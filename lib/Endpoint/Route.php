<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Endpoint;

use Attribute;

#[Attribute]
class Route
{
    public readonly string $Path;
    public readonly ?string $Method;

    public function __construct(string $path, ?string $method = null)
    {
        $this->Path = $path;
        $this->Method = $method;
    }
}
