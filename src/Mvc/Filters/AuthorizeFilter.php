<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Mvc\Filters;

use Artister\DevNet\Mvc\IActionFilter;
use Artister\DevNet\Mvc\ActionContext;
use Artister\DevNet\Mvc\ActionExecutionDelegate;
use Artister\System\Process\Task;

class AuthorizeFilter implements IActionFilter
{
    private array $Options;

    public function __construct(array $options = [])
    {
        $this->Options = $options;
    }

    public function onActionExecutionAsync(ActionContext $context, ActionExecutionDelegate $next) : Task
    {
        $httpContext    = $context->HttpContext;
        $authorization  = $context->HttpContext->Authorization;

        if ($authorization)
        {
            $user           = $httpContext->User;
            $policy         = $this->Options['Policy'] ?? 'Authentication';
            $result         = $authorization->Authorize($policy, $user);

            if (!$result->isSucceeded())
            {
                if ($policy == 'Authentication')
                {
                    $authentication = $httpContext->getAttribute('Authentication');
                    $loginPath      = "/account/login";

                    /* if ($authentication) {
                        $loginPath  = $authentication->Options->LoginPath;
                    } */

                $httpContext->Response->redirect($loginPath);
                }
                else
                {
                    $httpContext->Response->setStatusCode(403);
                }
                
                return Task::completedTask();
            }
        }

        return $next($context);
    }
}