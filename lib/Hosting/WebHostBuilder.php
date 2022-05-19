<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Hosting;

use DevNet\System\Configuration\IConfiguration;
use DevNet\System\Configuration\ConfigurationBuilder;
use DevNet\System\Dependency\ServiceCollection;
use DevNet\System\Dependency\ServiceProvider;
use DevNet\System\Runtime\LauncherProperties;
use DevNet\System\Exceptions\ClassException;
use DevNet\System\Exceptions\PropertyException;
use DevNet\Web\Middleware\ApplicationBuilder;
use DevNet\Web\Http\HttpContext;
use Closure;

class WebHostBuilder implements IWebHostBuilder
{
    /**
     * @var ConfigurationBuilder $ConfigBuilder {get}
     * @var ServiceCollection $Services {get}
     */

    private ConfigurationBuilder $configBuilder;
    private ServiceCollection $services;
    private ServiceProvider $provider;
    private ApplicationBuilder $appBuilder;

    public function __get(string $name)
    {
        if ($name == 'ConfigBuilder') {
            return $this->configBuilder;
        }

        if ($name == 'Services') {
            return $this->services;
        }
        
        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . get_class($this) . "::" . $name);
    }

    public function __construct()
    {
        $this->configBuilder = new ConfigurationBuilder();
        $this->services      = new ServiceCollection();
        $this->provider      = new ServiceProvider($this->services);
        $this->appBuilder    = new ApplicationBuilder($this->provider);
    }

    public function useConfiguration(Closure $configure)
    {
        $basePath = LauncherProperties::getWorkspace();
        $this->configBuilder->setBasePath($basePath);
        $configure($this->configBuilder);

        return $this;
    }

    public function useSetting(string $key, string $value)
    {
        $this->configBuilder->addSetting($key, $value);
        return $this;
    }

    public function configureServices(Closure $configureServices)
    {
        if (PHP_SAPI == 'cli') {
            $configureServices($this->services);
            return $this;
        }
    
        try {
            $configureServices($this->services);
        } catch (\Throwable $error) {
            $context = $this->provider->getService(HttpContext::class);
            $context->addAttribute('Error', $error);
        }
        
        return $this;
    }

    public function useStartup(string $startup)
    {
        if (!class_exists($startup)) {
            throw ClassException::classNotFound($startup);
        }

        $config = $this->configBuilder->build();
        $this->services->addSingleton(IConfiguration::class, $config);

        $startup = new $startup($config);
        $startup->configureServices($this->services);
        $startup->configure($this->appBuilder);

        return $this;
    }

    public function build(): WebHost
    {
        return new WebHost($this->appBuilder, $this->provider);
    }
}
