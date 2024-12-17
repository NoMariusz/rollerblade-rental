<?php

$get_routes = [
    '/' => './views/home/index.php',
    '/home' => './views/home/index.php',
    '/contact' => './views/contact/index.php',
    '/rollerblades' => './views/rollerblades/index.php',
    '/rollerblade' => './views/rollerblade/index.php',
    '/rentals/all' => './views/rentals/all/index.php',
    '/rentals' => './views/rentals/index.php',
    '/login' => './views/login/index.html',
    '/logout' => './views/logout.php',
    '/register' => './views/register/index.html',
    '/403' => './views/403/index.html',
    '/ratings' => './views/getHomepageRatings.php',
];

$post_routes = [
    '/login' => './controllers/LoginController.php',
    '/register' => './controllers/RegisterController.php',
    '/rent' => './controllers/RentalController.php',
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