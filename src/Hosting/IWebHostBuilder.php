<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Hosting;

use Closure;

interface IWebHostBuilder
{
    public function configureServices(Closure $configureServices);

    public function configureApplication(Closure $configureApp);

    public function build(): WebHost;
}
