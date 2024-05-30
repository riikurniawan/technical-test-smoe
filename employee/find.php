<?php
include '../connection.php';
$id = str_replace("'", '', $_GET['id']);

$employee = $pdo->query("SELECT * FROM employee WHERE id=$id")->fetch(PDO::FETCH_OBJ);
if ($employee) echo json_encode($employee);
