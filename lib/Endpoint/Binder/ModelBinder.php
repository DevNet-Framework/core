<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Endpoint\Binder;

use ReflectionMethod;

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
                    $method = new ReflectionMethod($model, $name);
                    if ($method->isPublic()) {
                        $model->$name = $value;
                    }
                }
            }
            
            $form  = $bindingContext->ActionContext->HttpContext->Request->Form;
            foreach ($form->Fields as $name => $value) {
                if (property_exists($model, $name)) {
                    $method = new ReflectionMethod($model, $name);
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
