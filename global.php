<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

use DevNet\Cli\Templating\CodeGeneratorRegistry;
use DevNet\Core\Tools\ControllerGeneratorProvider;

/**
 * DevNet CLI package is not mandatory required by DevNet Web package,
 * so we need to check first if the DevNet CLI is installed before registering the command.
 */
if (class_exists(CodeGeneratorRegistry::class)) {
    CodeGeneratorRegistry::register('controller', ControllerGeneratorProvider::class);
}
