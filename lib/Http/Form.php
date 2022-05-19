<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http;

use DevNet\System\Exceptions\PropertyException;

class Form
{
    private array $fields;
    private FileCollection $files;

    public function __get(string $name)
    {
        if ($name == 'Fields') {
            return $this->fields;
        }
        
        if ($name == 'Files') {
            return $this->files;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . get_class($this) . "::" . $name);
    }

    public function __construct(array $fields = null, FileCollection $files = null)
    {
        if (!$fields) {
            $fields = $_POST;
        }

        if (!$files) {
            $files = new FileCollection();
        }

        $this->fields = $fields;
        $this->files  = $files;
    }

    public function getValue(string $name)
    {
        return $this->fields[$name] ?? null;
    }

    public function count()
    {
        return count($this->fields);
    }
}
