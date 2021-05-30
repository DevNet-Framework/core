<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Hosting;

use DevNet\Web\Dispatcher\IApplicationBuilder;
use DevNet\Web\Http\HttpContext;
use DevNet\Web\Dependency\IServiceProvider;
use DevNet\Web\Configuration\IConfiguration;

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
    }

    public function run()
    {
        $config = $this->Provider->getService(IConfiguration::class);
        $port = $config->getValue('port');

        if ($port)
        {
            $this->Server->setPort(intval($port));
        }
        
        $this->Server->start();

        $context    = $this->Provider->getService(HttpContext::class);
        $applicaion = $this->AppBuilder->build();

        $applicaion($context)->wait();
        $response = $context->Response;
        
        // Send the "status line".
        $statusLine = $response->getStatusLine();
        header($statusLine, true);
        
        // Send the response headers from the headers list.
        foreach ($response->Headers->getAll() as $name => $values)
        {
            foreach ($values as $value)
            {
                // keep a previous similar header.
                header("$name: $value", false);
            }
        }
        
        // Output the message body.
        $size = $context->Response->Body->getSize();
        if ($size > 0)
        {
            $response->Body->seek(0);
            while (!$response->Body->eof())
            {
                echo $response->Body->read(1024);
            }
        }
        exit();
    }

    public static function createBuilder(array $args = []) : WebHostBuilder
    {
        $builder = new WebHostBuilder();
        
        $builder->configureApplication(function($config) use ($args)
        {
            $config->addJsonFile("/settings.json");
            $config->addCommandLine($args);
        });

        return $builder;
    }
}
