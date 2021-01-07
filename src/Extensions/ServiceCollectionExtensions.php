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
use Artister\Web\Security\Authentication\Authentication;
use Artister\Web\Security\Authentication\AuthenticationBuilder;
use Artister\Web\Security\Authentication\AuthenticationDefaults;
use Artister\Web\Security\Authorization\Authorization;
use Artister\Web\Security\Authorization\AuthorizationOptions;
use Artister\Web\Identity\IdentityContext;
use Artister\Web\Identity\IdentityManager;
use Artister\Web\Identity\UserManager;
use Artister\Web\Identity\RoleManager;
use Artister\Web\Identity\IdentityUser;
use Artister\Web\Identity\IdentityRole;
use Closure;

class ServiceCollectionExtensions
{
    public static function addHttpContext(IServiceCollection $service)
    {
        $service->addSingleton(HttpContext::class, function($provider) : HttpContext {
            $httpContext = HttpContextFactory::create();
            $httpContext->addAttribute('RequestServices', $provider);
            return $httpContext;
        });
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
        
        $service->addSingleton(EntityContext::class, function($provider) use ($entityOptions) : EntityContext {
            $entityContextType = $entityOptions->ContextType;
            return new $entityContextType($entityOptions);
        });
    }

    public static function addIdentity(IServiceCollection $service, string $userType = IdentityUser::class, string $roleType = IdentityRole::class)
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