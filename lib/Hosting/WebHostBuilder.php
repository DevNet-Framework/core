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
use DevNet\System\Dependency\IServiceCollection;
use DevNet\System\Dependency\ServiceProvider;
use DevNet\System\Runtime\LauncherProperties;
use DevNet\System\Tweak;
use DevNet\Web\Http\HttpContext;
use DevNet\Web\Middleware\ApplicationBuilder;
use Closure;

class WebHostBuilder implements IWebHostBuilder
{
    use Tweak;

    private IConfiguration $configuration;
    private IServiceCollection $services;
    private ServiceProvider $provider;

    public function __construct(IConfiguration $configuration, IServiceCollection $services)
    {
        $this->configuration = $configuration;
        $this->services      = $services;
        $this->provider      = new ServiceProvider($services);
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
            $context->addAttribute('Error', $error);
        }
    }

    public function build(): WebHost
    {
        return new WebHost(new ApplicationBuilder($this->provider));
    }
}
