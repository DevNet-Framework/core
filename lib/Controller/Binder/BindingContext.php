<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Controller\Binder;

use DevNet\System\ObjectTrait;
use DevNet\Web\Controller\ActionContext;

class BindingContext
{
    use ObjectTrait;

    private string $name;
    private ?string $type;
    private ActionContext $actionContext;
    private IValueProvider $valueProvider;
    private $result = null;

    public function __construct(
        string $name,
        string $type,
        ActionContext $actionContext
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->actionContext = $actionContext;
        $this->valueProvider = $actionContext->ValueProvider;
    }

    public function get_Name(): string
    {
        return $this->name;
    }

    public function get_Type(): string
    {
        return $this->type;
    }

    public function get_ActionContext(): ActionContext
    {
        return $this->actionContext;
    }

    public function get_ValueProvider(): IValueProvider
    {
        return $this->valueProvider;
    }

    public function get_Result()
    {
        return $this->result;
    }

    public function success($model): void
    {
        $this->result = $model;
    }

    public function failed(): void
    {
        $this->result = null;
    }
}
