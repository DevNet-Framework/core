<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Configuration;

class ConfigurationBuilder
{
    private string $BasePath;
    private array $Settings;

    public function __construct(array $settings = [])
    {
        $this->Settings = $settings;
    }

    public function addBasePath(string $basePath)
    {
        $this->BasePath = $basePath;
    }

    public function addSetting(string $key, $value)
    {
        $setting = [$key, $value];
        $this->Settings = array_merge($this->Settings, $setting);
    }

    public function addJsonFile(string $path)
    {
        $fullPath = $this->BasePath."/".$path;

        if (!file_exists($fullPath))
        {
            throw new \Exception("Not found file {$fullPath}");
        }

        $settings = file_get_contents($fullPath);
        $settings = json_decode($settings, true);
        $this->Settings = array_merge($this->Settings, $settings);
    }

    public function addCommandLine(array $args)
    {
        $options = [];
        $key = null;
        foreach ($args as $arg)
        {
            if (str_starts_with($arg, '--'))
            {
                $key = ltrim($arg, '--');
            }
            else
            {
                if ($key)
                {
                    $options[$key] = $arg;
                    $key = null;
                }
            }
        }

        $this->Settings = array_merge($this->Settings, $options);
    }

    public function build() : IConfiguration
    {
       return new ConfigurationRoot($this->Settings);
    }
}
