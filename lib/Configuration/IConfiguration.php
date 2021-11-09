<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Configuration;

interface IConfiguration
{
    public function getValue(string $key);

    public function getSection(string $key): IConfiguration;

    public function getChildren(): array;
}
