<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Routing;

use DevNet\System\PropertyTrait;
use DevNet\Web\Http\HttpContext;

class RouteContext
{
    use PropertyTrait;

    private HttpContext $httpContext;
    private RouteData $routeData;
    private string $httpMethod;
    private string $urlPath;
    public ?object $Handler;

    public function __construct(HttpContext $httpContext)
    {
        $this->httpContext = $httpContext;
        $this->httpMethod  = $httpContext->Request->Method;
        $this->urlPath     = $httpContext->Request->Path;
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
