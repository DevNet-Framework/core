<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Controller;

use DevNet\System\ObjectTrait;
use DevNet\Web\Middleware\IMiddleware;
use ReflectionClass;
use ReflectionMethod;

class ActionDescriptor
{
    use ObjectTrait;

    private ReflectionClass $classInfo;
    private ReflectionMethod $methodInfo;
    private string $controllerName;
    private string $actionName;
    private array $filterAttributes = [];

    public function __construct($target, string $actionName)
    {
        $this->classInfo      = new ReflectionClass($target);
        $this->methodInfo     = new ReflectionMethod($target, $actionName);
        $this->controllerName = $this->methodInfo->getDeclaringClass()->getShortName();
        $this->actionName     = $this->methodInfo->getName();

        /**
         * in the case of a method attribute having the same name as a class attribute,
         * the method attribute has precedence and must override the class attribute configurations.
         */
        $classAttributes = $this->classInfo->getAttributes();
        $methodAttributes = $this->methodInfo->getAttributes();
        $attributes = array_merge($classAttributes, $methodAttributes);

        foreach ($attributes as $attribute) {
            $interfaces = class_implements($attribute->getName());
            if (in_array(IMiddleware::class, $interfaces)) {
                $this->filterAttributes[$attribute->getName()] = $attribute;
            }
        }
    }

    public function get_ClassInfo(): ReflectionClass
    {
        return $this->classInfo;
    }

    public function get_MethodInfo(): ReflectionMethod
    {
        return $this->methodInfo;
    }

    public function get_ControllerName(): string
    {
        return $this->controllerName;
    }

    public function get_ActionName(): string
    {
        return $this->actionName;
    }

    public function get_FilterAttributes(): array
    {
        return $this->filterAttributes;
    }
}
