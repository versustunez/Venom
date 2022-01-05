<?php

namespace Venom\Core;

use Venom\Core\Database\DatabaseHandler;
use Venom\Entities\ConfigObject;

class Config
{
    private static ?Config $instance = null;
    private bool $isWriteable = true;
    private float $version = 1.0;
    private ConfigObject $renderer;
    private ConfigObject $security;
    private bool $maintenance = false;
    private bool $devMode = false;
    private bool $isAdmin = false;
    private string $baseUrl = '';
    private bool $seoMode = false;
    private bool $useRouter = false;

    private function __construct()
    {
        $this->renderer = new ConfigObject();
        $this->security = new ConfigObject();
    }

    public static function get(): Config
    {
        if (self::$instance === null) {
            self::$instance = new Config();
        }
        return self::$instance;
    }

    public function setDatabase(array $array): void
    {
        if ($this->isWriteable) {
            DatabaseHandler::get()->init($array);
        } else {
            trigger_error('try to write closed config!');
        }
    }

    public function setRender(array $array): void
    {
        $this->set('renderer', $array);
    }

    public function set(string $variable, $value): void
    {
        if (!$this->isWriteable) {
            trigger_error('try to write closed config!');
            return;
        }
        if ($this->$variable instanceof ConfigObject) {
            $this->$variable->setData($value);
        } else {
            $this->$variable = $value;
        }
    }

    public function setMaintainMode(bool $mode): void
    {
        $this->set('maintenance', $mode);
    }

    public function getVersion(): float
    {
        return $this->version;
    }

    public function setVersion(float $param): void
    {
        $this->set('version', $param);
    }

    public function getRenderer(): ConfigObject
    {
        return $this->renderer;
    }

    public function isMaintenance(): bool
    {
        return $this->maintenance;
    }

    public function isDevMode(): bool
    {
        return $this->devMode;
    }

    public function setDevMode(bool $mode): void
    {
        $this->set('devMode', $mode);
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    /**
     * @param bool $isAdmin
     */
    public function setIsAdmin(bool $isAdmin): void
    {
        $this->set('isAdmin', $isAdmin);
    }

    public function setSeoMode(bool $mode): void
    {
        $this->set('seoMode', $mode);
    }

    public function getSeoEnabled(): bool
    {
        return $this->seoMode;
    }

    /**
     * @return bool
     */
    public function isClosed(): bool
    {
        return !$this->isWriteable;
    }

    /**
     * function to close the write mode... this make sure after the config is init no other tool can write to it!
     */
    public function close(): void
    {
        $this->isWriteable = false;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function setBaseUrl(string $url): void
    {
        $this->set('baseUrl', $url);
    }

    public function getSecurity(): ConfigObject
    {
        return $this->security;
    }

    public function setSecurity(array $security): void
    {
        $this->set('security', $security);
    }

    public function setEnableRouter(bool $value): void
    {
        $this->set('useRouter', $value);
    }

    public function isRouterEnabled(): bool
    {
        return $this->useRouter;
    }

}
