<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Routing;

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
