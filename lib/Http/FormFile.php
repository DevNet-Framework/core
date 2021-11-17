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
    private ?string $InputName;
    private ?string $FileName;
    private ?string $FileType;
    private ?string $TempName;
    private ?int $FileSize;
    private ?int $Error;

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __construct(
        string $inputName,
        string $fileName,
        string $fileType,
        string $tempName,
        int $fileSize,
        int $error
    ) {
        $this->InputName = $inputName;
        $this->FileName  = $fileName;
        $this->FileType  = $fileType;
        $this->TempName  = $tempName;
        $this->FileSize  = $fileSize;
        $this->Error     = $error;
    }

    public function copyTo(string $target): bool
    {
        return copy($this->TempName, $target);
    }
}
