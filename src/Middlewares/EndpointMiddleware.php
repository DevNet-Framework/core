<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Middlewares;

use Artister\DevNet\Http\HttpContext;
use Artister\DevNet\Dispatcher\IMiddleware;
use Artister\DevNet\Dispatcher\RequestDelegate;
use Artister\DevNet\Dependency\IServiceProvider;
use Artister\System\Process\Task;

class EndpointMiddleware implements IMiddleware
{
    private IServiceProvider $serviceProvider;

    public function __construct(IServiceProvider $serviceProvider)
    {
        $this->ServiceProvider  = $serviceProvider;
    }

    public function __invoke(HttpContext $context, RequestDelegate $next) : Task
    {
        $requestHandler = $context->Handler;

        if (!$requestHandler)
        {
            return $next($context);
        }

        return $requestHandler($context);
    }
}