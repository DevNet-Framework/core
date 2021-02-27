<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Templates\Web;

use Artister\System\Command\Parser\CommandParser;
use Artister\System\StringBuilder;
use Artister\System\ConsoleColor;
use Artister\System\Console;

class Program
{
    public static function main(array $args = [])
    {
        $rootPath   = getcwd();
        $className  = "Program";
        $basePath   = null;
        $namespace  = "Application";

        $parser = new CommandParser();
        $parser->addOption('--main');
        $parser->addOption('--project');
        $arguments = $parser->parse($args);

        $nameOption = $arguments->getOption('--main');
        if ($nameOption)
        {
            $className = $nameOption->Value;
        }

        if (!$className)
        {
            Console::foregroundColor(ConsoleColor::Red);
            Console::writeline("class Name not found, maybe forget to enter class name using the option --main");
            Console::resetColor();
            exit;
        }

        $projectOption = $arguments->getOption('--project');
        if ($projectOption)
        {
            $basePath = $projectOption->Value;
        }

        $path = implode("/", [$rootPath, $basePath]);

        if (!is_dir($path))
        {
            mkdir($path, 0777, true);
        }

        $result = self::createProgram($path, $namespace, $className);

        if ($result)
        {
            self::copyFile( __DIR__.'/resources/project.phproj', $path."/project.phproj");
            self::copyFile( __DIR__.'/resources/composer.json', $path."/composer.json");
            self::copyFile( __DIR__.'/resources/Startup.php', $path."/Startup.php");
            self::copyFile( __DIR__.'/resources/Routes.php', $path."/Routes.php");
            self::copyFile( __DIR__.'/resources/settings.json', $path."/settings.json");
            self::copyFile( __DIR__.'/resources/Controllers/HomeController.php', $path."/Controllers/HomeController.php");
            self::copyFile( __DIR__.'/resources/Controllers/AccountController.php', $path."/Controllers/AccountController.php");
            self::copyFile( __DIR__.'/resources/Models/LoginForm.php', $path."/Models/LoginForm.php");
            self::copyFile( __DIR__.'/resources/Views/Home/index.phtml', $path."/Views/home/index.phtml");
            self::copyFile( __DIR__.'/resources/Views/Account/index.phtml', $path."/Views/Account/index.phtml");
            self::copyFile( __DIR__.'/resources/Views/Account/login.phtml', $path."/Views/Account/login.phtml");
            self::copyFile( __DIR__.'/resources/Views/Account/register.phtml', $path."/Views/Account/register.phtml");
            self::copyFile( __DIR__.'/resources/Views/Layouts/layout.phtml', $path."/Views/Layouts/layout.phtml");
            self::copyFile( __DIR__.'/resources/Views/Layouts/navbar.phtml', $path."/Views/Layouts/navbar.phtml");
            self::copyFile( __DIR__.'/resources/webroot/css/style.css', $path."/webroot/css/style.css");
            self::copyFile( __DIR__.'/resources/webroot/lib/bootstrap/css/bootstrap.min.css', $path."/webroot/lib/bootstrap/css/bootstrap.min.css");
            self::copyFile( __DIR__.'/resources/webroot/js/script.js', $path."/webroot/js/script.js");
            self::copyFile( __DIR__.'/resources/webroot/lib/bootstrap/js/bootstrap.bundle.min.js', $path."/webroot/lib/bootstrap/js/bootstrap.bundle.min.js");
            self::copyFile( __DIR__.'/resources/webroot/index.php', $path."/webroot/index.php");
            self::copyFile( __DIR__.'/resources/webroot/web.config', $path."/webroot/web.config");
            self::copyFile( __DIR__.'/resources/webroot/.htaccess', $path."/webroot/.htaccess");

            Console::foregroundColor(ConsoleColor::Green);
            Console::writeline("The template 'Web Application' was created successfully.");
            Console::resetColor();
        }

    }

    public static function createProgram($path, $namespace, $className) : bool
    {
        $namespace = ucwords($namespace, "\\");
        $className = ucfirst($className);

        $context = new StringBuilder();
        $context->appendLine("<?php");
        $context->appendLine();
        $context->appendLine("namespace {$namespace};");
        $context->appendLine();
        $context->appendLine("use Artister\Web\Hosting\WebHost;");
        $context->appendLine("use Artister\Web\Hosting\IWebHostBuilder;");
        $context->appendLine();
        $context->appendLine("class {$className}");
        $context->appendLine("{");
        $context->appendLine("    public static function main(array \$args = [])");
        $context->appendLine("    {");
        $context->appendLine("        self::createWebHostBuilder(\$args)->build()->run();");
        $context->appendLine("    }");
        $context->appendLine();
        $context->appendLine("    public static function createWebHostBuilder(array \$args) : IWebHostBuilder");
        $context->appendLine("    {");
        $context->appendLine("        return WebHost::createBuilder(\$args)");
        $context->appendLine("            ->useStartup(Startup::class);");
        $context->appendLine("    }");
        $context->append("}");

        $myfile = fopen($path."/".$className.".php", "w");
        $size   = fwrite($myfile, $context->__toString());
        $status = fclose($myfile);

        if ($size && $status)
        {
            return true;
        }

        return false;
    }

    public static function copyFile($srcfile, $dstfile)
    {
        $dstdir =  dirname($dstfile);
        if (!is_dir($dstdir))
        {
            mkdir($dstdir, 0777, true);
        }

        if (!file_exists($dstfile))
        {
            copy($srcfile, $dstfile);
        }
    }
}
