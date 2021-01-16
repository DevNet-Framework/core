<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) 2018-2020 Mohammed Moussaoui
 * @license     MIT License
 * @link        https://github.com/artister
 */

namespace Artister\Web\Identity;

use Artister\Entity\IEntity;
use Artister\System\Collections\IList;

class User implements IEntity
{
    protected int $Id;
    protected string $Username;
    protected string $Password;

    protected IList $UserRole;

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