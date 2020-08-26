<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Entity;

interface IEntity
{
    /**
     * @return mixed return the the field value
     * @throws OutOfRangeException Undefined property: className::$propertyName
     * throwing OutOfRangeException is optionnel.
     */
    public function __get(string $name);
}