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
use DevNet\Web\Http\HttpException;

class HttpMethodFilter implements IActionFilter
{
    private array $Options;

    public function __construct(array $options = [])
    {
        $this->Options = $options;
    }

    public function onActionExecution(ActionContext $context, ActionExecutionDelegate $next)
    {
        $httpContext = $context->HttpContext;
        $httpMethod  = $httpContext->Request->Method;

        foreach ($this->Options as &$option) {
            $option = strtoupper($option);
        }

        if (!in_array($httpMethod, $this->Options)) {
            $httpContext->Response->setStatusCode(405);
            throw new HttpException("\"{$httpMethod}\" Method Not Allowed", 405);
        }

        return $next($context);
    }
}
