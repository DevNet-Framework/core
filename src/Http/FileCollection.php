<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Http;

use Artister\System\Collections\Dictionary;
use Artister\System\Type;

class FileCollection extends Dictionary
{
    public function __construct(array $files = null)
    {
        parent::__construct(Type::String, File::class);

        if ($files)
        {
            foreach ($files as $key => $file)
            {
                $this->Add($key, $file);
            }
        }
        else
        {
            $files = $_FILES;

            foreach ($files as $key => $info)
            {
                $file = new File($info['name'], $info['type'], $info['tmp_name'], $info['size'], $info['error']);
                $this->Add($key, $file);
            }
        }
    }
}
