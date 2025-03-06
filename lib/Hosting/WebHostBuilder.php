<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Hosting;

use DevNet\Common\Configuration\IConfiguration;
use DevNet\Common\Configuration\ConfigurationBuilder;
use DevNet\Common\Dependency\IServiceCollection;
use DevNet\Common\Dependency\ServiceProvider;
use DevNet\System\Runtime\LauncherProperties;
use DevNet\Http\Message\HttpContext;
use Closure;

class WebHostBuilder implements IWebHostBuilder
{
    private IConfiguration $configuration;
    private IServiceCollection $services;
    private ServiceProvider $provider;
    private WebHostEnvironment $environment;

    public IConfiguration $Configuration { get => $this->configuration; }
    public IServiceCollection $Services { get => $this->services; }

    public function __construct(IConfiguration $configuration, IServiceCollection $services)
    {
        $this->configuration = $configuration;
        $this->services      = $services;
        $this->provider      = new ServiceProvider($services);
        $this->environment   = new WebHostEnvironment();
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
