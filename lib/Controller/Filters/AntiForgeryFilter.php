<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Controller\Filters;

use DevNet\Web\Http\HttpContext;
use DevNet\Web\Middleware\IMiddleware;
use DevNet\Web\Middleware\RequestDelegate;
use DevNet\Web\Security\Antiforgery\IAntiforgery;
use DevNet\Web\Security\Antiforgery\AntiforgeryException;

class AntiForgeryFilter implements IMiddleware
{
    private array $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function __invoke(HttpContext $context, RequestDelegate $next)
    {
        $antiforgery = $context->RequestServices->getService(IAntiforgery::class);

        $result = $antiforgery->validateTokens($context);

        if (!$result) {
            throw new AntiforgeryException("Invalid AntiForgery Token.");
        }

        return $next($context);
    }
}
