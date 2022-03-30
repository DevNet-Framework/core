<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http;

use DevNet\System\Collections\Enumerator;
use DevNet\System\Collections\IEnumerable;

class FeatureCollection implements IEnumerable
{
    private array $items;

    public function set(object $feature)
    {
        $this->items[get_class($feature)] = $feature;
    }

    public function get(string $type): ?object
    {
        return $this->items[$type] ?? null;
    }

    public function getIterator(): Enumerator
    {
        return new Enumerator($this->items);
    }
}
