<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Endpoint;

use DevNet\Http\Message\HttpContext;

class ActionContext
{
    private ActionDescriptor $actionDescriptor;
    private HttpContext $httpContext;

    public ActionDescriptor $ActionDescriptor { get => $this->actionDescriptor; }
    public HttpContext $HttpContext { get => $this->httpContext; }

    public function __construct(ActionDescriptor $actionDescriptor, HttpContext $httpConnext)
    {
        $this->actionDescriptor = $actionDescriptor;
        $this->httpContext = $httpConnext;
    }
}
