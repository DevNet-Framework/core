<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Router;

class RoutePathContext
{
    private ?string $routeName;
    private ?string $parameters;

    public function __construct(string $routeName = null, string $parameters = null)
    {
        $this->routeName = $routeName;
        $this->parameters = $parameters;
    }

    public function getRouteName() : string
    {
        return $this->routeName;
    }

    public function getParameters() : ?array
    {
        return $this->parameters;
    }
}