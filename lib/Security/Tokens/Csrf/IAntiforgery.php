<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Tokens\Csrf;

use DevNet\Web\Http\HttpContext;

interface IAntiforgery
{
    public function getToken(): AntiforgeryToken;

    public function validateToken(HttpContext $httpContext): bool;
}
