<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Tokens\Csrf;

use DevNet\Web\Http\HttpContext;

interface IAntiforgery
{
    public function getToken(): AntiforgeryToken;

    public function validateToken(HttpContext $httpContext): bool;
}
