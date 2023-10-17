<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http;

use DevNet\System\Collections\Dictionary;
use DevNet\System\Dependency\IServiceProvider;
use DevNet\Web\Security\Claims\ClaimsIdentity;

class HttpContext
{
    public HttpRequest $Request;
    public HttpResponse $Response;
    public ClaimsIdentity $User;
    public Dictionary $Items;
    public IServiceProvider $Services;

    public function __construct(HttpRequest $request, HttpResponse $response)
    {
        $this->Request  = $request;
        $this->Response = $response;
        $this->User     = new ClaimsIdentity(); // anonymous user
        $this->Items    = new Dictionary('string', 'object');
    }
}
