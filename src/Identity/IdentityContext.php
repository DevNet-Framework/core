<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) 2018-2020 Mohammed Moussaoui
 * @license     MIT License
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Identity;

use Artister\DevNet\Entity\EntityContext;
use Artister\DevNet\Entity\EntitySet;
use Artister\DevNet\Entity\EntityModelBuilder;
use Artister\DevNet\Http\HttpContext;

class IdentityContext
{
    private HttpContext $HttpContext;
    private EntityContext $EntityContext;
    private EntitySet $Users;
    private EntitySet $Roles;
    private EntitySet $UserRole;

    public function __construct(HttpContext $httpContext, EntityContext $entityContext, string $userClass, string $roleClass)
    {
        $this->HttpContext      = $httpContext;
        $this->EntityContext    = $entityContext;
        $this->Users            = $entityContext->set($userClass);
        $this->Roles            = $entityContext->set($roleClass);
        $this->UserRole         = $entityContext->set(UserRole::class);

        $builder = $entityContext->Model->Builder;

        if ($userClass == User::class && $roleClass == Role::class)
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