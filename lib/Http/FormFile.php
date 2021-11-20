<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http;

class FormFile
{
    private ?string $Name;
    private ?string $Type;
    private ?string $Temp;
    private ?int $Size;
    private ?int $Error;

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __construct(
        string $name,
        string $type,
        string $temp,
        int $size,
        int $error
    ) {
        $this->Name  = $name;
        $this->Type  = $type;
        $this->Temp  = $temp;
        $this->Size  = $size;
        $this->Error = $error;
    }

    public function copyTo(string $target): bool
    {
        return copy($this->Temp, $target);
    }
}
