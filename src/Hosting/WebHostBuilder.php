<?php declare(strict_types = 1);
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
use DevNet\System\Exceptions\ClassException;
use DevNet\System\Runtime\LauncherProperties;
use DevNet\Web\Dispatcher\ApplicationBuilder;
use DevNet\Web\Http\HttpContextFactory;
use DevNet\Web\Http\HttpContext;
use DevNet\Web\Router\RouteBuilder;
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

        $this->Services->addSingleton(HttpContext::class, function($provider) : HttpContext {
            $httpContext = HttpContextFactory::create();
            $httpContext->addAttribute('RequestServices', $provider);
            return $httpContext;
        });

        $this->Services->addSingleton(RouteBuilder::class, fn($provider) => new RouteBuilder($provider));
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
