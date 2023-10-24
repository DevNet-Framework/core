<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Extensions;

use DevNet\Entity\EntityContext;
use DevNet\Entity\EntityOptions;
use DevNet\Web\Http\HttpContext;
use DevNet\Web\Http\Client\HttpClient;
use DevNet\Web\Http\Client\HttpClientOptions;
use DevNet\Web\Endpoint\ControllerOptions;
use DevNet\Web\Security\Tokens\Csrf\IAntiforgery;
use DevNet\Web\Security\Tokens\Csrf\Antiforgery;
use DevNet\Web\Security\Tokens\Csrf\AntiforgeryOptions;
use DevNet\Web\Security\Authentication\Authentication;
use DevNet\Web\Security\Authentication\AuthenticationBuilder;
use DevNet\Web\Security\Authentication\IAuthentication;
use DevNet\Web\Security\Authorization\Authorization;
use DevNet\Web\Security\Authorization\AuthorizationOptions;
use DevNet\Web\Security\Authorization\IAuthorization;
use DevNet\Web\Identity\IdentityContext;
use DevNet\Web\Identity\IdentityOptions;
use DevNet\Web\Identity\IdentityManager;
use DevNet\Web\Identity\UserManager;
use DevNet\Web\Identity\RoleManager;
use DevNet\System\Database\DbConnection;
use DevNet\System\Dependency\IServiceCollection;
use DevNet\System\Logging\ILoggerFactory;
use DevNet\System\Logging\LoggerFactory;
use DevNet\System\Runtime\LauncherProperties;
use Closure;

class ServiceCollectionExtensions
{
    public static function addLogging(IServiceCollection $services, Closure $configuration = null)
    {
        $services->addSingleton(ILoggerFactory::class, fn (): ILoggerFactory => LoggerFactory::Create($configuration));
    }

    public static function addHttpClient(IServiceCollection $services, Closure $configuration = null)
    {
        $options = new HttpClientOptions();
        if ($configuration) {
            $configuration($options);
        }

        $services->addSingleton(HttpClient::class, fn (): HttpClient => new HttpClient($options));
    }

    public static function addController(IServiceCollection $services, Closure $configuration = null)
    {
        $options = new ControllerOptions();

        if ($configuration) {
            $configuration($options);
        }

        if (!is_dir($options->ControllersDirectory)) {
            $options->ControllersDirectory = LauncherProperties::getRootDirectory() . '/' . trim($options->ControllersDirectory);
        }

        if (!is_dir($options->ViewsDirectory)) {
            $options->ViewsDirectory = LauncherProperties::getRootDirectory() . '/' . trim($options->ViewsDirectory);
        }

        $services->addSingleton(ControllerOptions::class, $options);
    }

    public static function addAntiforgery(IServiceCollection $services, Closure $configuration = null)
    {
        $options = new AntiforgeryOptions();
        if ($configuration) {
            $configuration($options);
        }

        $services->addSingleton(IAntiforgery::class, fn (): IAntiforgery => new Antiforgery($options));
    }

    public static function addAuthentication(IServiceCollection $services, Closure $configuration)
    {
        $services->addSingleton(IAuthentication::class, function ($provider) use ($configuration): Authentication {
            $builder = new AuthenticationBuilder($provider->getService(HttpContext::class));
            $configuration($builder);
            return $builder->build();
        });
    }

    public static function addAuthorization(IServiceCollection $services, Closure $configuration = null)
    {
        $options = new AuthorizationOptions();
        if ($configuration) {
            $configuration($options);
        }

        $services->addSingleton(IAuthorization::class, fn (): Authorization => new Authorization($options));
    }

    public static function addDbConnection(IServiceCollection $services, string $datasource, string $username = "", string $password = "")
    {
        $services->addSingleton(DbConnection::class, fn (): DbConnection => new DbConnection($datasource, $username, $password));
    }

    public static function addEntityContext(IServiceCollection $services, string $contextType, Closure $configuration = null)
    {
        $options = new EntityOptions();
        if ($configuration) {
            $configuration($options);
        }

        $services->addSingleton($contextType, fn (): EntityContext => new $contextType($options));
        $services->addSingleton(EntityContext::class, fn ($provider): EntityContext => $provider->getService($contextType));
    }

    public static function addIdentity(IServiceCollection $services, Closure $configuration = null)
    {
        $identityOptions = new IdentityOptions;
        if ($configuration) {
            $configuration($identityOptions);
        }

        $services->addSingleton(IdentityContext::class, function ($provider) use ($services, $identityOptions): IdentityContext {
            if (!$provider->contains(Authentication::class)) {
                self::addAuthentication($services);
            }

            $httpContext = $provider->getService(HttpContext::class);
            $entityContext = $provider->getService($identityOptions->ContextType);
            return new IdentityContext($httpContext, $entityContext, $identityOptions);
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
