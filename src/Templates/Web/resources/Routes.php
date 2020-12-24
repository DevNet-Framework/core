<?php

namespace Application;

use Artister\Web\Router\IRouteBuilder;
use Artister\Web\Http\HttpContext;
use Artister\System\Process\Task;

class Routes
{
    static function registerRoutes(IRouteBuilder $routes) : void
    {
        // example of endpoint route using Http verb Get
        /* $routes->mapGet("api/test", function(HttpContext $context) : Task
        {
            $data = [
                'Title'     => 'Test',
                'Content'   => 'This is Web API test'
            ];

            $content = json_encode($data);

            $context->Response->Headers->add("Content-Type", "application/json");
            $context->Response->Body->write($content);

            return task::completedTask();
        }); */
    }
}