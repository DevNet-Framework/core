<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Tokens\Csrf;

class AntiForgeryTokenSet
{
    public ?string $CookieToken;
    public ?string $RequestToken;
    public ?string $FormFieldName;

    public function __construct(string $cookieToken = null, string $requestToken = null, string $formFieldName = null)
    {
        $this->CookieToken = $cookieToken;
        $this->RequestToken = $requestToken;
        $this->FormFieldName = $formFieldName;
    }
}
