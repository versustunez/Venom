<?php


namespace Venom\Routing;


use RuntimeException;
use Venom\Entities\RoleEntity;
use Venom\Security\Security;
use Venom\Venom;

class Route
{
    const GET = "GET";
    const POST = "POST";
    const PUT = "PUT";
    const DELETE = "DELETE";

    public string $url;
    public array $routes = [];
    public int $maxParameters = 0;
    public string $module = "unknown";
    public bool $isSecure = false;
    public Venom $venom;

    public function __construct(public string $controller, array $config)
    {
        $count = count($config);
        if ($count === 0) {
            throw new RuntimeException("Route: \"$controller\" no valid Routes Found!");
        }
        $count -= isset($config["*"]) ? 1 : 0;
        $this->maxParameters = $count;
        $this->routes = $config;
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
}