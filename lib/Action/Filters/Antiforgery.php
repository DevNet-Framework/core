<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Action\Filters;

use DevNet\System\Async\Task;
use DevNet\Web\Action\ActionContext;
use DevNet\Web\Action\ActionDelegate;
use DevNet\Web\Action\IActionFilter;
use DevNet\Web\Security\Tokens\Csrf\AntiforgeryException;
use DevNet\Web\Security\Tokens\Csrf\IAntiforgery;
use Attribute;

#[Attribute]
class Antiforgery implements IActionFilter
{
    public function __invoke(ActionContext $context, ActionDelegate $next): Task
    {
        $antiforgery = $context->HttpContext->RequestServices->getService(IAntiforgery::class);
        if (!$antiforgery) {
            throw new AntiforgeryException("Unable to get IAntiforger service, make sure to register it as a service!");
        }

        $result = $antiforgery->validateTokens($context->HttpContext);

        if (!$result) {
            throw new AntiforgeryException("Invalid Antiforgery Token!");
        }

        return $next($context);
    }
}
