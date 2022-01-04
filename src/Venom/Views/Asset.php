<?php


namespace Venom\Views;


use Venom\Core\Config;

class Asset
{
    public static ?Asset $instance = null;
    private array $jsFiles = [];
    private array $cssFiles = [];
    private VenomRenderer $renderer;

    private function __construct()
    {
    }

    public static function get(): Asset
    {
        if (self::$instance === null) {
            self::$instance = new Asset();
        }
        return self::$instance;
    }

    public function addJS(string $name, string $filepath, $pos = 99999): void
    {
        $this->jsFiles[$name] = [
            'file' => $filepath,
            'pos' => $pos
        ];
    }

    public function addCSS(string $name, string $filepath, $pos = 99999): void
    {
        $this->cssFiles[$name] = [
            'file' => $filepath,
            'pos' => $pos
        ];
    }

    public function getImagePath(string $filepath, bool $useAbsolute = false)
    {
        $config = Config::get();
        $preDir = '/' . $config->getRenderer()->uploadDir;
        if ($useAbsolute) {
            $preDir = $config->getBaseUrl() . $preDir;
        }
        return $preDir . $filepath;
    }

    public function setRenderer(VenomRenderer $renderer): void
    {
        $this->renderer = $renderer;
    }

    //this will output all js files! sorted by position
    public function renderJS(): void
    {
        usort($this->jsFiles, function ($a, $b) {
            return $a['pos'] <=> $b['pos'];
        });
        $theme = $this->getPath('/js/');
        foreach ($this->jsFiles as $key => $file) {
            echo '<script src="' . $theme . $file['file'] . '" id="js-' . $key . '"></script>';
        }
    }

    private function getPath($base): string
    {
        $dir = Config::get()->isAdmin() ? 'admin' : Config::get()->getRenderer()->assetDir;
        $preDir = '/theme/' . $dir . $base;
        $config = Config::get();
        $baseUrl = Config::get()->getBaseUrl();
        if ($baseUrl !== '' && $config->getRenderer()->useStaticUrl) {
            $preDir = Config::get()->getBaseUrl() . $preDir;
        }
        return $preDir;
    }

    public function renderCSS(): void
    {
        usort($this->cssFiles, function ($a, $b) {
            return $a['pos'] <=> $b['pos'];
        });
        $theme = $this->getPath('/css/');
        foreach ($this->cssFiles as $key => $file) {
            echo '<link rel="stylesheet" href="' . $theme . $file['file'] . '" id="css-' . $key . '">';
        }
    }
}
