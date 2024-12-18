<?php

include_once './src/includes.php';

class UserRoleChangeController extends BaseController
{
    public function __construct()
    {
        parent::__construct(AuthLevels::Admin, 'PATCH');
    }

    public function handleRequest()
    {
        $userId = $_GET['userId'] ?? null;
        $newRoleId = $_GET['roleId'] ?? null;

        // Validate input
        if (!$userId || !$newRoleId) {
            $this->sendResponse(['error' => 'User ID and role are required.'], 400);
            exit;
        }

        $this->dbManager->begin_transaction();

        try {
            // Check if user exists
            if (!$this->userExists($userId)) {
                $this->sendResponse(['error' => 'User not found.'], 404);
                exit;
            }

            // Update the user's role
            if (!$this->updateUserRole($userId, $newRoleId)) {
                $this->sendResponse(['error' => 'Failed to update user role.'], 500);
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
        $this->sendResponse(['message' => 'User role updated successfully.']);
    }

    private function userExists($userId)
    {
        $query = "SELECT 1 FROM users WHERE id = :user_id";
        $bindParams = [
            ['key' => ':user_id', 'value' => $userId, 'type' => PDO::PARAM_INT],
        ];

        $result = $this->dbManager->make_safe_query($query, $bindParams);
        return !empty($result);
    }


    private function updateUserRole($userId, $newRoleId)
    {
        $query = "UPDATE users 
                  SET role_id = :role_id 
                  WHERE id = :user_id";

        $bindParams = [
            ['key' => ':role_id', 'value' => $newRoleId, 'type' => PDO::PARAM_INT],
            ['key' => ':user_id', 'value' => $userId, 'type' => PDO::PARAM_INT],
        ];

        return $this->dbManager->make_safe_no_result_query($query, $bindParams);
    }
}

$controller = new UserRoleChangeController();
$controller->process();
