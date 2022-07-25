<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http;

use DevNet\Web\Middleware\IMiddleware;
use DevNet\Web\Middleware\RequestDelegate;

class HttpMethodFilter implements IMiddleware
{
    private array $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function __invoke(HttpContext $context, RequestDelegate $next)
    {
        $httpMethod  = $context->Request->Method;

        foreach ($this->options as &$option) {
            $option = strtoupper($option);
        }

        if (!in_array($httpMethod, $this->options)) {
            $context->Response->setStatusCode(405);
            throw new HttpException("\"{$httpMethod}\" Method Not Allowed", 405);
        }

        return $next($context);
    }
}
