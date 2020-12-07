<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Hosting;

use Artister\System\Runtime\Boot\LauncherProperties;

class WebServer
{
    private string $Directoty;
    private string $Host = 'localhost';
    private int $Port  = 8000;

    public function __construct()
    {
        $this->Directoty  = LauncherProperties::getWorkspace()."/webroot";
    }

    public function setDirectoty(string $directoty)
    {
        $this->Directoty = $directoty;
    }

    public function setHost(string $host)
    {
        $this->Host = $host;
    }

    public function setPort(int $port)
    {
        $this->Port = $port;
    }

    public function start()
    {
        if (PHP_SAPI == 'cli')
        {            
            shell_exec("php -S {$this->Host}:{$this->Port} -t {$this->Directoty}");
            exit;
        }
    }
}