<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Exception;

use DevNet\Web\Http\HttpContext;
use DevNet\Web\Middleware\IMiddleware;
use DevNet\Web\Middleware\RequestDelegate;
use DevNet\Web\View\ViewManager;
use DevNet\System\Async\Tasks\Task;
use Throwable;

class ExceptionMiddleware implements IMiddleware
{
    private ?string $errorHandlingPath;

    public function __construct(?string $errorHandlingPath = null)
    {
        $this->errorHandlingPath = $errorHandlingPath;
    }

    public function __invoke(HttpContext $context, RequestDelegate $next)
    {
        if ($context->getAttribute('Error')) {
            if ($this->errorHandlingPath) {
                $context->Request->Uri->Path = $this->errorHandlingPath;
                $context->Response->Body->truncate(0);
                return yield $next($context);
            }
            return yield $this->handel($context);
        }
        
        try {
            // need to await the async RequestDelegate here to be able to catch the error exception
            yield $next($context);
        } catch (Throwable $error) {
            if (PHP_SAPI == 'cli') {
                throw new $error;
            }
            $context->addAttribute('Error', $error);
            if ($this->errorHandlingPath) {
                $context->Request->Uri->Path = $this->errorHandlingPath;
                $context->Response->Body->truncate(0);
                return yield $next($context);
            }
            return yield $this->handel($context);
        }
    }

    public function handel(HttpContext $context)
    {
        $error = $context->Error;
        $data  = $this->parse($error);
        $view  = new ViewManager(__DIR__ . '/Views');

        $view->setData($data);
        $context->Response->Body->write($view->render('ExceptionView'));
        return Task::completedTask();
    }

    public function parse(Throwable $error): array
    {
        $severities = [
            E_ERROR             => 'Fatal Error',
            E_WARNING           => 'Warning',
            E_PARSE             => 'Parse Error',
            E_NOTICE            => 'Notice',
            E_CORE_ERROR        => 'Core Error',
            E_CORE_WARNING      => 'Core Warning',
            E_COMPILE_ERROR     => 'Compile Error',
            E_COMPILE_WARNING   => 'Compile Warning',
            E_USER_ERROR        => 'User Error',
            E_USER_WARNING      => 'User Warning',
            E_USER_NOTICE       => 'User Notice',
            E_STRICT            => 'Strict Error',
            E_RECOVERABLE_ERROR => 'Recoverable Error',
            E_DEPRECATED        => 'Deprecated',
            E_USER_DEPRECATED   => 'User Deprecated'
        ];

        $trace = $error->getTrace();
        if ($error instanceof \ErrorException) {
            $severity = $severities[$error->getSeverity()];
        } else {
            $severity = $severities[E_ERROR];
        }

        $firstfile = $trace[0]['file'] ?? null;

        if ($error->getFile() == $firstfile) {
            array_shift($trace);
        }

        if ($error->getCode() == 0) {
            $code = '';
        } else {
            $code = $error->getCode();
        }

        $data['error']   = $severity;
        $data['message'] = $error->getMessage();
        $data['class']   = get_class($error);
        $data['code']    = $code;
        $data['file']    = $error->getFile();
        $data['line']    = $error->getLine();
        $data['trace']   = $trace;

        return $data;
    }
}
