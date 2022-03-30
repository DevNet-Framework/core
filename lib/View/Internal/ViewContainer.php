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
    protected array $items;

    public function set(string $name, $value): void
    {
        $this->items[$name] = $value;
    }

    public function get(string $name)
    {
        return $this->items[$name] ?? null;
    }

    public function has(string $name): bool
    {
        return isset($this->items[$name]) ? true : false;
    }
}
