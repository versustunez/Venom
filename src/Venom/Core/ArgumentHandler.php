<?php


namespace Venom\Core;


class ArgumentHandler
{
    public static ?ArgumentHandler $instance = null;
    private array $arguments = [];
    private array $post = [];
    private array $put = [];

    public function __construct()
    {
        foreach ($_GET as $key => $item) {
            $this->arguments[htmlspecialchars($key)] = htmlspecialchars($item);
        }
        foreach ($_POST as $key => $item) {
            $this->arguments[htmlspecialchars($key)] = htmlspecialchars($item);
            $this->post[htmlspecialchars($key)] = htmlspecialchars($item);
        }
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            parse_str(file_get_contents("php://input"),$_PUT);
            foreach ($_PUT as $key => $item) {
                $this->arguments[htmlspecialchars($key)] = htmlspecialchars($item);
                $this->put[htmlspecialchars($key)] = htmlspecialchars($item);
            }
        }
    }

    public static function get(): ArgumentHandler
    {
        if (self::$instance === null) {
            self::$instance = new ArgumentHandler();
        }
        return self::$instance;
    }

    public function getItem(string $key, $default = null)
    {
        return $this->arguments[$key] ?? $default;
    }

    public function setItem(string $key, $item): void
    {
        $this->arguments[$key] = $item;
    }

    public function hasItem(string $key): bool
    {
        return !empty($this->arguments[$key]);
    }

    public function getPostItem(string $key, $default = null)
    {
        return $this->post[$key] ?? $default;
    }

    public function hasPostItem(string $key): bool
    {
        return !empty($this->post[$key]);
    }

    public function getPutItem(string $key, $default = null)
    {
        return $this->put[$key] ?? $default;
    }

    public function hasPutItem(string $key): bool
    {
        return !empty($this->put[$key]);
    }
}
