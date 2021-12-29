<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Mvc\Filters;

use DevNet\Core\Mvc\IActionFilter;
use DevNet\Core\Mvc\ActionExecutionDelegate;
use DevNet\Core\Mvc\ActionContext;
use DevNet\Core\Security\Antiforgery\IAntiforgery;
use DevNet\Core\Security\Antiforgery\AntiforgeryException;

class AntiForgeryFilter implements IActionFilter
{
    private array $Options;

    public function __construct(array $options = [])
    {
        $this->Options = $options;
    }

    public function onActionExecution(ActionContext $context, ActionExecutionDelegate $next)
    {
        $httpContext = $context->HttpContext;
        $antiforgery = $httpContext->RequestServices->getService(IAntiforgery::class);

        $result = $antiforgery->validateTokens($httpContext);

        if (!$result) {
            throw new AntiforgeryException("Invalid AntiForgery Token.");
        }

        return $next($context);
    }
}
