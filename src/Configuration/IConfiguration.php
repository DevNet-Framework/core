<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Configuration;

interface IConfiguration
{
    public function getValue(string $key);
    
    public function getSection(string $key) : IConfiguration;

    public function getChildren() : array;
}