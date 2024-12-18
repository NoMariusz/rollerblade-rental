<?php

$get_routes = [
    '/' => './views/home/index.php',
    '/home' => './views/home/index.php',
    '/contact' => './views/contact/index.php',
    '/rollerblades' => './views/rollerblades/index.php',
    '/rollerblade' => './views/rollerblade/index.php',
    '/rentals/all' => './views/rentals/all/index.php',
    '/rentals' => './views/rentals/index.php',
    '/users' => './views/users/index.php',
    '/login' => './views/login/index.html',
    '/logout' => './views/logout.php',
    '/register' => './views/register/index.html',
    '/403' => './views/403/index.html',
    '/ratings' => './views/getHomepageRatings.php',
];

$non_get_routes = [
    'POST' => [
        '/login' => './controllers/LoginController.php',
        '/register' => './controllers/RegisterController.php',
        '/rent' => './controllers/RentalController.php',
    ],
    'PATCH' => [
        '/rental/status' => './controllers/RentalStatusChangeController.php',
        '/user/role' => './controllers/UserRoleChangeController.php',
        '/user/password' => './controllers/ChangeUserPasswordController.php',
    ],
    'DELETE' => [
        '/rental/status' => './controllers/RentalStatusChangeController.php',
        '/user' => './controllers/DeleteUserController.php',
    ],
];

class Router
{
    static function handle(string $path, string $method)
    {
        if ($method === 'GET') {
            return self::handleGet($path);
        }

        if (array_key_exists($method, $GLOBALS['non_get_routes'])) {
            return self::handleJsonBasedMethods($path, $GLOBALS['non_get_routes'][$method]);
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

    static function handleJsonBasedMethods(string $path, $controllersMap)
    {
        if (array_key_exists($path, $controllersMap)) {
            return include_once $controllersMap[$path];
        }

        http_response_code(404);
        return json_encode(['error' => '404', 'message' => 'Not Found']);
    }
}