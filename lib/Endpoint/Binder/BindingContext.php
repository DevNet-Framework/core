<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Endpoint\Binder;

use DevNet\System\PropertyTrait;
use DevNet\Web\Endpoint\ActionContext;

class BindingContext
{
    use PropertyTrait;

    private string $name;
    private ?string $type;
    private ActionContext $actionContext;
    private IValueProvider $valueProvider;
    private $result = null;

    public function __construct(string $name, string $type, ActionContext $actionContext, IValueProvider $valueProvider)
    {
        $this->name = $name;
        $this->type = $type;
        $this->actionContext = $actionContext;
        $this->valueProvider = $valueProvider;
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
