<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\View\Internal;

class ViewContainer
{
    protected array $Items;

    public function set(string $Name, $Value): void
    {
        $this->Items[$Name] = $Value;
    }

    public function get(string $Name)
    {
        return $this->Items[$Name] ?? null;
    }

    public function has(string $Name): bool
    {
        return isset($this->Items[$Name]) ? true : false;
    }
}
