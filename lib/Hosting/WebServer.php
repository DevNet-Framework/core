<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet
 */

namespace DevNet\Web\Hosting;

use DevNet\System\Command\CommandArgument;
use DevNet\System\Command\CommandEventArgs;
use DevNet\System\Command\CommandLine;
use DevNet\System\Command\CommandOption;
use DevNet\System\IO\Console;
use DevNet\System\IO\ConsoleColor;
use DevNet\System\Runtime\LauncherProperties;

class WebServer
{
    public string $Host = 'localhost';
    public int $Port    = 8000;
    public string $Root;
    public CommandLine $Command;

    public function __construct()
    {
        $this->Root = LauncherProperties::getWorkspace() . "/webroot";
        $this->Command = new CommandLine();
        $this->Command->addArgument(new CommandArgument('serve'));
        $this->Command->addOption(new CommandOption('--host'));
        $this->Command->addOption(new CommandOption('--port'));
        $this->Command->addOption(new CommandOption('--root'));
        $this->Command->addOption(new CommandOption('--help', '-h'));
        $this->Command->Handler->add($this, 'serve');
    }

    public function serve(object $sender, CommandEventArgs $args): void
    {
        if (PHP_SAPI != 'cli') {
            return;
        }

        $server = $args->get('serve');

        if (!$server) {
            return;
        }

        if ($server->Value != 'serve' || $args->Residual) {
            Console::foregroundColor(ConsoleColor::Red);
            Console::writeline("The specified argument or option is not valid.");
            Console::resetColor();
            exit;
        }

        $host = $args->get('--host');
        $port = $args->get('--port');
        $root = $args->get('--root');
        $help = $args->get('--help');

        if ($host) {
            if (!$host->Value) {
                Console::foregroundColor(ConsoleColor::Red);
                Console::writeline("The option --host is missing a value.");
                Console::resetColor();
                exit;
            }

            $this->Host = $host->Value;
        }

        if ($port) {
            if (!$port->Value) {
                Console::foregroundColor(ConsoleColor::Red);
                Console::writeline("The option --port is missing a value.");
                Console::resetColor();
                exit;
            }

            $this->Port = $port->Value;
        }

        if ($root) {
            if (!$root->Value) {
                Console::foregroundColor(ConsoleColor::Red);
                Console::writeline("The option --root is missing a value.");
                Console::resetColor();
                exit;
            }

            $this->Root = $root->Value;
        }

        if ($help) {
            if (!$help->Value) {
                Console::writeline("Usage: devnet run serve [options]");
                Console::writeline();
                Console::writeline("Options:");
                Console::writeline("  --help, -h  Show the command-line's help.");
                Console::writeline("  --host      Set the server host name or IP.");
                Console::writeline("  --port      Set the server port.");
                Console::writeline("  --root      Set the web root directory.");
                Console::writeline();
                exit;
            }
        }

        shell_exec("php -S {$this->Host}:{$this->Port} -t {$this->Root}");
    }

    public function start(array $args = [])
    {
        if (!$args) {
            return;
        }
        $this->Command->invoke($args);
    }
}
