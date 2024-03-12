<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Hosting;

use DevNet\System\PropertyTrait;

class WebHostEnvironment
{
    use PropertyTrait;

    public string $ApplicationName = 'Application';
    public string $EnvironmentName = 'Production';
    public string $WebRootPath = '/webroot';

    public function __construct()
    {
        $environmentName = getenv('DEVNET_ENVIRONMENT');
        if ($environmentName) {
            $this->EnvironmentName = $environmentName;
        }
    }

    public function isProduction(): bool
    {
        if (strtolower($this->EnvironmentName) == 'production') {
            return true;
        }

        return false;
    }

    public function IsDevelopment(): bool
    {
        if (strtolower($this->EnvironmentName) == 'development') {
            return true;
        }

        return false;
    }
}
