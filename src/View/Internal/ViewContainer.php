<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\View\Internal;

class ViewContainer
{
    private array $Dependencies;

    public function addValue(string $Name, $Value) : void
    {
        $this->Dependencies[$Name] = $Value;
    }

    public function getValue(string $Name)
    {
        return $this->Dependencies[$Name] ?? null;
    }

    public function contains(string $Name) : bool
    {
        return isset($this->Dependencies[$Name]) ? true : false;
    }
}
