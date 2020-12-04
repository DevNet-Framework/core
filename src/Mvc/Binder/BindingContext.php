<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Mvc\Binder;

use Artister\DevNet\Mvc\ActionContext;
use ReflectionFunctionAbstract;

class BindingContext
{
    private string $Name;
    private ?string $Type;
    private ActionContext $ActionContext;
    private IValueProvider $ValueProvider;
    private $Result = null;

    public function __construct(
        string $name,
        string $type,
        ActionContext $actionContext
    ){
        $this->Name = $name;
        $this->Type = $type;
        $this->ActionContext = $actionContext;
        $this->ValueProvider = $actionContext->ValueProvider;
    }

    /**
     * Magic method for read-only of all properties.
     * @return mixed depend on the property type.
     */
    public function __get(string $name)
    {
        return $this->$name;
    }

    public function success($model) : void
    {
        $this->Result = $model;
    }

    public function failed() : void
    {
        $this->Result = null;
    }
}