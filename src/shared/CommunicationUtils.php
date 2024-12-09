<?php

class CommunicationUtils
{
    static function sendResponseInDebug($data, $statusCode = 200)
    {
        if (DEBUG) {
            self::sendResponse(["debugData" => $data, "backtrace" => debug_backtrace()], $statusCode);
        }
    }
    static function sendResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
