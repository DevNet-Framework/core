<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Router;

use DevNet\Core\Router\IRouter;
use DevNet\Core\Router\Internal\RouteParser;
use DevNet\Core\Router\Internal\RouteMatcher;
use DevNet\Core\Router\Internal\RouteGenerator;

class Route implements IRouter
{
    private string $Name;
    private string $Verb;
    private string $Pattern;
    private IRouteHandler $Handler;
    private array $Data;

    public function __construct(string $name, string $verb, string $pattern, IRouteHandler $handler)
    {
        $this->Name = $name;
        $this->Verb = $verb;
        $this->Pattern = $pattern;
        $this->Handler = $handler;
    }

    /**
     * read-only for all properties.
     */
    public function __get(string $name)
    {
        return $this->$name;
    }

    public function matchRoute(RouteContext $routeContext): bool
    {
        $urlPath = $routeContext->UrlPath;
        $urlPath = RouteParser::parseUrlPath($urlPath);

        $urlPattern = RouteParser::parseUrlPattern($this->Pattern);
        $matched = RouteMatcher::matchUrl($urlPattern, $urlPath);

        if ($matched) {
            $httpMethod = $routeContext->HttpMethod;
            $allowed = RouteMatcher::matchMethod($httpMethod, $this->Verb);

            if ($allowed) {
                $this->Data = RouteParser::parsePlaceholders($matched);
                $routeContext->RouteData->Routers['matched'] = $this;
                $routeContext->RouteData->Values = $this->Data;
                $this->Handler->handle($routeContext);
                return true;
            }

            $routeContext->RouteData->Routers['unallowed'][] = $this;
            return false;
        }

        $routeContext->RouteData->Routers['unmached'][] = $this;
        return false;
    }

    public function getRoutePath(RoutePathContext $routePathContext): string
    {
        $parameters = $routePathContext->getParameters();
        $routePath = RouteGenerator::generatePath($this->Pattern, $parameters);
        return $routePath;
    }
}
