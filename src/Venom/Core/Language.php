<?php


namespace Venom\Core;


use RuntimeException;
use Venom\Core\Database\DatabaseHandler;
use Venom\Entities\DatabaseObject;

class Language
{
    private string $language = "";
    private array $languages = [];
    private ?DatabaseObject $defaultLang;

    public function __construct()
    {
        $this->defaultLang = DatabaseHandler::get()->getOne("select language, shortTag from language WHERE isActive = 1 and isDefault = 1", []);
    }

    public function initLang()
    {
        $lang = ArgumentHandler::get()->getItem("lang", $this->defaultLang->shortTag ?? 'de');
        //check if language exists
        $data = DatabaseHandler::get()->getOne("select id from language where shortTag = :shortTag", [
            ':shortTag' => $lang
        ]);

        if (isset($data->id)) {
            $this->language = $lang;
        } else {
            throw new RuntimeException("Language \"$lang\" not found");
        }
    }

    public function registerLang(string $key, array $values)
    {
        $this->languages[$key] = $values;
    }

    public function getCurrentLang()
    {
        return $this->language;
    }

    public function getTranslations()
    {
        return $this->languages[$this->language] ?: [];
    }

    public function getTranslation($key)
    {
        if (!isset($this->languages[$this->language])) {
            return $key;
        }
        return $this->languages[$this->language][$key] ?? $key;
    }
}