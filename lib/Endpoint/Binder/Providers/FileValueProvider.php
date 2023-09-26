<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Endpoint\Binder\Providers;

use DevNet\Web\Endpoint\Binder\IValueProvider;
use DevNet\Web\Http\FileCollection;

/**
 * Describes the interface of a container that exposes methods to read its entries.
 */
class FileValueProvider implements IValueProvider
{
    private FileCollection $files;

    public function __construct(FileCollection $files = null)
    {
        if (!$files) {
            $files = new FileCollection();
        }
        $this->files = $files;
    }

    public function getValue(string $key)
    {
        $files = $this->files->getFiles($key);
        if (count($files) == 1) {
            return $files[0];
        }
        return $files;
    }

    public function contains(string $key): bool
    {
        $files = $this->files->getFiles($key);
        if (count($files) > 0) {
            return true;
        }
        return false;
    }
}
