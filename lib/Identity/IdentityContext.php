<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Identity;

use DevNet\Web\Http\HttpContext;
use DevNet\Entity\EntityContext;
use DevNet\Entity\EntitySet;
use DevNet\Entity\EntityModelBuilder;
use DevNet\System\Exceptions\PropertyException;

class IdentityContext
{
    private HttpContext $httpContext;
    private EntityContext $entityContext;
    private IdentityOptions $options;
    private string $userType;
    private string $roleType;
    private EntitySet $users;
    private EntitySet $roles;
    private EntitySet $userRole;

    public function __get(string $name)
    {
        if (in_array($name, ['HttpContext', 'EntityContext', 'Options', 'Users', 'Roles', 'UserRole'])) {
            $property = lcfirst($name);
            return $this->$property;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . get_class($this) . "::" . $name);
    }

    public function __construct(
        HttpContext $httpContext,
        EntityContext $entityContext,
        string $userType,
        string $roleType,
        IdentityOptions $identityOptions
    ) {
        $this->httpContext   = $httpContext;
        $this->entityContext = $entityContext;
        $this->userType      = $userType;
        $this->roleType      = $roleType;
        $this->options       = $identityOptions;
        $this->users         = $entityContext->set($userType);
        $this->roles         = $entityContext->set($roleType);
        $this->userRole      = $entityContext->set(UserRole::class);

        $this->onModelCreate($entityContext->Model->Builder);
    }

    public function onModelCreate(EntityModelBuilder $builder)
    {
        $builder->entity(UserRole::class)
            ->hasForeignKey('UserId', $this->userType)
            ->hasForeignKey('RoleId', $this->roleType)
            ->hasOne('User', $this->userType)
            ->hasOne('Role', $this->roleType);

        $builder->entity($this->userType)->hasMany('UserRole', UserRole::class);
        $builder->entity($this->roleType)->hasMany('UserRole', UserRole::class);
    }

    public function save(): int
    {
        return $this->entityContext->save();
    }
}
