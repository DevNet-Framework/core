<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Action\Features;

use DevNet\Web\Http\HttpContext;
use DevNet\Web\Security\Tokens\Csrf\IAntiforgery;

class HtmlHelper
{
    private HttpContext $httpContext;

    public function __construct(HttpContext $httpContext)
    {
        $this->httpContext = $httpContext;
    }

    public function AntiforgeryToken(): ?string
    {
        $antiforgery = $this->httpContext->RequestServices->getService(IAntiforgery::class);
        if (!$antiforgery) {
            return null;
        }

        $tokens = $antiforgery->storeTokens($this->httpContext);
        return "<input type=\"hidden\" name=\"{$tokens->FormFieldName}\" value=\"{$tokens->RequestToken}\">";
    }
}
