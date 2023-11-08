<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http;

use DevNet\System\PropertyTrait;

class Host
{
    use PropertyTrait;

    private string $name;
    private ?int $port;

    public function __construct(?string $name = null, ?int $port = null)
    {
        $this->name = (string) $name;
        $this->port = $port;
    }

    public function get_Name(): string
    {
        return $this->name;
    }

    public function get_Port(): ?int
    {
        return $this->port;
    }

    public function __toString(): string
    {
        $host = $this->port ? $this->name . ':' . $this->port : $this->host;
        return $host;
    }
}
