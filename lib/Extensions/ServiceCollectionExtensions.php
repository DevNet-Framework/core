<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Extensions;

use DevNet\Common\Dependency\IServiceCollection;
use DevNet\Common\Logging\ILoggerFactory;
use DevNet\Common\Logging\LoggerFactory;
use DevNet\System\Database\DbConnection;
use DevNet\Entity\EntityContext;
use DevNet\Entity\EntityOptions;
use DevNet\Web\Http\Message\HttpContext;
use DevNet\Web\Http\Client\HttpClient;
use DevNet\Web\Http\Client\HttpClientOptions;
use DevNet\Web\Security\Tokens\Csrf\IAntiforgery;
use DevNet\Web\Security\Tokens\Csrf\Antiforgery;
use DevNet\Web\Security\Tokens\Csrf\AntiforgeryOptions;
use DevNet\Web\Security\Authentication\Authentication;
use DevNet\Web\Security\Authentication\AuthenticationBuilder;
use DevNet\Web\Security\Authentication\IAuthentication;
use DevNet\Web\Security\Authorization\Authorization;
use DevNet\Web\Security\Authorization\AuthorizationOptions;
use DevNet\Web\Security\Authorization\IAuthorization;
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

    public static function addAntiforgery(IServiceCollection $services, Closure $configure = null)
    {
        $options = new AntiforgeryOptions();
        if ($configure) {
            $configure($options);
        }

        $services->addSingleton(IAntiforgery::class, fn (): IAntiforgery => new Antiforgery($options));
    }

    public static function addAuthentication(IServiceCollection $services, Closure $configure)
    {
        $services->addSingleton(IAuthentication::class, function ($provider) use ($configure): Authentication {
            $builder = new AuthenticationBuilder($provider->getService(HttpContext::class));
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
