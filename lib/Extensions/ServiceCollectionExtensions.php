<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Extensions;

use DevNet\Common\Dependency\IServiceCollection;
use DevNet\Common\Logging\ILoggerFactory;
use DevNet\Common\Logging\LoggerFactory;
use DevNet\Entity\EntityContext;
use DevNet\Entity\EntityOptions;
use DevNet\Http\Client\HttpClient;
use DevNet\Http\Client\HttpClientOptions;
use DevNet\Security\Authentication\Authentication;
use DevNet\Security\Authentication\AuthenticationBuilder;
use DevNet\Security\Authentication\IAuthentication;
use DevNet\Security\Authorization\Authorization;
use DevNet\Security\Authorization\AuthorizationOptions;
use DevNet\Security\Authorization\IAuthorization;
use DevNet\Security\Tokens\Csrf\IAntiForgery;
use DevNet\Security\Tokens\Csrf\AntiForgery;
use DevNet\Security\Tokens\Csrf\AntiForgeryOptions;
use DevNet\System\Database\DbConnection;
use Closure;

class ServiceCollectionExtensions
{
    public static function addLogging(IServiceCollection $services, Closure $configure = null)
    {
        $services->addSingleton(ILoggerFactory::class, fn (): ILoggerFactory => LoggerFactory::Create($configure));
    }

    public static function addHttpClient(IServiceCollection $services, Closure $configure = null)
    {
        $options = new HttpClientOptions();
        if ($configure) {
            $configure($options);
        }

        $services->addSingleton(HttpClient::class, fn (): HttpClient => new HttpClient($options));
    }

    public static function addAntiForgery(IServiceCollection $services, Closure $configure = null)
    {
        $options = new AntiForgeryOptions();
        if ($configure) {
            $configure($options);
        }

        $services->addSingleton(IAntiForgery::class, fn (): IAntiForgery => new AntiForgery($options));
    }

    public static function addAuthentication(IServiceCollection $services, Closure $configure)
    {
        $services->addSingleton(IAuthentication::class, function () use ($configure): Authentication {
            $builder = new AuthenticationBuilder();
            $configure($builder);
            return $builder->build();
        });
    }

    public static function addAuthorization(IServiceCollection $services, Closure $configure = null)
    {
        $options = new AuthorizationOptions();
        if ($configure) {
            $configure($options);
        }

        $services->addSingleton(IAuthorization::class, fn (): Authorization => new Authorization($options));
    }

    public static function addDbConnection(IServiceCollection $services, string $dataSource, string $username = "", string $password = "")
    {
        $services->addSingleton(DbConnection::class, fn (): DbConnection => new DbConnection($dataSource, $username, $password));
    }

    public static function addEntityContext(IServiceCollection $services, string $contextType, Closure $configure = null)
    {
        $options = new EntityOptions();
        if ($configure) {
            $configure($options);
        }

        $services->addSingleton($contextType, fn (): EntityContext => new $contextType($options));
        $services->addSingleton(EntityContext::class, fn ($provider): EntityContext => $provider->getService($contextType));
    }
}
