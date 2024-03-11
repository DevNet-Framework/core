<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Tokens\Csrf;

use DevNet\System\Runtime\LauncherProperties;
use DevNet\Web\Http\Message\CookieOptions;

class AntiForgeryOptions
{
    public CookieOptions $Cookie;
    public string $CookieName = "AntiForgery";
    public string $FieldName  = "X-CSRF-TOKEN";
    public string $HeaderName = "X-XSRF-TOKEN";

    public function __construct()
    {
        $this->Cookie     = new CookieOptions();
        $this->CookieName = $this->CookieName . "-" . md5($this->CookieName . LauncherProperties::getRootDirectory());;
    }
}
