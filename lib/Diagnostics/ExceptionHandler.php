<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Diagnostics;

use DevNet\Core\View\ViewManager;
use Throwable;

class ExceptionHandler
{
    private ViewManager $view;

    public function __construct()
    {
        $this->view = new ViewManager(__DIR__);
    }

    public function handle(Throwable $error): string
    {
        $data = $this->parse($error);
        return $this->view->render('ExceptionView', $data);
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

        $firstFile = $trace[0]['file'] ?? null;

        if ($error->getFile() == $firstFile) {
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
