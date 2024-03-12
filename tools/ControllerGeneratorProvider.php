<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Tools;

use DevNet\Cli\Templating\CodeGeneratorProvider;

class ControllerGeneratorProvider extends CodeGeneratorProvider
{
    public function __construct()
    {
        parent::__construct('controller', 'Generate a controller class file.', new ControllerGenerator());
    }
}
