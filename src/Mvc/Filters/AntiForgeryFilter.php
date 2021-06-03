<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Mvc\Filters;

use DevNet\System\Async\Task;
use DevNet\Web\Mvc\IActionFilter;
use DevNet\Web\Mvc\ActionExecutionDelegate;
use DevNet\Web\Mvc\ActionContext;
use DevNet\Web\Security\Antiforgery\IAntiforgery;
use DevNet\Web\Security\Antiforgery\AntiforgeryException;

class AntiForgeryFilter implements IActionFilter
{
    private array $Options;

    public function __construct(array $options = [])
    {
        $this->Options = $options;
    }
    
    public function onActionExecutionAsync(ActionContext $context, ActionExecutionDelegate $next) : Task
    {
        $httpContext = $context->HttpContext;
        $antiforgery = $httpContext->RequestServices->getService(IAntiforgery::class);

        $result = $antiforgery->validateTokens($httpContext);

        if (!$result)
        {
            throw new AntiforgeryException("Invalid AntiForgery Token.");
        }

        return $next($context);
    }
}
