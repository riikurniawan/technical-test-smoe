<?php
include 'connection.php';

$stmt = $pdo->query("SELECT * FROM employee");
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($employees);
