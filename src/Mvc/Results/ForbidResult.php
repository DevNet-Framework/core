<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Mvc\Results;

use Artister\Web\Mvc\ActionContext;

class ForbidResult extends ActionResult
{
    public function execute(ActionContext $actionContext) : void
    {
        $httpContext = $actionContext->HttpContext;

        //$httpContext->response->headers->add("Location", $url);
        $httpContext->response->setStatusCode(403);
    }
}