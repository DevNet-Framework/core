<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http;

use DevNet\System\Collections\Enumerator;

class Cookies
{
    public array $Cookies = [];
    public Headers $Headers;

    public function __construct(Headers $headers)
    {
        $this->Headers = $headers;
        if ($headers->contains('cookie')) {
            $cookieString = $headers->getValues('cookie')[0];
            $cookieString = str_replace(' ', '', $cookieString);
            $cookieString = rtrim($cookieString, ';');
            $cookieFragments = explode(';', $cookieString);

            foreach ($cookieFragments as $fragment) {
                $cookie = explode('=', $fragment);
                if (isset($cookie[1])) {
                    $this->Cookies[$cookie[0]] = $cookie[1];
                }
            }
        }
    }

    public function getValue(String $name): ?string
    {
        return $this->Cookies[$name] ?? null;
    }

    public function add(String $name, string $value, CookieOptions $options = null): void
    {
        $cookie = "{$name}={$value};";
        if ($options) {
            $cookie .= "{$options->__toString()}";
        }

        $this->Cookies[$name] = $value;
        $this->Headers->add('Set-Cookie', $cookie);
    }

    public function contains(string $name): bool
    {
        return isset($this->Cookies[$name]);
    }

    public function remove(String $name)
    {
        if ($this->contains($name)) {
            $this->Headers->remove($name);
            unset($this->Cookies[$name]);
        }
    }

    public function getIterator(): iterable
    {
        return new Enumerator($this->Cookies);
    }
}
