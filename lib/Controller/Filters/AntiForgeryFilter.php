<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Controller\Filters;

use DevNet\Web\Controller\IActionFilter;
use DevNet\Web\Controller\ActionExecutionDelegate;
use DevNet\Web\Controller\ActionContext;
use DevNet\Web\Security\Antiforgery\IAntiforgery;
use DevNet\Web\Security\Antiforgery\AntiforgeryException;

class AntiForgeryFilter implements IActionFilter
{
    private array $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;
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
