<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) 2018-2020 Mohammed Moussaoui
 * @license     MIT License
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
    private EntitySet $Users;
    private EntitySet $Roles;
    private EntitySet $UserRole;

    public function __construct(HttpContext $httpContext, EntityContext $entityContext, string $userType, string $roleType)
    {
        $this->HttpContext      = $httpContext;
        $this->EntityContext    = $entityContext;
        $this->Users            = $entityContext->set($userType);
        $this->Roles            = $entityContext->set($roleType);
        $this->UserRole         = $entityContext->set(UserRole::class);

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
                ->hasForeignKey('UserId', User::class)
                ->hasForeignKey('RoleId', Role::class)
                ->hasOne('User', User::class)
                ->hasOne('Role', Role::class);

        $builder->entity(User::class)->hasMany('UserRole', UserRole::class);
        $builder->entity(Role::class)->hasMany('UserRole', UserRole::class);
    }

    public function save()
    {
        $this->EntityContext->save();
    }
}