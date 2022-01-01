<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Mvc\Providers;

use DevNet\Web\Mvc\Binder\IValueProvider;
use DevNet\Web\Http\FileCollection;

/**
 * Describes the interface of a container that exposes methods to read its entries.
 */
class FileValueProvider implements IValueProvider
{
    private FileCollection $Files;

    public function __construct(FileCollection $files = null)
    {
        if (!$files) {
            $files = new FileCollection();
        }
        $this->Files = $files;
    }

    public function getValue(string $key)
    {
        $files = $this->Files->getFiles($key);
        if (count($files) == 1) {
            return $files[0];
        }
        return $files;
    }

    public function contains(string $key): bool
    {
        $files = $this->Files->getFiles($key);
        if (count($files) > 0) {
            return true;
        }
        return false;
    }
}
