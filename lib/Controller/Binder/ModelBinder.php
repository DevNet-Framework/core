<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Controller\Binder;

use DevNet\Core\Http\Form;

class ModelBinder implements IModelBinder
{
    public function bind(BindingContext $bindingContext)
    {
        $type = $bindingContext->Type;

        if (class_exists($type)) {
            $model = new $type();
            $form  = $bindingContext->ActionContext->HttpContext->Request->Form;

            foreach ($form->Fields as $key => $value) {
                if (property_exists($model, $key)) {
                    $model->$key = $value;
                }
            }

            foreach ($form->Files as $name => $upload) {
                if (property_exists($model, $name)) {
                    if (count($upload) == 1) {
                        $model->$name = $upload[0];
                    } else {
                        $model->$key = $upload;
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
