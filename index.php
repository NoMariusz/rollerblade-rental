<?php
include_once './src/Router.php';

// ensure that session is started
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

Router::handle($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
