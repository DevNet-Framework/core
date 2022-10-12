<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

use DevNet\Cli\Templating\TemplateRegistry;
use DevNet\Web\Tools\WebTemplateProvider;

TemplateRegistry::register('web', WebTemplateProvider::class);