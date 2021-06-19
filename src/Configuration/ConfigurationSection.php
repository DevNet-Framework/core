<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Configuration;

class ConfigurationSection implements IConfiguration
{
    private IConfiguration $Root;
    private string $Path;

    public function __construct(IConfiguration $root, string $path)
    {
        $this->Root = $root;
        $this->Path = $path;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function getValue(string $key)
    {
        return $this->Root->getValue($this->Path.":".$key);
    }

    public function getSection(string $key) : IConfiguration
    {
        return new ConfigurationSection($this->Root, $this->Path.":".$key);
    }

    public function getChildren() : array
    {
        return $this->Root->getChildren($this->Path);
    }
}
