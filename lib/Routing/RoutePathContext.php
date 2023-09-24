<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Routing;

class RoutePathContext
{
    private ?string $routeName;
    private array $parameters;

    public function __construct(string $routeName = null, array $parameters = [])
    {
        $this->routeName = $routeName;
        $this->parameters = $parameters;
    }

    public function getRouteName(): string
    {
        return $this->routeName;
    }

    public function getParameters(): ?array
    {
        return $this->parameters;
    }
}
