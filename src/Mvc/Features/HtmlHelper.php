<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Mvc\Features;

use Artister\Web\Http\HttpContext;
use Artister\Web\Security\Antiforgery\IAntiforgery;

class HtmlHelper
{
    private HttpContext $HttpContext;

    public function __construct(HttpContext $httpContext)
    {
        $this->HttpContext = $httpContext;
    }

    public function antiForgory() : ?string
    {
        $antiForgery = $this->HttpContext->RequestServices->getService(IAntiforgery::class);
        if (!$antiForgery)
        {
            return null;
        }

        $tokens = $antiForgery->storeTokens($this->HttpContext);
        return "<input type=\"hidden\" name=\"{$tokens->FormFieldName}\" value=\"{$tokens->RequestToken}\">";
    }
}
