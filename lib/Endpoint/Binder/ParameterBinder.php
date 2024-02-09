<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Endpoint\Binder;

use DevNet\Web\Endpoint\ActionContext;

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
