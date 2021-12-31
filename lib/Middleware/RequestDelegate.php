<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Middleware;

use DevNet\Core\Http\HttpContext;
use DevNet\System\Delegate;

class RequestDelegate extends Delegate
{
    /** RequestDelegate signature */
    public function delegate(HttpContext $context)
    {
    }
}
