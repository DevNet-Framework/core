<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http;

use DevNet\System\Collections\ArrayList;
use DevNet\System\Type;

class FileCollection extends ArrayList
{
    public function __construct()
    {
        parent::__construct(Type::Integer, FormFile::class);
    }

    public function getFile(string $name): ?FormFile
    {
        foreach ($this->Array as $file) {
            if ($file->Name == $name) {
                return $file;
            }
        }
        return null;
    }

    public function getFiles(string $name): array
    {
        $files = [];
        foreach ($this->Array as $file) {
            if ($file->Name == $name) {
                $files[] = $file;
            }
        }
        return $files;
    }
}
