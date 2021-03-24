<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Extensions;

use Artister\System\Dependency\IServiceCollection;
use Artister\System\Database\DbConnection;
use Artister\Entity\EntityContext;
use Artister\Entity\EntityOptions;
use Artister\Web\Http\HttpContextFactory;
use Artister\Web\Http\HttpContext;
use Artister\Web\Router\RouteBuilder;
use Artister\Web\Mvc\MvcOptions;
use Artister\Web\Mvc\MvcRouteHandler;
use Artister\Web\View\ViewManager;
use Artister\Web\Security\Antiforgery\IAntiforgery;
use Artister\Web\Security\Antiforgery\Antiforgery;
use Artister\Web\Security\Antiforgery\AntiforgeryOptions;
use Artister\Web\Security\Authentication\Authentication;
use Artister\Web\Security\Authentication\AuthenticationBuilder;
use Artister\Web\Security\Authentication\AuthenticationDefaults;
use Artister\Web\Security\Authorization\Authorization;
use Artister\Web\Security\Authorization\AuthorizationOptions;
use Artister\Web\Identity\IdentityContext;
use Artister\Web\Identity\IdentityManager;
use Artister\Web\Identity\UserManager;
use Artister\Web\Identity\RoleManager;
use Artister\Web\Identity\User;
use Artister\Web\Identity\Role;
use Closure;

class ServiceCollectionExtensions
{
    public static function addAntiforgery(IServiceCollection $services, $options = new AntiforgeryOptions();

        if ($configuration)
        {
            $configuration($options);
        }

        $services->addSingleton(IAntiforgery::class, fn() => new Antiforgery($options));
    }

    public static function addAuthentication(IServiceCollection $services, Closure $configuration = null)
    {
        $builder = new AuthenticationBuilder();
        $builder->addCookie(AuthenticationDefaults::AuthenticationScheme);

        if ($configuration)
        {
            $configuration($builder);
        }

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
        $options = new MvcOptions();

        if ($configuration)
        {
            $configuration($options);
        }
        
        $viewDirectory  = $options->getViewDirectory();
        $services->addView($viewDirectory);
        $services->addSingleton(MvcOptions::class, $options);
        $services->addSingleton(RouteBuilder::class, fn($provider) => new RouteBuilder($provider, new MvcRouteHandler($provider)));
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
        self::addAuthentication($services, $configuration);

        $services->addSingleton(IdentityContext::class, function($provider) use ($userType, $roleType) : IdentityContext
        {
            $httpContext = $provider->getService(HttpContext::class);
            $entityContext = $provider->getService(EntityContext::class);
            return new IdentityContext($httpContext, $entityContext, $userType, $roleType);
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
