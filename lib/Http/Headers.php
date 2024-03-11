<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http;

class Headers
{
    private array $headerNames = [];
    private array $headerValues = [];

    public function __construct(array $headerValues = [])
    {
        foreach ($headerValues as $name => $value) {
            $this->add($name, $value);
        }
    }

    public function contains(string $name): bool
    {
        return isset($this->headerNames[strtolower($name)]);
    }

    public function add(string $name, string $value, bool $replace = true)
    {
        $normalized = strtolower($name);
        $this->headerNames[$normalized] = $name;
        $this->headerValues[$name][] = $value;
    }

    public function remove(string $name)
    {
        $normalized = strtolower($name);
        if (isset($this->headerNames[$normalized])) {
            $name = $this->headerNames[$normalized];
            unset($this->headerValues[$name]);
        }
    }

    public function getValues(string $name): array
    {
        $normalized = strtolower($name);
        if (isset($this->headerNames[$normalized])) {
            $name = $this->headerNames[$normalized];
            return $this->headerValues[$name];
        }

        return [];
    }

    public function getAll()
    {
        return $this->headerValues;
    }
}
