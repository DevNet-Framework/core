<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Mvc\Providers;

use Artister\Web\Mvc\Binder\ValueProvider;
use Artister\Web\Http\FileCollection;

/**
 * Describes the interface of a container that exposes methods to read its entries.
 */
class FileValueProvider extends ValueProvider
{
    public function __construct(FileCollection $files = null)
    {
        if (!$files) {
            $files = new FileCollection();
        }

        $this->Values = $files->toArray();
    }
}