<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Endpoint\Binder;

interface IModelBinder
{
    public function bind(BindingContext $bidingContext);
}
