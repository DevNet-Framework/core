<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Filters;

use DevNet\Web\Http\HttpContext;
use DevNet\Web\Http\HttpException;
use DevNet\Web\Middleware\IMiddleware;
use DevNet\Web\Middleware\RequestDelegate;
use Attribute;

#[Attribute]
class HttpMethod implements IMiddleware
{
    private array $verbes;

    public function __construct(string ...$verbes)
    {
        $this->verbes = $verbes;
    }

    public function __invoke(HttpContext $context, RequestDelegate $next)
    {
        $allwoed = false;
        $httpMethod = $context->Request->Method;
        foreach ($this->verbes as $verbe) {
            if ($httpMethod == strtoupper($verbe)) {
                $allwoed = true;
                break;
            }
        }

        if (!$allwoed) {
            $context->Response->setStatusCode(405);
            throw new HttpException("\"{$httpMethod}\" Method Not Allowed", 405);
        }

        return $next($context);
    }
}
