<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Mvc\Binder;

class ModelBinder implements IModelBinder
{
    public function bind(BindingContext $bindingContext)
    {
        $valueProvider = $bindingContext->ValueProvider;

        if ($valueProvider->contains($bindingContext->Name))
        {
            $model = $valueProvider->getValue($bindingContext->Name);
            $bindingContext->success($model);
        }
        else
        {
            $bindingContext->failed();
        }
    }
}