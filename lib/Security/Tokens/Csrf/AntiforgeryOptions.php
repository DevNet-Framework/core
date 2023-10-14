<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Tokens\Csrf;

use DevNet\System\Runtime\LauncherProperties;
use DevNet\Web\Http\CookieOptions;

class AntiforgeryOptions
{
    public CookieOptions $Cookie;
    public string $CookieName = "Antiforgery";
    public string $FieldName  = "X-CSRF-TOKEN";
    public string $HeaderName = "X-XSRF-TOKEN";

    public function __construct()
    {
        $this->Cookie     = new CookieOptions();
        $this->CookieName = $this->CookieName . "-" . md5($this->CookieName . LauncherProperties::getRootDirectory());;
    }
}
