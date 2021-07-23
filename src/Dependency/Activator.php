<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Dependency;

use DevNet\System\Exceptions\ClassException;
use ReflectionClass;

class Activator
{
    public static function CreateInstance(string $type, IServiceProvider $provider = null)
    {
        if (!class_exists($type)) {
            throw ClassException::classNotFound($type);
        }

        $classInfo  = new ReflectionClass($type);
        $methodInfo = $classInfo->getConstructor();

        if (!$methodInfo || !$provider) {
            return $classInfo->newInstance();
        }

        $parameters = $methodInfo->getParameters();
        $arguments  = [];

        foreach ($parameters as $parameter) {
            $parameterType = '';
            if ($parameter->getType()) {
                $parameterType = $parameter->getType()->getName();
            }

            if (!$provider->contains($parameterType)) {
                break;
            }

            $arguments[] = $provider->getService($parameterType);
        }

        return $classInfo->newInstanceArgs($arguments);
    }
}
