<?php

abstract class BaseController
{
    protected $dbManager;

    public function __construct(int $priviledgesLevel = AuthLevels::None, string $method = 'POST')
    {
        if (!AuthUtils::hasPriviledges($priviledgesLevel)) {
            $this->sendResponse(['error' => 'Unauthorized'], 401);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== $method) {
            $this->sendResponse(['error' => 'Method not allowed'], 405);
            return;
        }

        $this->dbManager = new DbManager(); // Initialize the database manager
    }

    function process()
    {
        $this->handleRequest();
    }

    abstract function handleRequest();

    protected function sendResponse($data, $statusCode = 200)
    {
        CommunicationUtils::sendResponse($data, $statusCode);
    }

    protected function getJSONInput()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
}
