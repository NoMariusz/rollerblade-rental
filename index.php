<?php
include_once './src/Router.php';
include_once './src/constants.php';

if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// ensure that session is started
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

Router::handle(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), $_SERVER['REQUEST_METHOD']);
