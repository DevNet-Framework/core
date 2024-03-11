<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

 namespace DevNet\Web\Security\Tokens\Csrf;

use DevNet\System\Async\Task;
use DevNet\Web\Endpoint\ActionContext;
use DevNet\Web\Endpoint\ActionDelegate;
use DevNet\Web\Endpoint\IActionFilter;
use DevNet\Web\Security\Tokens\Csrf\AntiforgeryException;
use DevNet\Web\Security\Tokens\Csrf\IAntiforgery;
use Attribute;

#[Attribute]
class ValidateAntiForgery implements IActionFilter
{
    public function __invoke(ActionContext $context, ActionDelegate $next): Task
    {
        $antiforgery = $context->HttpContext->Services->getService(IAntiforgery::class);
        if (!$antiforgery) {
            throw new AntiforgeryException("Unable to get IAntiforgery service, make sure to register it as a service!");
        }

        $result = $antiforgery->validateToken($context->HttpContext);

        if (!$result) {
            throw new AntiforgeryException("Invalid Antiforgery Token!", 403);
        }

        return $next($context);
    }
}
