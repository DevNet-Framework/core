<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Tokens\Csrf;

class AntiForgeryOptions
{
    public string $CookieName = "AntiForgery";
    public string $CookiePath = "/";
    public string $FieldName  = "X-CSRF-TOKEN";
    public string $HeaderName = "X-XSRF-TOKEN";

    public function __construct()
    {
        $this->CookieName = $this->CookieName . "-" . md5($this->CookieName . $_SERVER['DOCUMENT_ROOT']);;
    }
}
