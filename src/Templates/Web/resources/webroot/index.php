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

    if (!file_exists((string)$project->runtime->path))
    {
        $project->runtime->path = exec("devnet --path");
        
        $dom = new DOMDocument();
        $dom->formatOutput = true;
        $dom->loadXML($project->asXML());
        $dom->save($projectPath);
    }

    require dirname((string)$project->runtime->path) . "/autoload.php";
}

$launcher = launcher::getLauncher();
$launcher->workspace(dirname(__DIR__));
$launcher->entryPoint(Program::class);
$launcher->launch();
