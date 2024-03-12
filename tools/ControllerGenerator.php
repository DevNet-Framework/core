<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Tools;

use DevNet\Cli\Templating\CodeModel;
use DevNet\Cli\Templating\ICodeGenerator;
use DevNet\System\Text\StringBuilder;

class ControllerGenerator implements ICodeGenerator
{
    private StringBuilder $content;

    public function __construct()
    {
        $this->content = new StringBuilder();
    }

    public function generate(array $parameters): array
    {
        $name      = $parameters['--name'] ?? 'MyController';
        $output    = $parameters['--output'] ?? 'Controllers';
        $namespace = $parameters['--prefix'] ?? 'Application';
        $namespace = $namespace .'\\' . str_replace('/', '\\', $output);
        $namespace = trim($namespace, '\\');

        $this->content->clear();
        $this->content->appendLine('<?php');
        $this->content->appendLine();
        $this->content->appendLine("namespace {$namespace};");
        $this->content->appendLine();
        $this->content->appendLine('use DevNet\Core\Endpoint\Controller;');
        $this->content->appendLine('use DevNet\Core\Endpoint\IActionResult;');
        $this->content->appendLine('use DevNet\Core\Endpoint\Route;');
        $this->content->appendLine();
        $this->content->appendLine("class {$name} extends Controller");
        $this->content->appendLine('{');
        $this->content->appendLine("    #[Route('/')]");
        $this->content->appendLine('    public function index(): IActionResult');
        $this->content->appendLine('    {');
        $this->content->appendLine('        return $this->content("Hello World!");');
        $this->content->appendLine('    }');
        $this->content->appendLine('}');

        return [new CodeModel($name . '.php', $this->content, $output)];
    }
}
