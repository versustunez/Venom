<?php


namespace Venom\Exceptions;


use Exception;
use Throwable;

class ExceptionHandler
{
    private string $logFile = __DIR__ . '/../../../logs/Exception.log';
    private $file;

    public function __construct()
    {
        try {
            $this->file = fopen($this->logFile, 'ab+');
        } catch (Throwable $ex) {
            $this->file = null;
        }
    }

    public static function setExceptionHandler(): void
    {
        set_exception_handler(array(__CLASS__, 'handleException'));
    }

    public static function handleException(Throwable $ex): void
    {
        $handler = new ExceptionHandler();
        $handler->writeException($ex);
        echo "<h1>Critical Exception</h1><p>Pls visit Log to see what happened</p>";
        exit(255);
    }

    public function writeException(Throwable $ex): void
    {
        if ($this->file !== null) {
            try {
                $trace = "=====[FATAL ERROR]=====\n" . $ex->getMessage() . "\n" . $ex->getTraceAsString() . "\n=====[FATAL ERROR END]=====\n";
                fwrite($this->file, $trace);
                fclose($this->file);
            } catch (Exception $e) {
                trigger_error("cannot write Exception file!");
            }
        }
    }
}
