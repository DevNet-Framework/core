<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Configuration;

class ConfigurationRoot implements IConfiguration
{
    private array $Settings = [];

    public function __construct(array $settings = [])
    {
        $this->Settings = $settings;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function getValue(string $key)
    {
        $keys = explode(":", $key);

        $value = $this->Settings;

        foreach ($keys as $key)
        {
            $value = $value[$key] ?? null;

            if ($value == null)
            {
                return null;
            }
        }
        
        if (is_array($value))
        {
            return null;
        }

        return $value;
    }

    public function getSection(string $key) : IConfiguration
    {
        return new ConfigurationSection($this, $key);
    }

    public function getChildren(string $key = '') : array
    {
        $path = $key;
        $keys = explode(":", $key);

        $settings = $this->Settings;

        foreach ($keys as $key)
        {
            $settings = $settings[$key] ?? null;

            if (!is_array($settings))
            {
                return [];
            }
        }

        $children   = [];
        $keys       = array_keys($settings);

        foreach ($keys as $key)
        {
            if ($path != '')
            {
                $key = $path.":".$key;
            }

            $children[] = new ConfigurationSection($this, $key);
        }

        return $children;
    }
}
