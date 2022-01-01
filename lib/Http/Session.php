<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http;

class Session
{
    private string $Id;
    private string $Name;
    private array $Options = [];

    public function __construct(string $Name, ?int $lifetime = null, ?string $Path = null)
    {
        $this->Name = $Name;
        $this->Options['name'] = $Name;

        if (isset($lifetime)) {
            $this->Options['cookie_lifetime'] = $lifetime;
        }

        if (isset($Path)) {
            $this->Options['cookie_path'] = $Path;
        }

        if (isset($_COOKIE[$Name])) {
            $this->Id = $_COOKIE[$Name];
        } else {
            $this->Id = session_create_id();
        }
    }

    public function setOptions(array $Options)
    {
        $this->Options = array_merge($this->Options, $Options);
    }

    public function start(): void
    {
        $this->close();
        session_id($this->Id);
        session_start($this->Options);

        if ($this->has('SessionOptions')) {
            $this->Options = array_merge($this->get('SessionOptions'), $this->Options);
            $this->close();
            session_start($this->Options);
        }

        $_COOKIE[$this->Name] = $this->Id;
        //setcookie(session_name(), session_id(), time()+$lifetime);
        $this->set('SessionOptions', $this->Options);
    }

    public function isSet()
    {
        return isset($_COOKIE[$this->Name]) ? true : false;
    }

    public function regenerate(bool $DeleteOldSession = true): void
    {
        session_regenerate_id($DeleteOldSession);
        $this->Id = session_id();
        $_COOKIE[$this->Name] = $this->Id;
    }

    public function set(string $Name, $value)
    {
        $_SESSION[$Name] = $value;
    }

    public function get(string $Name)
    {
        return $_SESSION[$Name] ?? null;
    }

    public function has(string $Name): bool
    {
        return isset($_SESSION[$Name]);
    }

    public function remove(string $Name)
    {
        if (isset($_SESSION[$Name])) {
            unset($_SESSION[$Name]);
        }
    }

    public function getName()
    {
        return $this->Options['name'] ?? 'PHPSESSID';
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
