<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Middlewares;

use Artister\DevNet\Dispatcher\IMiddleware;
use Artister\DevNet\Dispatcher\RequestDelegate;
use Artister\DevNet\Http\HttpContext;
use Artister\System\Diagnostic\Debuger;
use Artister\System\Process\Task;

class ExceptionMiddleware implements IMiddleware
{
    private ?string $ErrorHandlingPath;

    public function __construct(?string $errorHandlingPath = '')
    {
        $this->ErrorHandlingPath = $errorHandlingPath;
    }

    public function __invoke(HttpContext $context, RequestDelegate $next) : Task
    {
        $debug = new Debuger();
        $debug->disable();

        if ($this->ErrorHandlingPath ==='')
        {
            $debug->enable();
            return $next($context);
        }
        else
        {
            try
            {
                return $next($context);
            }
            catch (\Throwable $error)
            {
                $context->addAttribute('Error', $error);
                $context->Request->Uri->Path = $this->ErrorHandlingPath;
                return $next($context);
            }
        }
    }
}