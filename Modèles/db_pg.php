<?php
$host = 'app.garageisep.com';
$port = '5408';
$dbname = 'app_db';
$user = 'app_user';
$password = 'appg8';

$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
$pdo = new PDO($dsn, $user, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
