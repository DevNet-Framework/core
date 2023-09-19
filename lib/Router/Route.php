<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Router;

use DevNet\System\PropertyTrait;
use DevNet\Web\Router\IRouter;
use DevNet\Web\Router\Internal\RouteParser;
use DevNet\Web\Router\Internal\RouteMatcher;

class Route implements IRouter
{
    use PropertyTrait;

    private string $verb;
    private string $pattern;
    private IRouteHandler $handler;
    private array $data;

    public function __construct(string $verb, string $pattern, IRouteHandler $handler)
    {
        $this->verb = $verb;
        $this->pattern = $pattern;
        $this->handler = $handler;
    }

    public function get_Verb(): string
    {
        return $this->verb;
    }

    public function get_Pattern(): string
    {
        return $this->pattern;
    }

    public function get_Handler(): IRouteHandler
    {
        return $this->handler;
    }

    public function get_Data(): array
    {
        return $this->data;
    }

    public function match(RouteContext $routeContext): bool
    {
        $urlPath = $routeContext->UrlPath;
        $urlPath = RouteParser::parseUrlPath($urlPath);

        $urlPattern = RouteParser::parseUrlPattern($this->pattern);
        $matched = RouteMatcher::matchUrl($urlPattern, $urlPath);

        if ($matched) {
            $httpMethod = $routeContext->HttpMethod;
            $allowed = RouteMatcher::matchMethod($httpMethod, $this->verb);

            if ($allowed) {
                $this->data = RouteParser::parsePlaceholders($matched);
                $routeContext->RouteData->Routers['matched'] = $this;
                $routeContext->RouteData->Values = $this->data;
                $this->handler->handle($routeContext);
                return true;
            }

            $routeContext->RouteData->Routers['unallowed'][] = $this;
            return false;
        }

        $routeContext->RouteData->Routers['unmached'][] = $this;
        return false;
    }
}
