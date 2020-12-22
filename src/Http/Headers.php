<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Http;

class Headers
{
    private array $HeaderNames = [];
    private array $HeaderValues = [];

    public function __construct(array $headerValues = [])
    {
        foreach ($headerValues as $name => $value)
        {
            $this->add($name, $value);
        }
    }

    public function contains(string $name) : bool
    {
        return isset($this->HeaderNames[strtolower($name)]);
    }

    public function add(string $name, string $value, bool $replace = true)
    {
        $normalized = strtolower($name);
        $this->HeaderNames[$normalized] = $name;
        $this->HeaderValues[$name][] = $value;
    }

    public function remove(string $name)
    {
        $normalized = strtolower($name);
        if (isset($this->HeaderNames[$normalized]))
        {
            $name = $this->HeaderNames[$normalized];
            unset($this->HeaderValues[$name]);
        }
    }

    public function getValues(string $name) : array
    {
        $normalized = strtolower($name);
        if (isset($this->HeaderNames[$normalized]))
        {
            $name = $this->HeaderNames[$normalized];
            return $this->HeaderValues[$name];
        }

        return [];
    }

    public function getAll()
    {
        return $this->HeaderValues;
    }
}
