<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet
 */

namespace DevNet\Core\Hosting;

use DevNet\System\Command\CommandEventArgs;
use DevNet\System\Command\CommandLine;
use DevNet\System\Runtime\LauncherProperties;
use DevNet\System\IO\ConsoleColor;
use DevNet\System\IO\Console;

class WebServer
{
    private string $host = 'localhost';
    private int $port = 8000;
    private string $root;
    private CommandLine $command;

    public function __construct()
    {
        $this->root = LauncherProperties::getRootDirectory() . "/webroot";
        $this->command = new CommandLine('run', 'Run the web server');
        $this->command->addOption('--host', 'the server host name or IP');
        $this->command->addOption('--port', 'The server port');
        $this->command->addOption('--root', 'The web root directory');
        $this->command->setHandler([$this, 'serve']);
    }

    public function serve(object $sender, CommandEventArgs $args): void
    {
        if (PHP_SAPI != 'cli') {
            return;
        }

        $host = $args->getParameter('--host');
        $port = $args->getParameter('--port');
        $root = $args->getParameter('--root');

        if ($host) {
            if (!$host->getValue()) {
                Console::$ForegroundColor = ConsoleColor::Red;
                Console::writeLine("The option --host is missing a value.");
                Console::resetColor();
                return;
            }

            $this->host = $host->getValue();
        }

        if ($port) {
            if (!$port->getValue()) {
                Console::$ForegroundColor = ConsoleColor::Red;
                Console::writeLine("The option --port is missing a value.");
                Console::resetColor();
                return;
            }

            $this->port = $port->getValue();
        }

        if ($root) {
            if (!$root->getValue()) {
                Console::$ForegroundColor = ConsoleColor::Red;
                Console::writeLine("The option --root is missing a value.");
                Console::resetColor();
                return;
            }

            $this->root = $root->getValue();
        }

        shell_exec("php -S {$this->host}:{$this->port} -t {$this->root}");
    }

    public function start(array $args = [])
    {
        $this->command->invoke($args);
    }
}
