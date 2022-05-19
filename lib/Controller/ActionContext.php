<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Controller;

use DevNet\System\Exceptions\PropertyException;
use DevNet\Web\Controller\Binder\IValueProvider;
use DevNet\Web\Http\HttpContext;

class ActionContext
{
    private ActionDescriptor $actionDescriptor;
    private HttpContext $httpContext;
    private IValueProvider $valueProvider;

    public function __get(string $name)
    {
        if (in_array($name, ['ActionDescriptor', 'HttpContext', 'ValueProvider'])) {
            $property = lcfirst($name);
            return $this->$property;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . get_class($this) . "::" . $name);
    }

    public function __construct(ActionDescriptor $actionDescriptor, HttpContext $httpConext, IValueProvider $provider)
    {
        $this->actionDescriptor = $actionDescriptor;
        $this->httpContext      = $httpConext;
        $this->valueProvider    = $provider;
    }
}
