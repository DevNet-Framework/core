<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Mvc\Filters;

use Artister\Web\Http\HttpContext;
use Artister\Web\Mvc\IActionFilter;
use Artister\Web\Mvc\ActionExecutionDelegate;
use Artister\Web\Mvc\ActionContext;
use Artister\System\Async\Task;
use Artister\Web\Security\Antiforgery\IAntiforgery;

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
            throw new \Exception("AntiForgery");
        }

        return $next($context);
    }
}