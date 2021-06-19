<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Http;

use DevNet\System\Collections\Enumerator;
use DevNet\System\Collections\IEnumerable;
use DevNet\System\Type;
use stdClass;

class FeatureCollection implements IEnumerable
{
    private array $Items;

    public function set(object $feature)
    {
        $this->Items[get_class($feature)] = $feature;
    }

    public function get(string $type) : ?object
    {
        return $this->Items[$type] ?? null;
    }

    public function getIterator() : Enumerator
    {
        return new Enumerator($this->Items);
    }
}
