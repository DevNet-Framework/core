<?php declare(strict_types = 1);

use Artister\System\Boot\launcher;
use Application\Program;

$autoloadPath   = __DIR__ . "/../vendor/autoload.php";
$runtimePath    = "runtime.json";

if (file_exists($autoloadPath))
{
    require $autoloadPath;
}
else
{
    $cache          = new stdClass();
    $cache->Path    = "path/to/devnet";

    if (file_exists($runtimePath))
    {
        $content    = file_get_contents($runtimePath);
        $cache      = json_decode($content);
    }

    if (!file_exists($cache->Path))
    {
        $cache->Path    = exec("devnet --path");
        $content        = json_encode($cache, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        $file           = fopen($runtimePath, 'w');
        fwrite($file, $content);
        fclose($file);
    }

    require dirname($cache->Path) . "/autoload.php";
}

$launcher = launcher::getLauncher();
$launcher->workspace(dirname(__DIR__));
$launcher->entryPoint(Program::class);
$launcher->launch();
