<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
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
    private array $values;

    public function __construct(?string $queryString = null)
    {
        $this->queryString = (string) $queryString;
        parse_str($this->queryString, $output);
        $this->values = $output;
    }

    public function get_Values(): array
    {
        return $this->values;
    }

    public function Contains(string $key): bool
    {
        return isset($this->values[$key]) ? true : false;
    }

    public function getValue(string $key): ?string
    {
        return $this->values[$key] ?? null;
    }

    public function getIterator(): Enumerator
    {
        return new Enumerator($this->values);
    }

    public function __toString(): string
    {
        return $this->queryString;
    }
}
