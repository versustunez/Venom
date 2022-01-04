<?php

namespace Venom\Routing;

use RuntimeException;
use Venom\Entities\RoleEntity;
use Venom\Security\Security;
use Venom\Venom;

class IRoute
{

    public array $routes = [];
    public int $maxParameters = 0;
    public string $module = "unknown";
    public bool $isSecure = false;
    public Venom $venom;

    public function __construct(public string $controller, array $routes, bool $createEmpty = false)
    {
        if ($createEmpty) {
            return;
        }
        $count = count($routes);
        if ($count === 0) {
            throw new RuntimeException("Route: \"$controller\" no valid Routes Found!");
        }
        $count -= isset($routes["*"]) ? 1 : 0;
        $this->maxParameters = $count;
        $this->routes = $routes;
    }

    public function getDefinitions($method, mixed $subRoute): ?array
    {
        if ($this->isSecure && !Security::get()->hasPermission($this->module, $method === Route::GET ? RoleEntity::TYPE_READ : RoleEntity::TYPE_WRITE)) {
            return null;
        }
        if (isset($this->routes[$subRoute]) && isset($this->routes[$subRoute][$method])) {
            return [
                "cl" => $this->controller,
                "fnc" => $this->routes[$subRoute][$method]
            ];
        }
        return null;
    }

    public function setController(string $controller): void
    {
        $this->controller = $controller;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function addRoute(string $key, string $method, string $methodName)
    {
        if (!isset($routes[$key])) {
            $this->routes[$key] = [];
        }
        $this->routes[$key][$method] = $methodName;
        $count = count($this->routes) - isset($this->routes["*"]) ? 1 : 0;
        $this->maxParameters = $count;
    }
}
