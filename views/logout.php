<?php
include_once './src/includes.php';

AuthUtils::unauthorizeUser();

header('Location: /');
die();