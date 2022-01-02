<?php


namespace Venom\Helper;


class AdminHelper
{
    public static function sendResponse($content, string $component = '', bool $shouldReload = false, $extra = false)
    {
        $response = [
            'content' => $content,
            'component' => $component
        ];

        if ($shouldReload) {
            $response['reload'] = true;
        }
        if ($extra) {
            $response['extra'] = $extra;
        }
        echo json_encode($response);
        die();
    }

    public static function sendStatus(bool $isSuccess, string $message = "")
    {
        if ($message == "") {
            $message = $isSuccess ? "Operation Success" : "Operation failed";
        }
        echo json_encode([
            "status" => $isSuccess ? 'success' : 'failed',
            "message" => $message
        ]);
        die();
    }
}