<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http;

use DevNet\System\PropertyTrait;

class Form
{
    use PropertyTrait;

    private array $fields;
    private FileCollection $files;

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

    public function get_Fields(): array
    {
        return $this->fields;
    }

    public function get_Files(): FileCollection
    {
        return $this->files;
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
