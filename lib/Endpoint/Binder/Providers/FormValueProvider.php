<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Endpoint\Binder\Providers;

/**
 * Describes the interface of a container that exposes methods to read its entries.
 */
class FormValueProvider extends ValueProvider
{
    public function __construct(array $values = [])
    {
        if ($values == []) {
            $this->values = $_POST;
        }
    }
}
