<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

use DevNet\Cli\Templating\CodeGeneratorRegistry;
use DevNet\Cli\Templating\TemplateRegistry;
use DevNet\Web\Tools\ControllerGeneratorProvider;
use DevNet\Web\Tools\WebTemplateProvider;

/**
 * DevNet CLI package is not mandatory required by DevNet Web package,
 * so we need to check first if the DevNet CLI is installed before registering the templates.
 */
if (class_exists(TemplateRegistry::class)) {
    TemplateRegistry::register('web', WebTemplateProvider::class);
    CodeGeneratorRegistry::register('controller', ControllerGeneratorProvider::class);
}