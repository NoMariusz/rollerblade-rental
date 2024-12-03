<?php

class Router
{
    static function handle(string $path, string $method)
    {
        switch ($method) {
            case 'GET':
                return self::handleGet($path);
            case 'POST':
                return self::handlePost($path);
            default:
                break;
        }
        http_response_code(405);
        return json_encode(['error' => '405', 'message' => 'Method Not Allowed']);
    }

    static function handleGet(string $path)
    {
        switch ($path) {
            case '/':
            case '/home':
                return include_once './views/home.php';
            case '/login':
                return include_once './views/login.php';

            default:
                break;
        }
        return include_once './views/404/index.html';
    }

    static function handlePost(string $path)
    {
        switch ($path) {
            default:
                break;
        }
        http_response_code(404);
        return json_encode(['error' => '404', 'message' => 'Not Found']);
    }
}