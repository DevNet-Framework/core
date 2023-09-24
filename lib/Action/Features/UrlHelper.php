<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Action\Features;

use DevNet\Web\Http\HttpContext;
use DevNet\Web\Routing\RoutePathContext;

class UrlHelper
{
    private HttpContext $httpContext;

    public function __construct(HttpContext $httpContext)
    {
        $this->httpContext = $httpContext;
    }

    public function route(string $routeName, array $values = []): string
    {
        if (!$values) {
            $values = $this->httpContext->RouteValues;
        }

        $path = new RoutePathContext($routeName, $values);
        $router = $this->httpContext->RouteContext->RouteData->Routers[0];

        return $router->getRoutePath($path);
    }
}
