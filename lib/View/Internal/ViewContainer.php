<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\View\Internal;

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
