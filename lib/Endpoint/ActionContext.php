<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Endpoint;

use DevNet\System\PropertyTrait;
use DevNet\Web\Http\Message\HttpContext;

class ActionContext
{
    use PropertyTrait;

    private ActionDescriptor $actionDescriptor;
    private HttpContext $httpContext;

    public function get_ActionDescriptor(): ActionDescriptor
    {
        return $this->actionDescriptor;
    }

    public function get_HttpContext(): HttpContext
    {
        return $this->httpContext;
    }

    public function __construct(ActionDescriptor $actionDescriptor, HttpContext $httpConnext)
    {
        $this->actionDescriptor = $actionDescriptor;
        $this->httpContext = $httpConnext;
    }
}
