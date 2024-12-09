<?php

abstract class BaseController
{
    protected $dbManager;

    public function __construct()
    {
        $this->dbManager = new DbManager(); // Initialize the database manager
    }

    function process()
    {
        $this->checkIfMethodIsPost();
        $this->handleRequest();
    }

    abstract function handleRequest();

    private function checkIfMethodIsPost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(['error' => 'Method not allowed'], 405);
        }
    }

    protected function sendResponse($data, $statusCode = 200)
    {
        CommunicationUtils::sendResponse($data, $statusCode);
    }
}
