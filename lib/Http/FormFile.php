<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http;

use DevNet\System\Exceptions\PropertyException;

class FormFile
{
    private ?string $name;
    private ?string $type;
    private ?string $temp;
    private ?int $size;
    private ?int $error;

    public function __get(string $name)
    {
        if (in_array($name, ['Name', 'Type', 'Temp', 'Size', 'Error'])) {
            $property = lcfirst($name);
            return $this->$property;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . get_class($this) . "::" . $name);
    }

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

    public function copyTo(string $target): bool
    {
        return copy($this->temp, $target);
    }
}
