<?php

define('DB_SERVER', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'test');

$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

