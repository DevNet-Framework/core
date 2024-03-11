<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Middleware;

use DevNet\Web\Http\Message\HttpContext;

interface IRequestHandler
{
    public function __invoke(HttpContext $context);
}
