<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Identity;

use DevNet\Entity\EntityContext;
use DevNet\Entity\EntityModelBuilder;
use DevNet\Entity\EntitySet;
use DevNet\System\Tweak;
use DevNet\Web\Http\HttpContext;

class IdentityContext
{
    use Tweak;

    private HttpContext $httpContext;
    private EntityContext $entityContext;
    private IdentityOptions $options;
    private string $userType;
    private string $roleType;
    private EntitySet $users;
    private EntitySet $roles;
    private EntitySet $userRole;

    public function __construct(
        HttpContext $httpContext,
        EntityContext $entityContext,
        IdentityOptions $identityOptions
    ) {
        $this->httpContext   = $httpContext;
        $this->entityContext = $entityContext;
        $this->userType      = $identityOptions->UserType;
        $this->roleType      = $identityOptions->RoleType;
        $this->options       = $identityOptions;
        $this->users         = $entityContext->set($this->userType);
        $this->roles         = $entityContext->set($this->roleType);
        $this->userRole      = $entityContext->set(UserRole::class);

        $this->onModelCreate($entityContext->Database->Model->Builder);
    }

    public function get_HttpContext(): HttpContext
    {
        return $this->httpContext;
    }

    public function get_EntityContext(): EntityContext
    {
        return $this->entityContext;
    }

    public function get_Options(): IdentityOptions
    {
        return $this->options;
    }

    public function get_Users(): EntitySet
    {
        return $this->users;
    }

    public function get_Roles(): EntitySet
    {
        return $this->roles;
    }

    public function get_UserRole(): EntitySet
    {
        return $this->userRole;
    }

    public function onModelCreate(EntityModelBuilder $builder)
    {
        $builder->entity(UserRole::class)->navigation('UserId')->hasForeignKey($this->userType);
        $builder->entity(UserRole::class)->navigation('RoleId')->hasForeignKey($this->roleType);
        $builder->entity($this->userType)->navigation('UserRole')->hasMany(UserRole::class);
        $builder->entity($this->roleType)->navigation('UserRole')->hasMany(UserRole::class);
    }

    public function save(): int
    {
        return $this->entityContext->save();
    }
}
