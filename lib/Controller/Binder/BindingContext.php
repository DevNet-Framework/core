<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Controller\Binder;

use DevNet\System\Exceptions\PropertyException;
use DevNet\Web\Controller\ActionContext;

class BindingContext
{
    private string $name;
    private ?string $type;
    private ActionContext $actionContext;
    private IValueProvider $valueProvider;
    private $result = null;

    public function __get(string $name)
    {
        if (in_array($name, ['Name', 'Type', 'ActionContext', 'ValueProvider', 'Result'])) {
            $property = lcfirst($name);
            return $this->$property;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . self::class . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . self::class . "::" . $name);
    }

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

    public function success($model): void
    {
        $this->result = $model;
    }

    public function failed(): void
    {
        $this->result = null;
    }
}
