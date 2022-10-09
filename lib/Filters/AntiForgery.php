<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Filters;

use DevNet\Web\Http\HttpContext;
use DevNet\Web\Middleware\IMiddleware;
use DevNet\Web\Middleware\RequestDelegate;
use DevNet\Web\Security\Antiforgery\AntiforgeryException;
use DevNet\Web\Security\Antiforgery\IAntiforgery;
use Attribute;

#[Attribute]
class AntiForgery implements IMiddleware
{
    public function __invoke(HttpContext $context, RequestDelegate $next)
    {
        $antiforgery = $context->RequestServices->getService(IAntiforgery::class);
        if (!$antiforgery) {
            throw new AntiforgeryException("Unable to get IAntiforger service, make sure to register it as a service!");
        }

        $result = $antiforgery->validateTokens($context);

        if (!$result) {
            throw new AntiforgeryException("Invalid AntiForgery Token!");
        }

        return $next($context);
    }
}
