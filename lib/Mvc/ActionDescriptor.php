<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Mvc;

use ReflectionMethod;

class ActionDescriptor
{
    private ReflectionMethod $MethodInfo;
    private string $ControllerName;
    private string $ActionName;

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __construct($target, string $actionName)
    {
        $this->MethodInfo     = new ReflectionMethod($target, $actionName);
        $this->ControllerName = $this->MethodInfo->getDeclaringClass()->getShortName();
        $this->ActionName     = $this->MethodInfo->getName();
    }
}
