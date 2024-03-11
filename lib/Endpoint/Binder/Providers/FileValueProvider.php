<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Endpoint\Binder\Providers;

use DevNet\Web\Endpoint\Binder\IValueProvider;
use DevNet\Web\Http\Message\FormFileCollection;

/**
 * Describes the interface of a container that exposes methods to read its entries.
 */
class FileValueProvider implements IValueProvider
{
    private FormFileCollection $files;

    public function __construct(FormFileCollection $files = null)
    {
        if (!$files) {
            $files = new FormFileCollection();
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
