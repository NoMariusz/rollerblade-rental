<?php

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/src/constants.php';
include_once $root . '/src/credentials.php';
include_once $root . '/src/shared/CommunicationUtils.php';

include_once $root . '/src/auth/AuthLevels.php';
include_once $root . '/src/auth/AuthUtils.php';

include_once $root . '/src/shared/DbManager.php';

include_once $root . '/src/shared/BaseController.php';
include_once $root . '/src/shared/ViewsUtils.php';