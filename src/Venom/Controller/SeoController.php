<?php


namespace Venom\Controller;


use Venom\Core\ArgumentHandler;
use Venom\Core\Config;
use Venom\Core\Database\DatabaseHandler;
use Venom\Helper\ErrorHandler;
use Venom\Helper\URLHelper;

class SeoController
{

    private bool $shouldUse;
    private $data = null;

    public function __construct()
    {
        $this->shouldUse = Config::getInstance()->getSeoEnabled();
    }

    public function loadSite(): void
    {
        if (!$this->shouldUse) {
            return;
        }
        $url = URLHelper::getInstance()->getUrl();
        $data = DatabaseHandler::get()->getOne("SELECT * FROM seoData WHERE seo = :url", [
            ':url' => $url,
        ]);
        $this->data = $data;
        if ($this->data !== null) {
            parse_str(parse_url($this->data->raw)['query'], $queryItems);
            foreach ($queryItems as $key => $item) {
                ArgumentHandler::get()->setItem($key, $item);
            }
        } else {
            ErrorHandler::setNotFound();
        }
    }

    public function addSite(): void
    {

    }

    public function deleteSite(): void
    {

    }

    public function getData()
    {
        return $this->data;
    }
}