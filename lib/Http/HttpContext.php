<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http;

use DevNet\System\Collections\Dictionary;
use DevNet\Common\Dependency\IServiceProvider;
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
