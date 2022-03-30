<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Router;

use DevNet\System\Exceptions\PropertyException;
use DevNet\Web\Router\IRouter;
use DevNet\Web\Router\Internal\RouteParser;
use DevNet\Web\Router\Internal\RouteMatcher;
use DevNet\Web\Router\Internal\RouteGenerator;

class Route implements IRouter
{
    private string $name;
    private string $verb;
    private string $pattern;
    private IRouteHandler $handler;
    private array $data;

    public function __get(string $name)
    {
        if (in_array($name, ['Name', 'Verb', 'Pattern', 'Handler', 'Data'])) {
            $property = lcfirst($name);
            return $this->$property;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property" . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property" . get_class($this) . "::" . $name);
    }

    public function __construct(string $name, string $verb, string $pattern, IRouteHandler $handler)
    {
        $this->name = $name;
        $this->verb = $verb;
        $this->pattern = $pattern;
        $this->handler = $handler;
    }

    public function matchRoute(RouteContext $routeContext): bool
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

    public function getRoutePath(RoutePathContext $routePathContext): string
    {
        $parameters = $routePathContext->getParameters();
        $routePath = RouteGenerator::generatePath($this->pattern, $parameters);
        return $routePath;
    }
}
