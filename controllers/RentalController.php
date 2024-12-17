<?php

include_once './src/includes.php';

class RentalController extends BaseController
{
    public function __construct()
    {
        parent::__construct(AuthLevels::User);
    }

    public function handleRequest()
    {
        $jsonData = $this->getJSONInput();

        // Retrieve form data
        $rollerbladeId = $jsonData['rollerblade_id'] ?? null;
        $startDate = $jsonData['start_date'] ?? null;
        $endDate = $jsonData['end_date'] ?? null;

        // Validate input
        if (!$rollerbladeId || !$startDate || !$endDate) {
            $this->sendResponse(['error' => 'All fields are required.'], 400);
            exit;
        }

        if (strtotime($endDate) <= strtotime($startDate)) {
            $this->sendResponse(['error' => 'End date must be after start date.'], 400);
            exit;
        }

        $this->dbManager->begin_transaction();

        try {
            // Check rollerblade availability
            $available = $this->isRollerbladeAvailable($rollerbladeId, $startDate, $endDate);

            if (!$available) {
                $this->sendResponse(['error' => 'The selected rollerblade is not available for the given dates'], 400);
                exit;
            }

            // Add new rental
            $userId = AuthUtils::getUserId();
            if (!$this->addRental($userId, $rollerbladeId, $startDate, $endDate)) {
                $this->sendResponse(['error' => 'Failed to create rental. Please try again.'], 400);
                exit;
            }
        } catch (Exception $e) {
            $this->dbManager->rollback_transaction();
            CommunicationUtils::sendResponseInDebug($e->getMessage(), 500);
            $this->sendResponse(['error' => 'An error occurred. Please try again later.'], 400);
            exit;
        }

        $this->dbManager->commit_transaction();

        $this->sendResponse(['message' => 'Rental successful']);
    }

    /**
     * Check if the rollerblade is available between start and end dates.
     */
    private function isRollerbladeAvailable($rollerbladeId, $startDate, $endDate)
    {
        $query = "SELECT * FROM all_available_rollerblades_between_dates(:start_date, :end_date)
              WHERE rollerblade_id = :rollerblade_id";

        $bindParams = [
            ['key' => ':start_date', 'value' => $startDate, 'type' => PDO::PARAM_STR],
            ['key' => ':end_date', 'value' => $endDate, 'type' => PDO::PARAM_STR],
            ['key' => ':rollerblade_id', 'value' => $rollerbladeId, 'type' => PDO::PARAM_INT],
        ];

        $result = $this->dbManager->make_safe_query($query, $bindParams);

        return !empty($result); // Returns true if any results are found
    }

    /**
     * Add a new rental to the database.
     */
    private function addRental($userId, $rollerbladeId, $startDate, $endDate)
    {
        $statusId = $this->dbManager->make_query("SELECT id FROM rental_statuses WHERE name = '" . INITIAL_STATUS_NAME . "'")[0]['id'];

        $query = "INSERT INTO rentals (user_id, rollerblade_id, start_date, end_date, status_id, notes)
              VALUES (:user_id, :rollerblade_id, :start_date, :end_date, $statusId, '')";

        $bindParams = [
            ['key' => ':user_id', 'value' => $userId, 'type' => PDO::PARAM_INT],
            ['key' => ':rollerblade_id', 'value' => $rollerbladeId, 'type' => PDO::PARAM_INT],
            ['key' => ':start_date', 'value' => $startDate, 'type' => PDO::PARAM_STR],
            ['key' => ':end_date', 'value' => $endDate, 'type' => PDO::PARAM_STR],
        ];

        return $this->dbManager->make_safe_no_result_query($query, $bindParams);
    }
}

$controller = new RentalController();
$controller->process();