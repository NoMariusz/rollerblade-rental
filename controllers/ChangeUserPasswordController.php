<?php

include_once './src/includes.php';

class ChangeUserPasswordController extends BaseController
{
    public function __construct()
    {
        parent::__construct(AuthLevels::Admin, 'PATCH');
    }

    public function handleRequest()
    {
        $jsonData = $this->getJSONInput();

        $userId = $jsonData['userId'] ?? null;
        $newPassword = $jsonData['newPassword'] ?? null;

        // Validate input
        if (!$userId || !$newPassword) {
            $this->sendResponse(['error' => 'User ID and new password are required.'], 400);
            exit;
        }

        if (!$this->isPasswordValid($newPassword)) {
            $this->sendResponse(['error' => 'Password does not meet the minimum security requirements.'], 400);
            exit;
        }

        $this->dbManager->begin_transaction();

        try {
            // Check if user exists
            if (!$this->userExists($userId)) {
                $this->sendResponse(['error' => 'User not found.'], 404);
                exit;
            }

            // Change the user's password
            if (!$this->updatePassword($userId, $newPassword)) {
                $this->sendResponse(['error' => 'Failed to update password.'], 500);
                exit;
            }
        } catch (Exception $e) {
            $this->dbManager->rollback_transaction();
            CommunicationUtils::sendResponseInDebug($e->getMessage(), 500);
            $this->sendResponse(['error' => 'An error occurred. Please try again later.'], 500);
            exit;
        }

        $this->dbManager->commit_transaction();

        // Success response
        $this->sendResponse(['message' => 'Password updated successfully.']);
    }

    /**
     * Check if the user exists in the database.
     */
    private function userExists($userId)
    {
        $query = "SELECT 1 FROM users WHERE id = :user_id";
        $bindParams = [
            ['key' => ':user_id', 'value' => $userId, 'type' => PDO::PARAM_INT],
        ];

        $result = $this->dbManager->make_safe_query($query, $bindParams);
        return !empty($result);
    }

    /**
     * Update the user's password in the database.
     */
    private function updatePassword($userId, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        $query = "UPDATE users 
                  SET password = :password 
                  WHERE id = :user_id";

        $bindParams = [
            ['key' => ':password', 'value' => $hashedPassword, 'type' => PDO::PARAM_STR],
            ['key' => ':user_id', 'value' => $userId, 'type' => PDO::PARAM_INT],
        ];

        return $this->dbManager->make_safe_no_result_query($query, $bindParams);
    }

    private function isPasswordValid($password)
    {
        // in future can also validate password strength
        return is_string($password) && strlen($password) >= 0;
    }
}

$controller = new ChangeUserPasswordController();
$controller->process();
