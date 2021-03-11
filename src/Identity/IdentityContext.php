<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Identity;

use Artister\Web\Http\HttpContext;
use Artister\Entity\EntityContext;
use Artister\Entity\EntitySet;
use Artister\Entity\EntityModelBuilder;

class IdentityContext
{
    private HttpContext $HttpContext;
    private EntityContext $EntityContext;
    private string $UserType;
    private string $RoleType;
    private EntitySet $Users;
    private EntitySet $Roles;
    private EntitySet $UserRole;

    public function __construct(HttpContext $httpContext, EntityContext $entityContext, string $userType, string $roleType)
    {
        $this->HttpContext   = $httpContext;
        $this->EntityContext = $entityContext;
        $this->UserType      = $userType;
        $this->RoleType      = $roleType;
        $this->Users         = $entityContext->set($userType);
        $this->Roles         = $entityContext->set($roleType);
        $this->UserRole      = $entityContext->set(UserRole::class);

        $builder = $entityContext->Model->Builder;

        if ($userType == User::class && $roleType == Role::class)
        {
            $this->onModelCreate($builder);
        }
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function onModelCreate(EntityModelBuilder $builder)
    {
        $builder->entity(UserRole::class)
                ->hasForeignKey('UserId', $this->UserType)
                ->hasForeignKey('RoleId', $this->RoleType)
                ->hasOne('User', $this->UserType)
                ->hasOne('Role', $this->RoleType);

        $builder->entity($this->UserType)->hasMany('UserRole', UserRole::class);
        $builder->entity($this->RoleType)->hasMany('UserRole', UserRole::class);
    }

    public function save() : int
    {
        return $this->EntityContext->save();
    }
}