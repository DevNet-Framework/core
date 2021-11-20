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

class FileCollection implements IEnumerable
{
    private array $Files = [];

    public function addFile(string $name,  FormFile $file)
    {
        $this->Files[$name][] = $file;
    }

    public function getFile(string $name): ?FormFile
    {
        return $this->Files[$name][0] ?? null;
    }

    public function getFiles(string $name): array
    {
        return $this->Files[$name] ?? [];
    }

    public function getIterator(): Enumerator
    {
        return new Enumerator($this->Files);
    }
}
