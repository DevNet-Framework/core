<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Security\Antiforgery;

use DevNet\Core\Http\HttpContext;

interface IAntiforgery
{
    public function storeTokens(HttpContext $httpContext): AntiforgeryTokenSet;

    public function getTokens(HttpContext $httpContext): AntiforgeryTokenSet;

    public function validateTokens(HttpContext $httpContext): bool;
}
