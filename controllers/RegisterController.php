<?php

include_once './src/includes.php';

class RegisterController extends BaseController
{
    public function handleRequest()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        // Validate the request payload
        if (
            isset($input['username'], $input['password'], $input['email'], $input['first-name'], $input['last-name'])
        ) {
            $username = trim($input['username']);
            $password = trim($input['password']);
            $email = trim($input['email']);
            $firstName = trim($input['first-name']);
            $lastName = trim($input['last-name']);

            $this->processRegistration($username, $password, $email, $firstName, $lastName);
        } else {
            $this->sendResponse(['error' => 'Invalid input'], 400);
        }
    }

    private function processRegistration($username, $password, $email, $firstName, $lastName)
    {
        try {
            // Check if the username or email already exists
            if ($this->userExists($username, $email)) {
                $this->sendResponse(['error' => 'Username or email already exists'], 409);
                return;
            }

            // Hash the password for security
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Start a transaction to ensure atomicity
            $this->dbManager->begin_transaction();

            // Insert into users table
            $userId = $this->createUser($username, $hashedPassword);

            // Insert into user_profiles table
            $success = $this->createUserProfile($userId, $firstName, $lastName, $email);
            if (!$success) {
                $this->sendResponse(['message' => 'Cannot create userProfile', 'data' => $success], 500);
                return;
            }

            // Commit the transaction
            $this->dbManager->commit_transaction();

            $this->sendResponse(['message' => 'Registration successful']);
        } catch (Exception $e) {
            // Roll back in case of an error
            $this->dbManager->rollback_transaction();
            $this->sendResponse(['error' => 'An error occurred. Please try again later.'], 500);
        }
    }

    private function userExists($username, $email)
    {
        $query = 'SELECT COUNT(*) as count FROM users inner join user_profiles on users.id = user_id WHERE username = :username OR email = :email';

        $result = $this->dbManager->make_safe_query(
            $query,
            [
                ['key' => ':username', 'type' => PDO::PARAM_STR, 'value' => $username],
                ['key' => ':email', 'type' => PDO::PARAM_STR, 'value' => $email],
            ]
        );

        return $result['count'] > 0;
    }

    private function createUser($username, $password)
    {
        $query = 'INSERT INTO users (username, password, role_id) VALUES (:username, :password, ' . USER_ROLE_ID . ')';

        return $this->dbManager->make_safe_insert_id_query(
            $query,
            [
                ['key' => ':username', 'type' => PDO::PARAM_STR, 'value' => $username],
                ['key' => ':password', 'type' => PDO::PARAM_STR, 'value' => $password],
            ]
        );
    }

    private function createUserProfile($userId, $firstName, $lastName, $email)
    {
        $query = 'INSERT INTO user_profiles (user_id, first_name, last_name, email) VALUES (:user_id, :first_name, :last_name, :email)';

        return $this->dbManager->make_safe_query(
            $query,
            [
                ['key' => ':user_id', 'type' => PDO::PARAM_INT, 'value' => $userId],
                ['key' => ':first_name', 'type' => PDO::PARAM_STR, 'value' => $firstName],
                ['key' => ':last_name', 'type' => PDO::PARAM_STR, 'value' => $lastName],
                ['key' => ':email', 'type' => PDO::PARAM_STR, 'value' => $email],
            ]
        );
    }
}

// Instantiate and handle the registration request
$registerController = new RegisterController();
$registerController->process();
