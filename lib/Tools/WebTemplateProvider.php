<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Tools;

use DevNet\Cli\Templating\TemplateProvider;

class WebTemplateProvider extends TemplateProvider
{
    public function __construct()
    {
        parent::__construct('web', 'Create a web application project.', __DIR__ . '/../../template');
    }
}
