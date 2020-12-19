<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) 2018-2020 Mohammed Moussaoui
 * @license     MIT License
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Identity;

use Artister\Data\Entity\IEntity;
use Artister\System\Collections\IList;

class User implements IEntity
{
    private int $Id;
    private string $Username;
    private string $Password;

    private IList $UserRole;

    public function __construct(string $username = null)
    {
        if ($username) {
            $this->Username = $username;
        }
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __set(string $name, $value)
    {
        $this->$name = $value;
    }
}