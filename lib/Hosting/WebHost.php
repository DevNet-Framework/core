<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Hosting;

use Closure;
use DevNet\Web\Http\HttpContext;
use DevNet\Web\Http\HttpContextFactory;
use DevNet\Web\Middleware\IApplicationBuilder;
use DevNet\Web\Router\RouteBuilder;
use DevNet\System\Configuration\IConfiguration;
use DevNet\System\Dependency\IServiceProvider;
use DevNet\System\Runtime\LauncherProperties;
use DevNet\System\Runtime\Launcher;

class WebHost
{
    private IApplicationBuilder $AppBuilder;
    private IserviceProvider $Provider;
    private WebServer $Server;

    public function __construct(IApplicationBuilder $AppBuilder, IServiceProvider $provider)
    {
        $this->AppBuilder = $AppBuilder;
        $this->Provider   = $provider;
        $this->Server     = new WebServer();

        $launcher = Launcher::getLauncher();
        $launcher->Provider($provider);
    }

    public function start(Closure $configure): void
    {
        $configure($this->AppBuilder);
        $this->run();
    }

    public function run(): void
    {
        $config = $this->Provider->getService(IConfiguration::class);
        $args   = $config->Settings['args'] ?? [];

        $this->Server->start($args);

        $context    = $this->Provider->getService(HttpContext::class);
        $applicaion = $this->AppBuilder->build();

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
        $size = $context->Response->Body->getSize();
        if ($size > 0) {
            $response->Body->seek(0);
            while (!$response->Body->eof()) {
                echo $response->Body->read(1024 * 4);
            }
        }
        exit;
    }

    public static function createDefaultBuilder(array $args = []): WebHostBuilder
    {
        $basePath = LauncherProperties::getWorkspace();
        $builder  = new WebHostBuilder();

        $builder->ConfigBuilder->setBasePath($basePath);
        $builder->ConfigBuilder->addJsonFile("/settings.json");
        $builder->ConfigBuilder->addSetting('args', $args);

        $config = $builder->ConfigBuilder->build();
        $builder->Services->addSingleton(IConfiguration::class, $config);

        $builder->Services->addSingleton(HttpContext::class, function ($provider): HttpContext {
            $httpContext = HttpContextFactory::create();
            $httpContext->addAttribute('RequestServices', $provider);
            return $httpContext;
        });

        $builder->Services->addSingleton(RouteBuilder::class, fn ($provider) => new RouteBuilder($provider));

        return $builder;
    }
}
