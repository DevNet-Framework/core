<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Configuration;

class ConfigurationBuilder
{
    private string $BasePath = '/';
    private array $Settings = [];

    public function __construct(array $settings = [])
    {
        $this->Settings = $settings;
    }

    public function setBasePath(string $basePath)
    {
        $this->BasePath = $basePath;
    }

    public function addSetting(string $key, $value)
    {
        $this->Settings[$key] = $value;
    }

    public function addJsonFile(string $path)
    {
        $fullPath = $this->BasePath . "/" . $path;

        if (!file_exists($fullPath)) {
            throw new \Exception("Not found file {$fullPath}");
        }

        $settings = file_get_contents($fullPath);
        $settings = json_decode($settings, true);
        $this->Settings = array_merge($this->Settings, $settings);
    }

    public function build(): IConfiguration
    {
        return new ConfigurationRoot($this->Settings);
    }
}
