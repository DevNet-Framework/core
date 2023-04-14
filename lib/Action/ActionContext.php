<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Action;

use DevNet\System\PropertyTrait;
use DevNet\Web\Action\Binder\IValueProvider;
use DevNet\Web\Http\HttpContext;

class ActionContext
{
    use PropertyTrait;

    private ActionDescriptor $actionDescriptor;
    private HttpContext $httpContext;
    private IValueProvider $valueProvider;

    public function get_ActionDescriptor(): ActionDescriptor
    {
        return $this->actionDescriptor;
    }

    public function get_HttpContext(): HttpContext
    {
        return $this->httpContext;
    }

    public function get_ValueProvider(): IValueProvider
    {
        return $this->valueProvider;
    }

    public function __construct(ActionDescriptor $actionDescriptor, HttpContext $httpConext, IValueProvider $provider)
    {
        $this->actionDescriptor = $actionDescriptor;
        $this->httpContext      = $httpConext;
        $this->valueProvider    = $provider;
    }
}
