<?php

declare(strict_types=1);

namespace Interfaces\Http\Middleware;

use App\Domain\ValueObjects\Uuid;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Reflector;
use ReflectionParameter;

class SubstituteUuids
{
    public function handle(Request $request, Closure $next)
    {
        // Getting a route object from current request
        $route = $request->route();

        // Filter only parameters type-hinted with Ramsey\Uuid\UuidInterface
        /** @var ReflectionParameter[] $parameters */
        $parameters = array_filter($route->signatureParameters(), function ($p) {
            return Reflector::getParameterClassName($p) === Uuid::class;
        });

        foreach ($parameters as $parameter) {
            // Getting parameter value by parameter name (uuid string)
            $uuid = $route->parameter($parameter->getName());

            // Replace uuid string with Uuid object
            $route->setParameter($parameter->getName(), Uuid::fromString($uuid));
        }

        return $next($request);
    }
}
