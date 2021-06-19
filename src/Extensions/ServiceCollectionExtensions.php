<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Extensions;

use DevNet\Core\Dependency\IServiceCollection;
use DevNet\Core\Http\HttpContext;
use DevNet\Core\Router\RouteBuilder;
use DevNet\Core\View\ViewManager;
use DevNet\Core\Controller\ControllerOptions;
use DevNet\Core\Controller\ControllerRouteHandler;
use DevNet\Core\Security\Antiforgery\IAntiforgery;
use DevNet\Core\Security\Antiforgery\Antiforgery;
use DevNet\Core\Security\Antiforgery\AntiforgeryOptions;
use DevNet\Core\Security\Authentication\Authentication;
use DevNet\Core\Security\Authentication\AuthenticationBuilder;
use DevNet\Core\Security\Authentication\AuthenticationDefaults;
use DevNet\Core\Security\Authorization\Authorization;
use DevNet\Core\Security\Authorization\AuthorizationOptions;
use DevNet\Core\Identity\IdentityContext;
use DevNet\Core\Identity\IdentityOptions;
use DevNet\Core\Identity\IdentityManager;
use DevNet\Core\Identity\UserManager;
use DevNet\Core\Identity\RoleManager;
use DevNet\Core\Identity\User;
use DevNet\Core\Identity\Role;
use DevNet\System\Database\DbConnection;
use DevNet\Entity\EntityContext;
use DevNet\Entity\EntityOptions;
use Closure;

class ServiceCollectionExtensions
{
    public static function addAntiforgery(IServiceCollection $services, Closure $configuration = null)
    {
        $options = new AntiforgeryOptions();
        if ($configuration)
        {
            $configuration($options);
        }

        $services->addSingleton(IAntiforgery::class, fn() => new Antiforgery($options));
    }

    public static function addAuthentication(IServiceCollection $services, Closure $configuration = null)
    {
        $builder = new AuthenticationBuilder();
        $builder->addCookie(AuthenticationDefaults::AuthenticationScheme, $configuration);
        $services->addSingleton(Authentication::class, fn() => $builder->build());
    }

    public static function addAuthorisation(IServiceCollection $services, Closure $configuration = null)
    {
        $options = new AuthorizationOptions();
        $options->addPolicy("Authentication", fn($policy) => $policy->requireAuthentication());

        if ($configuration)
        {
            $configuration($options);
        }

        $services->addSingleton(Authorization::class, fn() => new Authorization($options));
    }

    public static function addDbConnection(IServiceCollection $services, string $connection)
    {
        $services->addSingleton(DbConnection::class, fn() => new DbConnection($connection));
    }

    public static function addView(IServiceCollection $services, string $directory)
    {
        $services->addSingleton(ViewManager::class, fn() => new ViewManager($directory));
    }
    
    public static function addMvc(IServiceCollection $services, Closure $configuration = null)
    {
        $options = new ControllerOptions();

        if ($configuration)
        {
            $configuration($options);
        }
        
        $viewDirectory = $options->getViewDirectory();
        $services->addView($viewDirectory);
        $services->addSingleton(ControllerOptions::class, $options);
        $services->addSingleton(RouteBuilder::class, fn($provider) => new RouteBuilder($provider, new ControllerRouteHandler($provider)));
    }

    public static function addEntityContext(IServiceCollection $services, string $entityConext, Closure $configuration = null)
    {
        $entityOptions = new EntityOptions;

        if ($configuration)
        {
            $configuration($entityOptions);
        }
        
        $services->addSingleton($entityConext, fn() => new $entityConext($entityOptions));
        $services->addSingleton(EntityContext::class, fn($provider) => $provider->getService($entityConext));
    }

    public static function addIdentity(IServiceCollection $services, string $userType = User::class, string $roleType = Role::class, Closure $configuration = null)
    {
        $identityOptions = new IdentityOptions;

        if ($configuration)
        {
            $configuration($identityOptions);
        }

        $services->addSingleton(IdentityContext::class, function($provider) use ($services, $userType, $roleType, $identityOptions) : IdentityContext
        {
            if (!$provider->has(Authentication::class))
            {
                self::addAuthentication($services);
            }

            $httpContext = $provider->getService(HttpContext::class);
            $entityContext = $provider->getService(EntityContext::class);
            return new IdentityContext($httpContext, $entityContext, $userType, $roleType, $identityOptions);
        });

        $services->addSingleton(IdentityManager::class, function($provider) : IdentityManager
        {
            $identityContext = $provider->getService(IdentityContext::class);
            return new IdentityManager($identityContext);
        });

        $services->addSingleton(UserManager::class, function($provider) : UserManager
        {
            $identityContext = $provider->getService(IdentityContext::class);
            return new UserManager($identityContext);
        });
        
        $services->addSingleton(RoleManager::class, function($provider) : RoleManager
        {
            $identityContext = $provider->getService(IdentityContext::class);
            return new RoleManager($identityContext);
        });
    }
}
