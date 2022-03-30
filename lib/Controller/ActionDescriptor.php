<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Controller;

use DevNet\System\Exceptions\PropertyException;
use ReflectionMethod;

class ActionDescriptor
{
    private ReflectionMethod $methodInfo;
    private string $controllerName;
    private string $actionName;

    public function __get(string $name)
    {
        if (in_array($name, ['MethodInfo', 'ControllerName', 'ActionName'])) {
            $property = lcfirst($name);
            return $this->$property;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property" . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property" . get_class($this) . "::" . $name);
    }

    public function __construct($target, string $actionName)
    {
        $this->methodInfo     = new ReflectionMethod($target, $actionName);
        $this->controllerName = $this->methodInfo->getDeclaringClass()->getShortName();
        $this->actionName     = $this->methodInfo->getName();
    }
}
