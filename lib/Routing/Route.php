<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Routing;

use DevNet\System\PropertyTrait;
use DevNet\Web\Routing\IRouter;
use DevNet\Web\Routing\Internal\RouteParser;
use DevNet\Web\Routing\Internal\RouteMatcher;

class Route implements IRouter
{
    use PropertyTrait;

    private IRouteHandler $handler;
    private string $pattern;
    private ?string $verb;
    private array $data = [];

    public function __construct(IRouteHandler $handler, string $pattern, ?string $verb = null)
    {
        $this->handler = $handler;
        $this->pattern = $pattern;
        $this->verb = $verb;
    }

    public function get_Handler(): IRouteHandler
    {
        return $this->handler;
    }

    public function get_Pattern(): string
    {
        return $this->pattern;
    }

    public function get_Verb(): string
    {
        return $this->verb;
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
