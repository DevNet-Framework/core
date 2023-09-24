<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Hosting;

use DevNet\System\Configuration\ConfigurationBuilder;
use DevNet\System\Configuration\IConfiguration;
use DevNet\System\Dependency\IServiceProvider;
use DevNet\System\Dependency\ServiceCollection;
use DevNet\System\Runtime\LauncherProperties;
use DevNet\Web\Http\HttpContext;
use DevNet\Web\Http\HttpContextFactory;
use DevNet\Web\Middleware\IApplicationBuilder;
use DevNet\Web\Routing\RouteBuilder;
use Closure;

class WebHost
{
    private IApplicationBuilder $appBuilder;
    private IserviceProvider $provider;
    private WebServer $server;

    public function __construct(IApplicationBuilder $AppBuilder)
    {
        $this->appBuilder = $AppBuilder;
        $this->provider   = $AppBuilder->Provider;
        $this->server     = new WebServer();
    }

    public function start(Closure $configure): void
    {
        if (PHP_SAPI == 'cli') {
            $configure($this->appBuilder);
            $this->run();
            return;
        }

        try {
            $configure($this->appBuilder);
        } catch (\Throwable $error) {
            $context = $this->provider->getService(HttpContext::class);
            $context->addAttribute('Error', $error);
        }
        
        $this->run();
    }

    public function run(): void
    {
        $config = $this->provider->getService(IConfiguration::class);
        $args   = $config->Settings['args'] ?? [];

        $this->server->start($args);
        $context    = $this->provider->getService(HttpContext::class);
        $applicaion = $this->appBuilder->build();
        
        if (PHP_SAPI == 'cli') {
            return;
        }

        $applicaion($context)->wait();
        $response = $context->Response;

        // Send the "status line".
        $statusLine = $response->getStatusLine();
        header($statusLine, true);

        // Send the response headers from the headers list.
        foreach ($response->Headers->getAll() as $name => $values) {
            foreach ($values as $value) {
                // keep a previous similar header.
                header("$name: $value", false);
            }
        }

        // Output the message body.
        if ($context->Response->Body->Length > 0) {
            $response->Body->seek(0);
            while (!$response->Body->EndOfStream) {
                echo $response->Body->read(1024 * 4);
            }
        }
    }

    public static function createDefaultBuilder(array $args = []): WebHostBuilder
    {
        $basePath = LauncherProperties::getRootDirectory();
        $configuration = new ConfigurationBuilder();
        $configuration->setBasePath($basePath);
        $configuration->addJsonFile("/settings.json");
        $configuration->addSetting('args', $args);

        $services = new ServiceCollection();

        $services->addSingleton(IConfiguration::class, function () use ($configuration): IConfiguration {
            return $configuration->build();
        });
        
        $services->addSingleton(HttpContext::class, function ($provider): HttpContext {
            $httpContext = HttpContextFactory::create();
            $httpContext->addAttribute('RequestServices', $provider);
            return $httpContext;
        });

        $services->addSingleton(RouteBuilder::class, fn (): RouteBuilder => new RouteBuilder());

        return new WebHostBuilder($configuration->build(), $services);
    }
}
