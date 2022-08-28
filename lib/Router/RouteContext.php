<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Router;

use DevNet\System\ObjectTrait;
use DevNet\Web\Http\HttpContext;

class RouteContext
{
    use ObjectTrait;

    private HttpContext $httpContext;
    private RouteData $routeData;
    private string $httpMethod;
    private string $urlPath;
    public ?object $Handler;

    public function __construct(HttpContext $httpContext)
    {
        $this->httpContext = $httpContext;
        $this->httpMethod  = $httpContext->Request->Method;
        $this->urlPath     = $httpContext->Request->Uri->Path;
        $this->routeData   = new RouteData();
        $this->Handler     = null;
    }

    public function get_HttpContext(): HttpContext
    {
        return $this->httpContext;
    }

    public function get_RouteData(): RouteData
    {
        return $this->routeData;
    }

    public function get_HttpMethod(): string
    {
        return $this->httpMethod;
    }

    public function get_UrlPath(): string
    {
        return $this->urlPath;
    }
}
