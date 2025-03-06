<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Routing;

use DevNet\Core\Routing\IRouter;
use DevNet\Core\Routing\Internal\RouteParser;
use DevNet\Core\Routing\Internal\RouteMatcher;

class Route implements IRouter
{
    private IRouteHandler $handler;
    private string $pattern;
    private ?string $verb;
    private array $data = [];

    public IRouteHandler $Handler { get => $this->handler; }
    public string $Pattern { get => $this->pattern; }
    public ?string $Verb { get => $this->verb; }
    public ?array $Data { get => $this->data; }

    public function __construct(IRouteHandler $handler, string $pattern, ?string $verb = null)
    {
        $this->handler = $handler;
        $this->pattern = $pattern;
        $this->verb = $verb;
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
