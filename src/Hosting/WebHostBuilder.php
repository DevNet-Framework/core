<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Hosting;

use Artister\Web\Dispatcher\ApplicationBuilder;
use Artister\System\Configuration\IConfiguration;
use Artister\System\Configuration\ConfigurationBuilder;
use Artister\System\Dependency\ServiceCollection;
use Artister\System\Dependency\ServiceProvider;
use Artister\System\Exceptions\ClassException;
use Artister\System\Boot\LauncherProperties;
use Closure;

class WebHostBuilder implements IWebHostBuilder
{
    private ConfigurationBuilder $ConfigBuilder;
    private ServiceCollection $Services;
    private ServiceProvider $Provider;
    private ApplicationBuilder $AppBuilder;

    public function __construct()
    {
        $this->ConfigBuilder    = new ConfigurationBuilder();
        $this->Services         = new ServiceCollection();
        $this->Provider         = new ServiceProvider($this->Services);
        $this->AppBuilder       = new ApplicationBuilder($this->Provider);
    }

    public function configureServices(Closure $configureServices)
    {
        $configureServices($this->Services);
        return $this;
    }

    public function configureApplication(Closure $configureApp)
    {
        $basePath = LauncherProperties::getWorkspace();
        $this->ConfigBuilder->addBasePath($basePath);
        $configureApp($this->ConfigBuilder);

        return $this;
    }

    public function useSetting(string $key, string $value)
    {
        $this->ConfigBuilder->addSetting($key, $value);
        return $this;
    }

    public function useConfiguration(string $key, string $value)
    {
        $this->Config[$key] = $value;
        return $this;
    }

    public function useStartup(string $startup)
    {
        if (!class_exists($startup))
        {
            throw ClassException::classNotFound($startup);
        }

        $config = $this->ConfigBuilder->build();
        $this->Services->addSingleton(IConfiguration::class, $config);

        $startup = new $startup($config);
        $startup->configureServices($this->Services);
        $startup->configure($this->AppBuilder);

        return $this;
    }

    public function build() : WebHost
    {
        $webHost = new WebHost($this->AppBuilder, $this->Provider);
        return $webHost;
    }
}