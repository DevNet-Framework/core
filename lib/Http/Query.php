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
use DevNet\System\PropertyTrait;

class Query implements IEnumerable
{
    use PropertyTrait;

    private string $queryString;
    private array $items;

    public function __construct(?string $queryString = null)
    {
        $this->queryString = (string) $queryString;
        parse_str($this->queryString, $output);
        $this->items = $output;
    }

    public function get_Items(): array
    {
        return $this->items;
    }

    public function Contains(string $key): bool
    {
        return isset($this->items[$key]) ? true : false;
    }

    public function getValue(string $key): ?string
    {
        return $this->items[$key] ?? null;
    }

    public function getIterator(): Enumerator
    {
        return new Enumerator($this->items);
    }

    public function __toString(): string
    {
        return $this->queryString;
    }
}
