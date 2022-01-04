<?php
namespace Venom\Routing;

use Attribute;
use \RuntimeException;
use Venom\Venom;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Route
{
    const GET = "GET";
    const POST = "POST";
    const PUT = "PUT";
    const DELETE = "DELETE";

    private function __construct(?string $url = null, ?string $method = self::GET, ?string $router = Router::DEFAULT_ROUTER)
    {
    }

    public static function create($routeClass, $arguments = [], $parameterCount = 0, $methodName = ''): ?IRoute
    {
        if (count($arguments) === 0) {
            if (method_exists($routeClass, '_getRoutes')) {
                return new IRoute($routeClass, (new $routeClass())->_getRoutes());
            }
            return null;
        }
        $router = $arguments[2] ?? Router::DEFAULT_ROUTER;
        $method = $arguments[1] ?? self::GET;
        $url = $arguments[0];
        $routerInstance = Venom::get()->getRouter($router);
        if (!$routerInstance) {
            throw new RuntimeException("Invalid Router: \"$router\" found");
        }
        $route = $routerInstance->getOrCreate($url, $routeClass);
        $key = $parameterCount == 0 ? '*' : $parameterCount;
        $route->addRoute($key, $method, $methodName);
        return $route;
    }
}
