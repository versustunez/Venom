<?php


namespace Venom\Helper;


use Venom\Core\ArgumentHandler;

class ErrorHandler
{
    public const ERROR_KEY = 'errorHandler';

    public static function setFatalError(): void
    {
        self::setError(500);
    }

    public static function setError(int $errorCode): void
    {
        http_response_code($errorCode);
        $handler = ArgumentHandler::get();
        if (!$handler->hasItem('cl')) {
            $handler->setItem('cl', 'error');
            $handler->setItem('fnc', 'handleError');
            $handler->setItem('errorCode', $errorCode);
            $handler->setItem(self::ERROR_KEY, true);
        }
    }

    public static function setNotFound(): void
    {
        self::setError(404);
    }

    public static function setNoContent(): void
    {
        self::setError(204);
    }
}
