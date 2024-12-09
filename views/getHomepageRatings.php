<?php
include_once './src/includes.php';

echo json_encode((new DbManager())->make_query('SELECT * FROM homepage_ratings'));