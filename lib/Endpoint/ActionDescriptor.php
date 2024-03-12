<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Endpoint;

use DevNet\System\PropertyTrait;
use ReflectionClass;
use ReflectionMethod;

class ActionDescriptor
{
    use PropertyTrait;

    private ReflectionClass $classInfo;
    private ReflectionMethod $methodInfo;
    private string $className;
    private string $actionName;
    private array $filterAttributes = [];

    public function __construct($target, string $actionName)
    {
        $this->classInfo  = new ReflectionClass($target);
        $this->methodInfo = new ReflectionMethod($target, $actionName);
        $this->className  = $this->methodInfo->getDeclaringClass()->getShortName();
        $this->actionName = $this->methodInfo->getName();

        /**
         * in the case of a method attribute having the same name as a class attribute,
         * the method attribute has precedence and must override the class attribute configurations.
         */
        $classAttributes  = $this->classInfo->getAttributes();
        $methodAttributes = $this->methodInfo->getAttributes();
        $attributes = array_merge($classAttributes, $methodAttributes);

        foreach ($attributes as $attribute) {
            $interfaces = class_implements($attribute->getName());
            if (in_array(IActionFilter::class, $interfaces)) {
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

    public function get_ClassName(): string
    {
        return $this->className;
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
