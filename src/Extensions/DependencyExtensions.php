<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Extensions;

use Artister\DevNet\Dependency\IServiceCollection;
use Artister\DevNet\Http\HttpContextFactory;
use Artister\DevNet\Http\HttpContext;
use Artister\DevNet\Router\RouteBuilder;
use Artister\DevNet\Mvc\MvcOptions;
use Artister\DevNet\Mvc\MvcRouteHandler;
use Artister\DevNet\View\ViewManager;
use Artister\System\Database\DbConnection;
use Artister\DevNet\Entity\EntityContext;
use Artister\DevNet\Entity\EntityOptions;
use Artister\System\Security\Authentication\Authentication;
use Artister\System\Security\Authentication\AuthenticationBuilder;
use Artister\System\Security\Authentication\AuthenticationDefaults;
use Artister\System\Security\Authorization\Authorization;
use Artister\System\Security\Authorization\AuthorizationOptions;
use Artister\DevNet\Identity\IdentityContext;
use Artister\DevNet\Identity\IdentityManager;
use Artister\DevNet\Identity\UserManager;
use Artister\DevNet\Identity\RoleManager;
use Artister\DevNet\Identity\User;
use Artister\DevNet\Identity\Role;
use Closure;

class DependencyExtensions
{
    public static function addHttpContext(IServiceCollection $service)
    {
        $service->addSingleton(HttpContext::class, fn() => HttpContextFactory::create());
    }

    public static function addAuthentication(IServiceCollection $service, Closure $configuration = null)
    {
        $builder = new AuthenticationBuilder();
        $builder->addCookie(AuthenticationDefaults::AuthenticationScheme);

        if ($configuration)
        {
            $configuration($builder);
        }

        $service->addSingleton(Authentication::class, fn() => $builder->build());
    }

    public static function addAuthorisation(IServiceCollection $service, Closure $configuration = null)
    {
        $options = new AuthorizationOptions();
        $options->addPolicy("Authentication", fn($policy) => $policy->requireAuthentication());

        if ($configuration) {
            $configuration($options);
        }

        $service->addSingleton(Authorization::class, fn() => new Authorization($options));
    }

    public static function addDbConnection(IServiceCollection $service, string $connection)
    {
        $service->addSingleton(DbConnection::class, fn() => new DbConnection($connection));
    }

    public static function addView(IServiceCollection $service, string $directory)
    {
        $service->addSingleton(ViewManager::class, fn() => new ViewManager($directory));
    }
    
    public static function addMvc(IServiceCollection $service, Closure $configuration = null)
    {
        
        $options = new MvcOptions();
        if ($configuration)
        {
            $configuration($options);
        }
        
        $viewDirectory  = $options->getViewDirectory();
        $service->addView($viewDirectory);
        $service->addHttpContext();

        $service->addSingleton(MvcOptions::class, $options);

        $service->addSingleton(RouteBuilder::class, fn($provider) => new RouteBuilder($provider, new MvcRouteHandler($provider)));
    }

    public static function addEntityContext(IServiceCollection $service, Closure $callbackConfig)
    {
        $entityOptions = new EntityOptions;
        $callbackConfig($entityOptions);
        $entityContextType = $entityOptions->ContextType;

        self::addDbConnection($service, $entityOptions->Connection);
        
        $service->addSingleton($entityContextType, function($Provider) use ($entityContextType) : EntityContext {
            $dbConnection = $Provider->getService(DbConnection::class);
            return new $entityContextType($dbConnection);
        });
    }

    public static function addIdentity(IServiceCollection $service, string $userType = User::class, string $roleType = Role::class)
    {
        self::addAuthentication($service);

        $service->addSingleton(IdentityContext::class, function($provider) use ($userType, $roleType) : IdentityContext {
            $httpContext = $provider->getService(HttpContext::class);
            $entityContext = $provider->getService(EntityContext::class);
            return new IdentityContext($httpContext, $entityContext, $userType, $roleType);
        });

        $service->addSingleton(IdentityManager::class, function($provider) : IdentityManager {
            $identityContext = $provider->getService(IdentityContext::class);
            return new IdentityManager($identityContext);
        });

        $service->addSingleton(UserManager::class, function($provider) : UserManager {
            $identityContext = $provider->getService(IdentityContext::class);
            return new UserManager($identityContext);
        });
        
        $service->addSingleton(RoleManager::class, function($provider) : RoleManager {
            $identityContext = $provider->getService(IdentityContext::class);
            return new RoleManager($identityContext);
        });
    }
}