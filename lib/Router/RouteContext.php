<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Router;

class RouteContext
{
    private string $HttpMethod;
    private string $UrlPath;
    private RouteData $RouteData;
    public ?object $Handler;

    public function __construct(string $httpMethod, string $urlPath)
    {
        $this->HttpMethod = $httpMethod;
        $this->UrlPath    = $urlPath;
        $this->RouteData  = new RouteData();
        $this->Handler    = null;
    }

    /**
     * Magic method read-only for all properties.
     * @return mixed depend on the property type.
     */
    public function __get(string $name)
    {
        switch ($name) {
            case 'UrlPath':
            case 'HttpMethod':
            case 'RouteData':
            case 'RouteHandler':
                return $this->$name;
                break;
            default:
                throw new \DomainException("Undefined property '$name'");
                break;
        }
    }
}
