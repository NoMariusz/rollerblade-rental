<?php

$get_routes = [
    '/' => './views/home/index.html',
    '/home' => './views/home/index.html',
    '/contact' => './views/contact/index.html',
    '/login' => './views/login/index.html',
    '/register' => './views/register/index.html',
    '/ratings' => './views/getHomepageRatings.php',
];

$post_routes = [
    '/login' => './controllers/LoginController.php',
    '/register' => './controllers/RegisterController.php',
];

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