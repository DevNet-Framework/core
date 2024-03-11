<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authentication;

use DevNet\Web\Http\Message\HttpContext;
use DevNet\Web\Http\Middleware\IMiddleware;
use DevNet\Web\Http\Middleware\RequestDelegate;
use DevNet\Web\Security\Authentication\IAuthentication;

class AuthenticationMiddleware implements IMiddleware
{
    public function __invoke(HttpContext $context, RequestDelegate $next)
    {
        if ($context->Services->contains(IAuthentication::class)) {
            $authentication = $context->Services->getService(IAuthentication::class);
            foreach ($authentication->Schemes as $scheme) {
                $result = $authentication->authenticate($scheme);
                if ($result->isSucceeded()) {
                    $context->User = $result->Identity;
                    break;
                }
            }
        }

        return $next($context);
    }
}
