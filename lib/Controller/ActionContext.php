<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Controller;

use DevNet\Core\Controller\Binder\IValueProvider;
use DevNet\Core\Http\HttpContext;

class ActionContext
{
    private ActionDescriptor $ActionDescriptor;
    private HttpContext $HttpContext;
    private IValueProvider $ValueProvider;
    private array $ActionFilters;

    public function __construct(ActionDescriptor $actionDescriptor, HttpContext $httpConext, IValueProvider $provider)
    {
        $this->ActionDescriptor = $actionDescriptor;
        $this->HttpContext      = $httpConext;
        $this->ValueProvider    = $provider;
    }

    /**
     * read-only for all properties.
     * @return mixed property value depend on the property type.
     */
    public function __get(string $name)
    {
        return $this->$name;
    }
}
