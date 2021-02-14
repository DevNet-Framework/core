<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Http;

use Artister\System\Collections\Enumerator;
use Artister\System\Collections\IEnumerable;
use Artister\System\Type;
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
