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
use Traversable;

class FileCollection implements IEnumerable
{
    private array $files = [];

    public function addFile(string $name,  FormFile $file)
    {
        $this->files[$name][] = $file;
    }

    public function getFile(string $name): ?FormFile
    {
        return $this->files[$name][0] ?? null;
    }

    public function getFiles(string $name): array
    {
        return $this->files[$name] ?? [];
    }

    public function getIterator(): Traversable
    {
        return new Enumerator($this->files);
    }
}
