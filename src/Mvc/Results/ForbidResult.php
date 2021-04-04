<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Mvc\Results;

use DevNet\Web\Mvc\ActionContext;

class ForbidResult extends ActionResult
{
    public function execute(ActionContext $actionContext) : void
    {
        $httpContext = $actionContext->HttpContext;
        $httpContext->response->setStatusCode(403);
    }
}
