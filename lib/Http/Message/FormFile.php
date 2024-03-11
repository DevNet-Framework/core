<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http\Message;

use DevNet\System\PropertyTrait;

class FormFile
{
    use PropertyTrait;

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
