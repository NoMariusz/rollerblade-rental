<?php
include_once './backend/Router.php';

Router::handle($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
