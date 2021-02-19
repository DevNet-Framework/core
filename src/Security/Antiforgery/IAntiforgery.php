<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Security\Antiforgery;

use Artister\Web\Http\HttpContext;

interface IAntiforgery
{
    public function storeTokens(HttpContext $httpContext) : AntiforgeryTokenSet;

    public function getTokens(HttpContext $httpContext) : AntiforgeryTokenSet;

    public function validateTokens(HttpContext $httpContext) : bool;
}