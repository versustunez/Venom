<?php


namespace Venom\Routing;


use Exception;
use Venom\Exceptions\ExceptionHandler;

class Router
{
    public const DEFAULT_ROUTER = 'defaultRouter';
    public const ADMIN_ROUTER = 'adminRouter';
    protected string $id = 'defaultRouter';
    protected int $version;
    protected string $prefix = '';
    protected array $routes = [];

    public function __construct(string $id, int $version, string $prefix = '')
    {
        $this->id = $id;
        $this->version = $version;
        $this->prefix = $prefix;
    }

    public function addRoutes(array $routes): void
    {
        $this->routes = array_merge($this->routes, $routes);
    }

    public function addRoute(string $path, Route $route): void
    {
        $this->routes[$path] = $route;
    }

    /*
     * return matched route or null
     */
    public function findRoute($url, $method): ?array
    {
        $url = $this->removeIfFirst($url, $this->prefix);
        $url = $this->removeTrailingSlash($url);
        // check if full match... this can easily done if the url isset select the empty!
        $method = strtoupper($method);
        $route = $this->getRouteByName($url, $method);
        if ($route !== null) {
            return $route;
        }
        $url = $this->removeIfFirst($url, '/');
        $baseRoute = $this->getNearestBaseRoute(explode("/", $url));
        if ($baseRoute !== null) {
            $count = count($baseRoute['params']);
            return $this->getRouteByName($baseRoute['url'], $method, $count, $baseRoute['params']) ?? $this->getRouteByName($baseRoute['url'], $method);
        }
        return null;
    }

    private function removeIfFirst($rawString, $string): bool|string
    {
        if ($string !== '' && str_starts_with($rawString, $string)) {
            return substr($rawString, strlen($string));
        }
        return $rawString;
    }

    private function getRouteByName($url, $method, $subRoute = '*', $params = []): ?array
    {
        if (isset($this->routes[$url])) {
            /** @var Route $route */
            $route = $this->routes[$url];
            $sub = $route->getDefinitions($method, $subRoute);
            if ($sub === null) {
                return null;
            }
            $sub["params"] = array_reverse($params);
            return $sub;
        }
        return null;
    }

    private function getNearestBaseRoute(array $params): ?array
    {
        $count = count($params);
        $baseUrlArray = [];
        $newParams = [];
        foreach ($params as $value) {
            $baseUrlArray[] = $value;
        }
        for ($i = 0; $i < $count; $i++) {
            $newParams[] = array_pop($baseUrlArray);
            $url = '/' . implode('/', $baseUrlArray);
            if (isset($this->routes[$url])) {
                return ['url' => $url, 'params' => $newParams];
            }
        }
        return null;
    }

    public function tryFunctionCall(?array $aRoute): bool
    {
        if ($aRoute === null || empty($aRoute['cl']) || empty($aRoute['fnc']) || !class_exists($aRoute['cl'])) {
            return false;
        }
        $route = new $aRoute['cl']();
        if (!is_callable(array($route, $aRoute['fnc']))) {
            return false;
        }
        try {
            $fnc = $aRoute['fnc'];
            $params = $aRoute['params'] ?? [];
            $route->$fnc(...$params);
            return true;
        } catch (Exception $ex) {
            ExceptionHandler::handleException($ex);
            return false;
        }
    }

    private function removeTrailingSlash(string $rawString): bool|string
    {
        $len = strlen($rawString);
        return $rawString[$len - 1] === '/' ? substr($rawString, 0, strlen($rawString) - 1) : $rawString;
    }

    public function getId(): string
    {
        return $this->id;
    }
}