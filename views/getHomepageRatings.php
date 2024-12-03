<?php
include_once './src/includes.php';

echo json_encode(DbManager::make_query('SELECT * FROM homepage_ratings'));