<?php

$get_routes = [
    '/' => './views/home.php',
    '/home' => './views/home.php',
    '/login' => './views/login.php',
    '/ratings' => './views/getHomepageRatings.php',
];

$post_routes = [];

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
        if (array_key_exists($path, $GLOBALS['get_routes'])) {
            return include_once $GLOBALS['get_routes'][$path];
        }

        return include_once './views/404/index.html';
    }

    static function handlePost(string $path)
    {
        if (array_key_exists($path, $GLOBALS['post_routes'])) {
            return include_once $GLOBALS['post_routes'][$path];
        }

        http_response_code(404);
        return json_encode(['error' => '404', 'message' => 'Not Found']);
    }
}