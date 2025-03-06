<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Endpoint;

use ReflectionClass;
use ReflectionMethod;

class ActionDescriptor
{
    private ReflectionClass $classInfo;
    private ReflectionMethod $methodInfo;
    private string $className;
    private string $actionName;
    private array $filterAttributes = [];

    public ReflectionClass $ClassInfo { get => $this->classInfo; }
    public ReflectionMethod $MethodInfo { get => $this->methodInfo; }
    public string $ClassName { get => $this->className; }
    public string $ActionName { get => $this->actionName; }
    public array $FilterAttributes { get => $this->filterAttributes; }

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
}
