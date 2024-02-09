<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Endpoint\Binder;

interface IValueProvider
{
    /**
     * Finds an entry of the container by its identifier and returns it.
     * @param string $key Identifier of the entry to look for.
     * @return mixed Entry.
     */
    public function getValue(string $key);

    public function contains(string $key);
}
