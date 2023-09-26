<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Endpoint\Binder\Providers;

/**
 * Describes the interface of a container that exposes methods to read its entries.
 */
class FormValueProvider extends ValueProvider
{
    public function __construct(array $values = null)
    {
        if (!$values) {
            $this->values = $_POST;
        }
    }
}
