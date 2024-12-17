<?php

include_once './src/includes.php';

class RentalStatusChangeController extends BaseController
{
    public function __construct()
    {
        parent::__construct(AuthLevels::Moderator, 'PATCH');
    }

    public function handleRequest()
    {
        $jsonData = $this->getJSONInput();

        // Retrieve data from the request
        $rentalId = $jsonData['rentalId'] ?? null;
        $newStatusId = $jsonData['statusId'] ?? null;

        // Validate input
        if (!$rentalId || !$newStatusId) {
            $this->sendResponse(['error' => 'Rental ID and status are required.'], 400);
            exit;
        }

        $this->dbManager->begin_transaction();

        try {
            // Check if rental exists
            if (!$this->rentalExists($rentalId)) {
                $this->sendResponse(['error' => 'Rental not found.'], 404);
                exit;
            }

            // Update the rental status
            if (!$this->updateRentalStatus($rentalId, $newStatusId)) {
                $this->sendResponse(['error' => 'Failed to update rental status.'], 500);
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
        $this->sendResponse(['message' => 'Rental status updated successfully.']);
    }

    /**
     * Check if the rental exists in the database.
     */
    private function rentalExists($rentalId)
    {
        $query = "SELECT 1 FROM rentals WHERE id = :rental_id";
        $bindParams = [
            ['key' => ':rental_id', 'value' => $rentalId, 'type' => PDO::PARAM_INT],
        ];

        $result = $this->dbManager->make_safe_query($query, $bindParams);
        return !empty($result);
    }

    /**
     * Update the rental status in the database.
     */
    private function updateRentalStatus($rentalId, $newStatusId)
    {
        $query = "UPDATE rentals 
                  SET status_id = :status_id 
                  WHERE id = :rental_id";

        $bindParams = [
            ['key' => ':status_id', 'value' => $newStatusId, 'type' => PDO::PARAM_INT],
            ['key' => ':rental_id', 'value' => $rentalId, 'type' => PDO::PARAM_INT],
        ];

        return $this->dbManager->make_safe_no_result_query($query, $bindParams);
    }
}

$controller = new RentalStatusChangeController();
$controller->process();
