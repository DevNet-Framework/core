<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Hosting;

use DevNet\Core\Configuration\IConfiguration;
use DevNet\Core\Configuration\ConfigurationBuilder;
use DevNet\Core\Dependency\ServiceCollection;
use DevNet\Core\Dependency\ServiceProvider;
use DevNet\Core\Middleware\ApplicationBuilder;
use DevNet\System\Exceptions\ClassException;
use DevNet\System\Loader\LauncherProperties;
use Closure;

class WebHostBuilder implements IWebHostBuilder
{
    private ConfigurationBuilder $ConfigBuilder;
    private ServiceCollection $Services;
    private ServiceProvider $Provider;
    private ApplicationBuilder $AppBuilder;

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __construct()
    {
        $this->ConfigBuilder = new ConfigurationBuilder();
        $this->Services      = new ServiceCollection();
        $this->Provider      = new ServiceProvider($this->Services);
        $this->AppBuilder    = new ApplicationBuilder($this->Provider);
    }

    public function useConfiguration(Closure $configure)
    {
        $basePath = LauncherProperties::getWorkspace();
        $this->ConfigBuilder->setBasePath($basePath);
        $configure($this->ConfigBuilder);

        return $this;
    }

    public function useSetting(string $key, string $value)
    {
        $this->ConfigBuilder->addSetting($key, $value);
        return $this;
    }

    public function configureServices(Closure $configureServices)
    {
        $configureServices($this->Services);
        return $this;
    }

    public function useStartup(string $startup)
    {
        if (!class_exists($startup)) {
            throw ClassException::classNotFound($startup);
        }

        $config = $this->ConfigBuilder->build();
        $this->Services->addSingleton(IConfiguration::class, $config);

        $startup = new $startup($config);
        $startup->configureServices($this->Services);
        $startup->configure($this->AppBuilder);

        return $this;
    }

    public function build(): WebHost
    {
        return new WebHost($this->AppBuilder, $this->Provider);
    }
}
