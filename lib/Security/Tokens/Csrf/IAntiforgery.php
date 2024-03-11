<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Tokens\Csrf;

use DevNet\Web\Http\Message\HttpContext;

interface IAntiForgery
{
    public function getToken(): AntiForgeryToken;

    public function validateToken(HttpContext $httpContext): bool;
}
