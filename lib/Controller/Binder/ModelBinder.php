<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Controller\Binder;

use DevNet\Web\Http\Form;

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
