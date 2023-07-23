<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http;

use DevNet\System\Tweak;

class FormFile
{
    use Tweak;

    private ?string $name;
    private ?string $type;
    private ?string $temp;
    private ?int $size;
    private ?int $error;

    public function __construct(
        string $name,
        string $type,
        string $temp,
        int $size,
        int $error
    ) {
        $this->name  = $name;
        $this->type  = $type;
        $this->temp  = $temp;
        $this->size  = $size;
        $this->error = $error;
    }

    public function get_Name(): ?string
    {
        return $this->name;
    }

    public function get_type(): ?string
    {
        return $this->type;
    }

    public function get_Temp(): ?string
    {
        return $this->temp;
    }

    public function get_Size(): ?int
    {
        return $this->size;
    }

    public function get_Error(): ?int
    {
        return $this->error;
    }

    public function copyTo(string $target): bool
    {
        return copy($this->temp, $target);
    }
}
