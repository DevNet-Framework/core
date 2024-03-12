<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

 namespace DevNet\Core\Endpoint\Filters;

use DevNet\System\Async\Task;
use DevNet\Core\Endpoint\ActionContext;
use DevNet\Core\Endpoint\ActionDelegate;
use DevNet\Core\Endpoint\IActionFilter;
use DevNet\Security\Tokens\Csrf\AntiForgeryException;
use DevNet\Security\Tokens\Csrf\IAntiForgery;
use Attribute;

#[Attribute]
class ValidateAntiForgery implements IActionFilter
{
    public function __invoke(ActionContext $context, ActionDelegate $next): Task
    {
        $antiForgery = $context->HttpContext->Services->getService(IAntiForgery::class);
        if (!$antiForgery) {
            throw new AntiForgeryException("Unable to get IAntiForgery service, make sure to register it as a service!");
        }

        $method = $context->HttpContext->Request->Method;
        if ($method == 'GET') {
            return next($context);
        }

        $formToken = $context->HttpContext->Request->Form->getValue($antiForgery->options->FieldName);
        $headerToken = $context->HttpContext->Request->Headers->getValues($antiForgery->options->FieldName)[0] ?? null;
        $formResult = $antiForgery->validateToken($formToken);
        $headerResult = $antiForgery->validateToken($headerToken);

        if (!$formResult && !$headerResult) {
            throw new AntiForgeryException("Invalid AntiForgery Token!", 403);
        }

        return $next($context);
    }
}
