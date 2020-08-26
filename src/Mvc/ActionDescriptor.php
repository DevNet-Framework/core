<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Mvc;

use ReflectionMethod;

class ActionDescriptor
{
    private ReflectionMethod $MethodInfo;
    private string $ControllerName;
    private string $ActionName;

    public function __construct($target, string $actionName)
    {
        $this->MethodInfo       = new ReflectionMethod($target, $actionName);
        $this->ClassName        = $this->MethodInfo->getDeclaringClass()->getName();
        $this->ActionName       = $actionName;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }
}