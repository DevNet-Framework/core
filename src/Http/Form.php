<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Http;

class Form
{
    public array $Fields;
    public FileCollection $Files;
    
    public function __construct(array $fields = null, FileCollection $files = null)
    {
        if (!$fields)
        {
            $fields = $_POST;
        }

        if (!$files)
        {
            $files = new FileCollection();
        }

        $this->Fields   = $fields;
        $this->Files    = $files;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function getValue(string $name)
    {
        return $this->Fields[$name] ?? null;
    }

    public function count()
    {
        return count($this->Fields);
    }
}
