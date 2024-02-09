<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Hosting;

use DevNet\System\Configuration\IConfiguration;
use DevNet\System\Configuration\ConfigurationBuilder;
use DevNet\System\Dependency\IServiceCollection;
use DevNet\System\Dependency\ServiceProvider;
use DevNet\System\Runtime\LauncherProperties;
use DevNet\System\PropertyTrait;
use DevNet\Web\Http\HttpContext;
use DevNet\Web\Middleware\ApplicationBuilder;
use Closure;

class WebHostBuilder implements IWebHostBuilder
{
    use PropertyTrait;

    private IConfiguration $configuration;
    private IServiceCollection $services;
    private ServiceProvider $provider;
    private WebHostEnvironment $environment;

    public function __construct(IConfiguration $configuration, IServiceCollection $services)
    {
        $this->configuration = $configuration;
        $this->services      = $services;
        $this->provider      = new ServiceProvider($services);
        $this->environment   = new WebHostEnvironment();
    }

    public function get_Configuration(): IConfiguration
    {
        return $this->configuration;
    }

    public function get_Services(): IServiceCollection
    {
        return $this->services;
    }

    public function configure(Closure $configure): void
    {
        $basePath = LauncherProperties::getRootDirectory();
        $configuration = new ConfigurationBuilder();
        $configuration->setBasePath($basePath);
        $configure($configuration);
        $this->configuration = $configuration->build();
    }

    public function register(Closure $register): void
    {
        if (PHP_SAPI == 'cli') {
            $register($this->services);
            return;
        }

        try {
            $register($this->services);
        } catch (\Throwable $error) {
            $context = $this->provider->getService(HttpContext::class);
            $context->Items->add('ErrorException', $error);
        }
    }

    public function build(): WebHost
    {
        return new WebHost(new ApplicationBuilder($this->environment, $this->provider));
    }
}
