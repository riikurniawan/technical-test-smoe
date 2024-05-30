<?php
// set your mysql connection
$servername = "localhost";
$username = "root";
$password = "";
$db_name = "interview-test";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (\Exception $e) {
    die($e->getMessage());
}
