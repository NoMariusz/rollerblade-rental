<?php
include_once './src/Router.php';

// ensure that session is started
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

Router::handle(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), $_SERVER['REQUEST_METHOD']);
