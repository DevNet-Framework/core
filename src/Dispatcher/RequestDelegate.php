<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Dispatcher;

use Artister\Web\Http\HttpContext;
use Artister\System\Event\Delegate;
use Artister\System\Process\Task;

class RequestDelegate extends Delegate
{
    /** RequestDelegate signature */
    public function delegate(HttpContext $context) : Task {}
}