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

class Role implements IEntity
{
    protected int $Id;
    protected string $Name;

    protected IList $UserRole;

    public function __construct(string $roleName = null)
    {
        if ($roleName)
        {
            $this->Name = $roleName;
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
