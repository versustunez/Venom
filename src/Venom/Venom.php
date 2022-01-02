<?php


namespace Venom;


use Venom\Admin\AdminController;
use Venom\Admin\AdminRouterInit;
use Venom\Core\ArgumentHandler;
use Venom\Core\Config;
use Venom\Core\Module;
use Venom\Core\ModuleLoader;
use Venom\Core\Registry;
use Venom\Exceptions\ExceptionHandler;
use Venom\Helper\ErrorHandler;
use Venom\Helper\URLHelper;
use Venom\Routing\Router;
use Venom\Views\Asset;
use Venom\Views\RenderController;
use Venom\Views\VenomRenderer;

class Venom
{
    private VenomRenderer $renderer;
    private array $controllers = [];
    private array $modules = [];
    private array $routers = [];

    public function __construct()
    {
        ExceptionHandler::setExceptionHandler();
        $this->renderer = new VenomRenderer($this);
        $this->routers[Router::ADMIN_ROUTER] = new Router(Router::ADMIN_ROUTER, 1.0, '/admin/api');
        Asset::get()->setRenderer($this->renderer);
    }

    public function inject(): void
    {
        $this->run();
    }

    public function run(): void
    {
        $arguments = ArgumentHandler::get();
        $arguments->setItem(ErrorHandler::ERROR_KEY, false);
        $config = Config::getInstance();
        if ($config->isAdmin()) {
            $this->initAdmin();
        }
        // we need to load the current controller and the current start template.
        // after this we can start the renderer
        if ($config->isRouterEnabled() || $config->isAdmin()) {
            $status = $this->useRouter();
            if ($status['found']) {
                if ($status['status']) {
                    exit(0);
                }
                ErrorHandler::setFatalError();
            }
        }
        $registry = Registry::getInstance();
        $registry->getLang()->initLang();
        // if site is errored then dont load via SEO
        if (!$config->isAdmin() && !$arguments->getItem(ErrorHandler::ERROR_KEY)) {
            $registry->getSeo()->loadSite();
        }
        $this->renderer->init($this->findController());
        $this->renderer->render();
    }

    public function initAdmin(): void
    {
        $this->controllers['adminCtrl'] = AdminController::class;
        AdminRouterInit::registerAdminRouters($this);
        ArgumentHandler::get()->setItem('cl', 'adminCtrl');
    }

    private function useRouter(): array
    {
        $url = URLHelper::getInstance()->getUrl();
        $isAdmin = Config::getInstance()->isAdmin();
        /** @var Router $router */
        foreach ($this->routers as $key => $router) {
            if ($isAdmin && $key !== Router::ADMIN_ROUTER) {
                continue;
            }
            if (!$isAdmin && $key === Router::ADMIN_ROUTER) {
                continue;
            }
            $route = $router->findRoute($url, $_SERVER['REQUEST_METHOD']);
            $status = $router->tryFunctionCall($route);
            if ($route !== null) {
                return ['found' => true, 'status' => $status];
            }
        }
        return ['found' => false, 'status' => true];
    }

    private function findController(): ?RenderController
    {
        $cl = ArgumentHandler::get()->getItem('cl');
        if ($cl !== null && isset($this->controllers[$cl])) {
            return $this->loadController($this->controllers[$cl]);
        }
        return null;
    }

    public function loadController($controllerClass): ?RenderController
    {
        $controller = new $controllerClass;
        if ($controller instanceof RenderController && $controller->register()) {
            return $controller;
        }
        return null;
    }

    public function initModules(array $modules): void
    {
        if (Config::getInstance()->isAdmin()) {
            $modules = array_merge(ModuleLoader::getModules(), $modules);
        }
        foreach ($modules as $module) {
            ModuleLoader::loadModule($module, $this);
        }
    }

    public function addControllers(array $controllers): void
    {
        $this->controllers = array_merge($this->controllers, $controllers);
    }

    public function addRouter(Router $router): void
    {
        $this->routers[$router->getId()] = $router;
    }

    public function getRouter(string $router): ?Router
    {
        return $this->routers[$router];
    }

    public function registerModule(array $module)
    {
        if (ModuleLoader::initModule($module, $this)) {
            $this->modules[$module[Module::NAME]] = $module;
        }
    }
}