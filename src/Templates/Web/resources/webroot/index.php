<?php declare(strict_types = 1);

use Artister\System\Boot\launcher;
use Application\Program;

$autoloadPath = __DIR__ . "/../vendor/autoload.php";
$projectPath  = "../project.phproj";

if (file_exists($autoloadPath))
{
    require $autoloadPath;
}
else
{
    $project = new SimpleXMLElement("<project></project>");

    if (file_exists($projectPath))
    {
        $project = simplexml_load_file($projectPath);
    }

    if (!file_exists((string)$project->autoload->path."/autoload.php"))
    {
        $project->autoload->path = dirname(exec("devnet --path"));
        $dom                     = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput       = true;
        
        $dom->loadXML($project->asXML());
        $dom->save($projectPath);
    }

    require (string)$project->autoload->path."/autoload.php";
}

$launcher = launcher::getLauncher();
$launcher->workspace(dirname(__DIR__));
$launcher->entryPoint(Program::class);
$launcher->launch();
