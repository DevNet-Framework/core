<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Routing;

use DevNet\System\Async\Task;
use DevNet\Http\Middleware\IRequestHandler;
use DevNet\Http\Middleware\RequestDelegate;


class RouteHandler implements IRouteHandler
{
    private IRequestHandler|RequestDelegate $target;

    public mixed $Target { get => $this->target; set => $this->target = $value; }

    public function __construct(IRequestHandler|RequestDelegate $target)
    {
        $this->target = $target;
    }

    public function handle(RouteContext $routeContext): Task
    {
        $handler = $this->target;
        $handler = new RequestDelegate(function ($context) use ($handler) {
            $result = $handler($context);
            if ($result instanceof Task) {
                return $result->then(function ($previous) use ($context) {
                    $result = $previous->Result;
                    if (is_object($result) || is_array($result)) {
                        $context->Response->Headers->add("Content-Type", "application/json");
                        $content = json_encode($result);
                        $context->Response->Body->write($content);
                    } else if (is_string($result)) {
                        $context->Response->Headers->add("Content-Type", "text/plain");
                        $context->Response->Body->write($result);
                    }
                });
            } else if (is_object($result) || is_array($result)) {
                $context->Response->Headers->add("Content-Type", "application/json");
                $content = json_encode($result);
                $context->Response->Body->write($content);
            } else if (is_string($result)) {
                $context->Response->Headers->add("Content-Type", "text/plain");
                $context->Response->Body->write($result);
            }

            return Task::completedTask();
        });

        $routeContext->Handler = $handler;

        return Task::completedTask();
    }
}
