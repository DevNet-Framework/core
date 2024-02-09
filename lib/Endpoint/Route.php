<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
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
