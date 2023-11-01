<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Hosting;

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
        if ($this->EnvironmentName == 'Production') {
            return true;
        }

        return false;
    }

    public function IsDevelopment(): bool
    {
        if ($this->EnvironmentName == 'Development') {
            return true;
        }

        return false;
    }
}
