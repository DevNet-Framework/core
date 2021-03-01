<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Templates\Controller;

use Artister\System\Command\Parser\CommandParser;
use Artister\System\StringBuilder;
use Artister\System\ConsoleColor;
use Artister\System\Console;

class Program
{
    public static function main(array $args = [])
    {
        $rootPath = getcwd();
        $className  = null;
        $basePath   = "Controllers";
        $namespace  = "Application";

        $parser = new CommandParser();
        $parser->addParameter('name');
        $parser->addOption('--directory');
        $arguments = $parser->parse($args);

        $controllerParameter = $arguments->getParameter('name');

        if ($controllerParameter) {
            $className = $controllerParameter->Value;
        }

        if (!$className) {
            Console::foregroundColor(ConsoleColor::Red);
            Console::writeline("Controller name not found, maybe forget to enter the controller name argument");
            Console::writeline("Try \"new --help\" for more information.");
            Console::resetColor();
            exit;
        }

        $directoryOption = $arguments->getOption('--directory');
        if ($directoryOption) {
            $basePath = $directoryOption->Value;
        }

        $path = implode("/", [$rootPath, $basePath]);
        $namespace = implode("\\", [$namespace, $basePath]);

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $result = self::createClass($path, $namespace, $className);

        if ($result) {
            Console::foregroundColor(ConsoleColor::Green);
            Console::writeline("$className.php has been created in $path");
            Console::resetColor();
        }
    }

    public static function createClass($path, $namespace, $className) : bool
    {
        $namespace  = ucwords($namespace, "\\");
        $className  = ucfirst($className);

        $context = new StringBuilder();
        $context->appendLine("<?php");
        $context->appendLine();
        $context->appendLine("namespace {$namespace};");
        $context->appendLine();
        $context->appendLine("use Artister\Web\Mvc\Controller;");
        $context->appendLine("use Artister\Web\Mvc\IActionResult;");
        $context->appendLine();
        $context->appendLine("class {$className} extends Controller");
        $context->appendLine("{");
        $context->appendLine("    public function index() : IActionResult");
        $context->appendLine("    {");
        $context->appendLine("        return \$this->view();");
        $context->appendLine("    }");
        $context->append("}");

        $myfile     = fopen($path."/".$className.".php", "w");
        $size       = fwrite($myfile, $context->__toString());
        $status     = fclose($myfile);

        if ($size && $status) {
            return true;
        }

        return false;
    }
}
