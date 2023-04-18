<?php

namespace Application;

use DevNet\Web\Extensions\ApplicationBuilderExtensions;
use DevNet\Web\Extensions\ServiceCollectionExtensions;
use DevNet\Web\Hosting\WebHost;

class Program
{
    public static function main(array $args = [])
    {
        $builder = WebHost::createDefaultBuilder($args);
        $configuration = $builder->ConfigBuilder->build();

        $builder->configureServices(function ($services) {
            // services
        });

        $host = $builder->build();

        $host->start(function ($app) use ($configuration) {
            if ($configuration->getValue('environment') == 'development') {
                $app->UseExceptionHandler();
            } else {
                $app->UseExceptionHandler("/home/error");
            }

            $app->useRouter();
            // middlewares
            $app->useEndpoint(function ($routes) {
                $routes->mapGet("/", fn () => "Hello World!");
            });
        });
    }
}
