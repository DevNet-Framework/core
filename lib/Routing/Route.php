<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Routing;

use DevNet\System\PropertyTrait;
use DevNet\Core\Routing\IRouter;
use DevNet\Core\Routing\Internal\RouteParser;
use DevNet\Core\Routing\Internal\RouteMatcher;

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

            $routeContext->RouteData->Routers['forbidden'][] = $this;
            return false;
        }

        $routeContext->RouteData->Routers['unmatched'][] = $this;
        return false;
    }
}
