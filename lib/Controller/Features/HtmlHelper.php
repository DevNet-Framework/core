<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Controller\Features;

use DevNet\Core\Http\HttpContext;
use DevNet\Core\Security\Antiforgery\IAntiforgery;

class HtmlHelper
{
    private HttpContext $HttpContext;

    public function __construct(HttpContext $httpContext)
    {
        $this->HttpContext = $httpContext;
    }

    public function antiForgeryToken(): ?string
    {
        $antiForgery = $this->HttpContext->RequestServices->getService(IAntiforgery::class);
        if (!$antiForgery) {
            return null;
        }

        $tokens = $antiForgery->storeTokens($this->HttpContext);
        return "<input type=\"hidden\" name=\"{$tokens->FormFieldName}\" value=\"{$tokens->RequestToken}\">";
    }
}
