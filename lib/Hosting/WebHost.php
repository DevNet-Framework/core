<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Hosting;

use DevNet\Common\Configuration\ConfigurationBuilder;
use DevNet\Common\Configuration\IConfiguration;
use DevNet\Common\Dependency\IServiceProvider;
use DevNet\Common\Dependency\ServiceCollection;
use DevNet\Core\Middlewares\ExceptionHandlerMiddleware;
use DevNet\Core\Routing\IRouteBuilder;
use DevNet\Core\Routing\RouteBuilder;
use DevNet\Http\Message\HttpContext;
use DevNet\Http\Message\HttpContextFactory;
use DevNet\System\Async\Task;
use DevNet\System\Runtime\LauncherProperties;
use Closure;

class WebHost
{
    private IApplicationBuilder $appBuilder;
    private IServiceProvider $provider;
    private WebServer $server;

    public function __construct(IApplicationBuilder $AppBuilder)
    {
        $this->appBuilder = $AppBuilder;
        $this->provider   = $AppBuilder->Provider;
        $this->server     = new WebServer();
    }

    public function start(Closure $configure): void
    {
        if ($this->appBuilder->Environment->isDevelopment()) {
            $this->appBuilder->use(new ExceptionHandlerMiddleware());
        }

        if (PHP_SAPI == 'cli') {
            $configure($this->appBuilder);
            $this->run();
            return;
        }

        $context = $this->provider->getService(HttpContext::class);
        try {
            // Must throw the previous error exception if it exists, before catching the next one.
            $error = $context->Items['ErrorException'] ?? null;
            if ($error) {
                throw $error;
            }
            $configure($this->appBuilder);
        } catch (\Throwable $error) {
            $context->Items->add('ErrorException', $error);
        }

        $this->run();
    }

    public function run(): void
    {
        $config = $this->provider->getService(IConfiguration::class);
        $args   = $config->Settings['args'] ?? [];

        $this->server->start($args);
        $context     = $this->provider->getService(HttpContext::class);
        $application = $this->appBuilder->build();

        if (PHP_SAPI == 'cli') {
            return;
        }

        $result = $application($context);
        if ($result instanceof Task) {
            $result->wait();
        }

        // Send the "status line".
        $statusLine = $context->Response->getStatusLine();
        header($statusLine, true);

        // Send the response headers from the headers list.
        foreach ($context->Response->Headers->getAll() as $name => $values) {
            foreach ($values as $value) {
                // keep a previous similar header.
                header("$name: $value", false);
            }
        }

        // Output the message body.
        if ($context->Response->Body->Length > 0) {
            $context->Response->Body->seek(0);
            echo $context->Response->Body->read($context->Response->Body->Length);
        }
    }

    public static function createDefaultBuilder(array $args = []): WebHostBuilder
    {
        $basePath = LauncherProperties::getRootDirectory();
        $configuration = new ConfigurationBuilder();

        if (is_file($basePath . "/settings.json")) {
            $configuration->setBasePath($basePath);
            $configuration->addJsonFile("/settings.json");
        }

        $configuration->addSetting('args', $args);

        $services = new ServiceCollection();
        $services->addSingleton(IConfiguration::class, function () use ($configuration): IConfiguration {
            return $configuration->build();
        });

        $services->addSingleton(HttpContext::class, function ($provider): HttpContext {
            $httpContext = HttpContextFactory::create();
            $httpContext->Services = $provider;
            return $httpContext;
        });

        $services->addSingleton(IRouteBuilder::class, fn (): RouteBuilder => new RouteBuilder());

        return new WebHostBuilder($configuration->build(), $services);
    }
}
