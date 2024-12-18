<?php

include_once './src/includes.php';

class DeleteUserController extends BaseController
{
    public function __construct()
    {
        parent::__construct(AuthLevels::Admin, 'DELETE');
    }

    public function handleRequest()
    {
        $userId = $_GET['id'] ?? null;

        // Validate input
        if (!$userId) {
            $this->sendResponse(['error' => 'User ID is required.'], 400);
            exit;
        }

        $this->dbManager->begin_transaction();

        try {
            // Check if user exists
            if (!$this->userExists($userId)) {
                $this->sendResponse(['error' => 'User not found.'], 404);
                exit;
            }

            // Delete the user
            if (!$this->deleteUser($userId)) {
                $this->sendResponse(['error' => 'Failed to delete user.'], 500);
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
        $this->sendResponse(['message' => 'User deleted successfully.']);
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

    private function deleteUser($userId)
    {
        $query = "DELETE FROM users WHERE id = :user_id";
        $bindParams = [
            ['key' => ':user_id', 'value' => $userId, 'type' => PDO::PARAM_INT],
        ];

        return $this->dbManager->make_safe_no_result_query($query, $bindParams);
    }
}

$controller = new DeleteUserController();
$controller->process();
