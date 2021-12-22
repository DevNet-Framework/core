<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Controller\Providers;

use DevNet\Core\Controller\Binder\ValueProvider;

/**
 * Describes the interface of a container that exposes methods to read its entries.
 */
class QueryValueProvider extends ValueProvider
{
    public function __construct(array $values = null)
    {
        if (!$values) {
            $this->Values = $_GET;
        }
    }
}
