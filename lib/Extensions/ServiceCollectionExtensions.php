<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Extensions;

use DevNet\Web\Dependency\IServiceCollection;
use DevNet\Web\Http\HttpContext;
use DevNet\Web\Router\RouteBuilder;
use DevNet\Web\View\ViewManager;
use DevNet\Web\Controller\ControllerOptions;
use DevNet\Web\Controller\ControllerRouteHandler;
use DevNet\Web\Security\Antiforgery\IAntiforgery;
use DevNet\Web\Security\Antiforgery\Antiforgery;
use DevNet\Web\Security\Antiforgery\AntiforgeryOptions;
use DevNet\Web\Security\Authentication\Authentication;
use DevNet\Web\Security\Authentication\AuthenticationBuilder;
use DevNet\Web\Security\Authentication\AuthenticationDefaults;
use DevNet\Web\Security\Authorization\Authorization;
use DevNet\Web\Security\Authorization\AuthorizationOptions;
use DevNet\Web\Identity\IdentityContext;
use DevNet\Web\Identity\IdentityOptions;
use DevNet\Web\Identity\IdentityManager;
use DevNet\Web\Identity\UserManager;
use DevNet\Web\Identity\RoleManager;
use DevNet\Web\Identity\User;
use DevNet\Web\Identity\Role;
use DevNet\System\Database\DbConnection;
use DevNet\Entity\EntityContext;
use DevNet\Entity\EntityOptions;
use Closure;

class ServiceCollectionExtensions
{
    public static function addAntiforgery(IServiceCollection $services, Closure $configuration = null)
    {
        $options = new AntiforgeryOptions();
        if ($configuration) {
            $configuration($options);
        }

        $services->addSingleton(IAntiforgery::class, fn () => new Antiforgery($options));
    }

    public static function addAuthentication(IServiceCollection $services, Closure $configuration = null)
    {
        $builder = new AuthenticationBuilder();
        $builder->addCookie(AuthenticationDefaults::AuthenticationScheme, $configuration);
        $services->addSingleton(Authentication::class, fn () => $builder->build());
    }

    public static function addAuthorisation(IServiceCollection $services, Closure $configuration = null)
    {
        $options = new AuthorizationOptions();
        $options->addPolicy("Authentication", fn ($policy) => $policy->requireAuthentication());

        if ($configuration) {
            $configuration($options);
        }

        $services->addSingleton(Authorization::class, fn () => new Authorization($options));
    }

    public static function addDbConnection(IServiceCollection $services, string $connection)
    {
        $services->addSingleton(DbConnection::class, fn () => new DbConnection($connection));
    }

    public static function addView(IServiceCollection $services, string $directory)
    {
        $services->addSingleton(ViewManager::class, fn ($provider) => new ViewManager($directory, $provider));
    }

    public static function addMvc(IServiceCollection $services, Closure $configuration = null)
    {
        $options = new ControllerOptions();

        if ($configuration) {
            $configuration($options);
        }

        $viewDirectory = $options->getViewDirectory();
        $services->addView($viewDirectory);
        $services->addSingleton(ControllerOptions::class, $options);
        $services->addSingleton(RouteBuilder::class, fn ($provider) => new RouteBuilder($provider, new ControllerRouteHandler($provider)));
    }

    public static function addEntityContext(IServiceCollection $services, string $entityConext, Closure $configuration = null)
    {
        $entityOptions = new EntityOptions;

        if ($configuration) {
            $configuration($entityOptions);
        }

        $services->addSingleton($entityConext, fn () => new $entityConext($entityOptions));
        $services->addSingleton(EntityContext::class, fn ($provider) => $provider->getService($entityConext));
    }

    public static function addIdentity(IServiceCollection $services, string $userType = User::class, string $roleType = Role::class, Closure $configuration = null)
    {
        $identityOptions = new IdentityOptions;

        if ($configuration) {
            $configuration($identityOptions);
        }

        $services->addSingleton(IdentityContext::class, function ($provider) use ($services, $userType, $roleType, $identityOptions): IdentityContext {
            if (!$provider->contains(Authentication::class)) {
                self::addAuthentication($services);
            }

            $httpContext = $provider->getService(HttpContext::class);
            $entityContext = $provider->getService(EntityContext::class);
            return new IdentityContext($httpContext, $entityContext, $userType, $roleType, $identityOptions);
        });

        $services->addSingleton(IdentityManager::class, function ($provider): IdentityManager {
            $identityContext = $provider->getService(IdentityContext::class);
            return new IdentityManager($identityContext);
        });

        $services->addSingleton(UserManager::class, function ($provider): UserManager {
            $identityContext = $provider->getService(IdentityContext::class);
            return new UserManager($identityContext);
        });

        $services->addSingleton(RoleManager::class, function ($provider): RoleManager {
            $identityContext = $provider->getService(IdentityContext::class);
            return new RoleManager($identityContext);
        });
    }
}
