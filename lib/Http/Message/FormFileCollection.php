<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http\Message;

use DevNet\System\Collections\Enumerator;
use DevNet\System\Collections\IEnumerable;

class FormFileCollection implements IEnumerable
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

    public function getIterator(): Enumerator
    {
        return new Enumerator($this->files);
    }
}
