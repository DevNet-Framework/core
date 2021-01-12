<?php

namespace Application;

use Artister\System\Configuration\IConfiguration;
use Artister\System\Dependency\IServiceCollection;
use Artister\Web\Dispatcher\IApplicationBuilder;
use Artister\Web\Extensions\ServiceCollectionExtensions;
use Artister\Web\Extensions\ApplicationBuilderExtensions;

class Startup
{
    private IConfiguration $Configuration;

    public function __construct(IConfiguration $configuration)
    {
        $this->Configuration = $configuration;
    }

    public function configureServices(IServiceCollection $services)
    {
        $services->addMvc();

        $services->addAuthentication();

        $services->addAuthorisation();
    }

    public function configure(IApplicationBuilder $app)
    {
        $app->UseExceptionHandler();

        $app->useRouter();

        $app->useAuthentication();
        
        $app->useAuthorization();
        
        $app->useEndpoint(function($routes)
        {
            //Routes::registerRoutes($routes);
            $routes->mapRoute("default", "{controller=Home}/{action=Index}/{id?}");
        });
    }
}