<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Endpoint\Binder;

use ReflectionMethod;
use ReflectionProperty;

class ModelBinder implements IModelBinder
{
    public function bind(BindingContext $bindingContext)
    {
        $type = $bindingContext->Type;

        if (class_exists($type)) {
            $model = new $type();
            $query  = $bindingContext->ActionContext->HttpContext->Request->Query;
            foreach ($query as $name => $value) {
                if (property_exists($model, $name)) {
                    $method = new ReflectionProperty($model, $name);
                    if ($method->isPublic()) {
                        $model->$name = $value;
                    }
                }
            }
            
            $form  = $bindingContext->ActionContext->HttpContext->Request->Form;
            foreach ($form->Fields as $name => $value) {
                if (property_exists($model, $name)) {
                    $method = new ReflectionProperty($model, $name);
                    if ($method->isPublic()) {
                        $model->$name = $value;
                    }
                }
            }

            foreach ($form->Files as $name => $upload) {
                if (property_exists($model, $name)) {
                    $method = new ReflectionMethod($model, $name);
                    if ($method->isPublic()) {
                        if (count($upload) == 1) {
                            $model->$name = $upload[0];
                        } else {
                            $model->$name = $upload;
                        }
                    }
                }
            }

            $bindingContext->success($model);
        } else {
            $valueProvider = $bindingContext->ValueProvider;

            if ($valueProvider->contains($bindingContext->Name)) {
                $model = $valueProvider->getValue($bindingContext->Name);
                $bindingContext->success($model);
            } else {
                $bindingContext->failed();
            }
        }
    }
}
