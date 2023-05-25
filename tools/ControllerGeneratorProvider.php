<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Tools;

use DevNet\Cli\Templating\CodeGeneratorProvider;

class ControllerGeneratorProvider extends CodeGeneratorProvider
{
    public function __construct()
    {
        parent::__construct('controller', 'Generate a controller class file.', new ControllerGenerator());
    }
}
