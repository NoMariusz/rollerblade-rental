<?php
include_once './src/Router.php';

Router::handle($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
