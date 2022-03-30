<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Router;

use DevNet\System\Exceptions\PropertyException;

class RouteContext
{
    private string $httpMethod;
    private string $urlPath;
    private RouteData $routeData;
    public ?object $Handler;

    public function __get(string $name)
    {
        if (in_array($name, ['HttpMethod', 'UrlPath', 'RouteData'])) {
            $property = lcfirst($name);
            return $this->$property;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property" . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property" . get_class($this) . "::" . $name);
    }

    public function __construct(string $httpMethod, string $urlPath)
    {
        $this->httpMethod = $httpMethod;
        $this->urlPath    = $urlPath;
        $this->routeData  = new RouteData();
        $this->Handler    = null;
    }
}
