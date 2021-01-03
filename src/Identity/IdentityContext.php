<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) 2018-2020 Mohammed Moussaoui
 * @license     MIT License
 * @link        https://github.com/artister
 */

namespace Artister\Web\Identity;

use Artister\Web\Http\HttpContext;
use Artister\Data\Entity\EntityContext;
use Artister\Data\Entity\EntitySet;
use Artister\Data\Entity\EntityModelBuilder;

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
        $this->UserRole         = $entityContext->set(IdentityUserRole::class);

        $builder = $entityContext->Model->Builder;

        if ($userType == IdentityUser::class && $roleType == IdentityRole::class)
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
        $builder->entity(IdentityUserRole::class)
                ->hasForeignKey('UserId', IdentityUser::class)
                ->hasForeignKey('RoleId', IdentityRole::class)
                ->hasOne('User', IdentityUser::class)
                ->hasOne('Role', IdentityRole::class);

        $builder->entity(IdentityUser::class)->hasMany('UserRole', IdentityUserRole::class);
        $builder->entity(IdentityRole::class)->hasMany('UserRole', IdentityUserRole::class);
    }

    public function save()
    {
        $this->EntityContext->save();
    }
}