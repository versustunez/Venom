<?php


namespace Venom\Helper;


class URLHelper
{
    private static ?URLHelper $instance = null;
    private string $parsedUrl;

    public function __construct()
    {
        $this->parsedUrl = htmlspecialchars(parse_url($_SERVER['REQUEST_URI'])['path']);
    }

    public static function getInstance(): URLHelper
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getUrl(): string
    {
        return $this->parsedUrl;
    }


    public function getUrlForId($id): string
    {
        return '';
    }

    public function getUrlForController($cl): string
    {
        return '';
    }

    public function generateFullUrl($url): string
    {
        return $url;
    }

    public function isAdminUrl(): bool
    {
        return strpos($this->parsedUrl, '/admin') === 0;
    }
}