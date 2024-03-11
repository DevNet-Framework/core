<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

 namespace DevNet\Web\Endpoint\Filters;

use DevNet\System\Async\Task;
use DevNet\Web\Endpoint\ActionContext;
use DevNet\Web\Endpoint\ActionDelegate;
use DevNet\Web\Endpoint\IActionFilter;
use DevNet\Web\Security\Tokens\Csrf\AntiForgeryException;
use DevNet\Web\Security\Tokens\Csrf\IAntiForgery;
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

        $result = $antiForgery->validateToken($context->HttpContext);

        if (!$result) {
            throw new AntiForgeryException("Invalid AntiForgery Token!", 403);
        }

        return $next($context);
    }
}
