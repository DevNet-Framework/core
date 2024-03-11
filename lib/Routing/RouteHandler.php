<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Routing;

use DevNet\System\Async\Task;
use DevNet\System\PropertyTrait;
use DevNet\Web\Http\Middleware\IRequestHandler;
use DevNet\Web\Http\Middleware\RequestDelegate;


class RouteHandler implements IRouteHandler
{
    use PropertyTrait;

    private IRequestHandler|RequestDelegate $target;
    private array $filters = [];

    public function __construct(IRequestHandler|RequestDelegate $target)
    {
        $this->target = $target;
    }

    public function get_Target()
    {
        return $this->target;
    }

    public function set_Target($value)
    {
        $this->target = $value;
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
