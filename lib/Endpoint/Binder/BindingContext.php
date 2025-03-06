<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Endpoint\Binder;

use DevNet\Core\Endpoint\ActionContext;

class BindingContext
{
    private string $name;
    private ?string $type;
    private ActionContext $actionContext;
    private IValueProvider $valueProvider;
    private mixed $result = null;

    public string $Name { get => $this->name; }
    public ?string $Type { get => $this->type; }
    public ActionContext $ActionContext { get => $this->actionContext; }
    public IValueProvider $ValueProvider { get => $this->valueProvider; }
    public mixed $Result { get => $this->result; }

    public function __construct(string $name, string $type, ActionContext $actionContext, IValueProvider $valueProvider)
    {
        $this->name = $name;
        $this->type = $type;
        $this->actionContext = $actionContext;
        $this->valueProvider = $valueProvider;
    }

    public function success(mixed $model): void
    {
        $this->result = $model;
    }

    public function failed(): void
    {
        $this->result = null;
    }
}
