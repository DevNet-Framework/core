<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Controller;

use ReflectionMethod;

class ActionDescriptor
{
    private ReflectionMethod $MethodInfo;
    private string $ControllerName;
    private string $ActionName;

    public function __construct($target, string $actionName)
    {
        $this->MethodInfo     = new ReflectionMethod($target, $actionName);
        $this->ControllerName = substr(strrchr($this->MethodInfo->getDeclaringClass()->getName(), "\\"), 1);
        $this->ActionName     = $actionName;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }
}
