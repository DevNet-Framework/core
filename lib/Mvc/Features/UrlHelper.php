<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Mvc\Features;

use DevNet\Core\Http\HttpContext;
use DevNet\Core\Router\RoutePathContext;

class UrlHelper
{
    private HttpContext $HttpContext;

    public function __construct(HttpContext $httpContext)
    {
        $this->HttpContext = $httpContext;
    }

    public function route(string $routeName, array $values = []): string
    {
        if (!$values) {
            $values = $this->HttpContext->RouteValues;
        }

        $path = new RoutePathContext($routeName, $values);
        $router = $this->HttpContext->RouteContext->RouteData->Routers[0];

        return $router->getRoutePath($path);
    }
}
