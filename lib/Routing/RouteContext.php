<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Routing;

use DevNet\Http\Message\HttpContext;

class RouteContext
{
    private HttpContext $httpContext;
    private RouteData $routeData;
    private string $httpMethod;
    private string $urlPath;
    public ?object $Handler;

    public HttpContext $HttpContext { get => $this->httpContext; }
    public RouteData $RouteData { get => $this->routeData; }
    public string $HttpMethod { get => $this->httpMethod; }
    public string $UrlPath { get => $this->urlPath; }

    public function __construct(HttpContext $httpContext)
    {
        $this->httpContext = $httpContext;
        $this->httpMethod  = $httpContext->Request->Method;
        $this->urlPath     = $httpContext->Request->Url->Path;
        $this->routeData   = new RouteData();
        $this->Handler     = null;
    }
}
