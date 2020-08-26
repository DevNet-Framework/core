<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Mvc\Filters;

use Artister\DevNet\Http\HttpContext;
use Artister\DevNet\Mvc\IActionFilter;
use Artister\DevNet\Mvc\ActionExecutionDelegate;
use Artister\DevNet\Mvc\ActionContext;
use Artister\System\Process\Task;

class HttpMethodFilter implements IActionFilter
{
    private array $Options;

    public function __construct(array $options = [])
    {
        $this->Options = $options;
    }
    
    public function onActionExecutionAsync(ActionContext $context, ActionExecutionDelegate $next) : Task
    {
        $httpContext = $context->ServiceProvider->getService(HttpContext::class);
        $httpMethod = $httpContext->Request->Method;

        if (in_array($httpMethod, $this->Options))
        {
            return $next($context);
        }

        $httpContext->Response->setStatusCode(405);

        return Task::completedTask();
    }
}