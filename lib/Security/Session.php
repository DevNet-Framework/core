<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security;

class Session
{
    private string $id;
    private string $name;
    private array $options = [];

    public function __construct(string $name, ?int $lifetime = null, ?string $path = null)
    {
        $this->name = $name;
        $this->options['name'] = $name;

        if (isset($lifetime)) {
            $this->options['cookie_lifetime'] = $lifetime;
        }

        if (isset($path)) {
            $this->options['cookie_path'] = $path;
        }

        if (isset($_COOKIE[$name])) {
            $this->id = $_COOKIE[$name];
        } else {
            $this->id = session_create_id();
        }
    }

    public function setOptions(array $options)
    {
        $this->options = array_merge($this->options, $options);
    }

    public function start(): void
    {
        $this->close();
        session_id($this->id);
        session_start($this->options);

        if ($this->has('SessionOptions')) {
            $this->options = array_merge($this->get('SessionOptions'), $this->options);
            $this->close();
            session_start($this->options);
        }

        $_COOKIE[$this->name] = $this->id;
        //setcookie(session_name(), session_id(), time()+$lifetime);
        $this->set('SessionOptions', $this->options);
    }

    public function isSet()
    {
        return isset($_COOKIE[$this->name]) ? true : false;
    }

    public function regenerate(bool $deleteOldSession = true): void
    {
        session_regenerate_id($deleteOldSession);
        $this->id = session_id();
        $_COOKIE[$this->name] = $this->id;
    }

    public function set(string $name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function get(string $name)
    {
        return $_SESSION[$name] ?? null;
    }

    public function has(string $name): bool
    {
        return isset($_SESSION[$name]);
    }

    public function remove(string $name)
    {
        if (isset($_SESSION[$name])) {
            unset($_SESSION[$name]);
        }
    }

    public function getName()
    {
        return $this->options['name'] ?? 'PHPSESSID';
    }

    public function getId(): string
    {
        return session_id();
    }

    public function getStatus()
    {
        return session_status();
    }

    public function close()
    {
        session_write_close();
    }

    public function destroy()
    {
        // Initialize the session.
        $this->start();

        // Unset all of the session variables.
        $_SESSION = array();

        // delete the session cookie.
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Unset session cookie variable.
        if (isset($_COOKIE[$this->getName()])) {
            unset($_COOKIE[$this->getName()]);
        }

        // destroy the session.
        session_destroy();
    }
}
