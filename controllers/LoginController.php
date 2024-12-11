<?php

include_once './src/includes.php';

class LoginController extends BaseController
{
    public function handleRequest()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        // Validate the request payload
        if (isset($input['username'], $input['password'])) {
            $username = trim($input['username']);
            $password = trim($input['password']);

            $this->processLogin($username, $password);
        } else {
            $this->sendResponse(['error' => 'Invalid input'], 400);
        }
    }

    private function processLogin($username, $password)
    {
        try {
            // Fetch user data by username
            $user = $this->getUserDataByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                // Password matches, login successful
                AuthUtils::authorizeUser($user['username'], $user['role']);
                $this->sendResponse(['message' => 'Login successful']);
            } else {
                // Invalid credentials
                $this->sendResponse(['error' => 'Bad username or password'], 401);
            }
        } catch (Exception $e) {
            // Handle any database or unexpected errors
            $this->sendResponse(['error' => 'An error occurred. Please try again later.'], 500);
        }
    }

    private function getUserDataByUsername($username)
    {
        $query = 'SELECT u.id, username, password, r.name as role FROM users u inner join roles r on role_id = r.id WHERE username = :username';

        $res = $this->dbManager->make_safe_query(
            $query,
            [['key' => ':username', 'type' => PDO::PARAM_STR, 'value' => $username]]
        );

        return $res ? $res[0] : false;
    }
}

// Instantiate and handle the login request
$loginController = new LoginController();
$loginController->process();

