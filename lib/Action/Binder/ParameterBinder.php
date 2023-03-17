<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Action\Binder;

use DevNet\Web\Action\ActionContext;

class ParameterBinder
{
    private ModelBinderProvider $modelBinderProvider;

    public function __construct(ModelBinderProvider $modelBinderProvider = null)
    {
        if (!$modelBinderProvider) {
            $modelBinderProvider = new ModelBinderProvider(new ModelBinder());
        }

        $this->modelBinderProvider = $modelBinderProvider;
    }

    public function resolveArguments(ActionContext $actionContext)
    {
        $arguments  = [];
        $parameters = $actionContext->ActionDescriptor->MethodInfo->getParameters();

        foreach ($parameters as $key => $parameter) {
            $modelName = $parameter->getName();
            $modelType = '';
            if ($parameter->getType()) {
                $modelType = $parameter->getType()->getName();
            }

            foreach ($this->modelBinderProvider as $modelBinder) {
                $bindingContext = new BindingContext(
                    $modelName,
                    $modelType,
                    $actionContext
                );

                $modelBinder->bind($bindingContext);
                if ($bindingContext->Result == true) {
                    break;
                }
            }

            $arguments[] = $bindingContext->Result;
        }

        return $arguments;
    }
}
