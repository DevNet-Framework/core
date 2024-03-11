<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http;

use DevNet\System\PropertyTrait;

class Form
{
    use PropertyTrait;

    private array $fields;
    private FormFileCollection $files;

    public function __construct(array $fields = null, FormFileCollection $files = null)
    {
        if (!$fields) {
            $fields = $_POST;
        }

        if (!$files) {
            $files = new FormFileCollection();
        }

        $this->fields = $fields;
        $this->files  = $files;
    }

    public function get_Fields(): array
    {
        return $this->fields;
    }

    public function get_Files(): FormFileCollection
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
