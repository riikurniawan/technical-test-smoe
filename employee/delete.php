<?php
include 'connection.php';

$out = [];
if (!empty($_GET['id'])) {
    $employee_id = str_replace("'", "", $_GET['id']);
    $employee = $pdo->query("SELECT * FROM employee WHERE id = $employee_id")->fetch();

    if ($employee) {
        $update = $pdo->prepare("DELETE FROM employee WHERE id = :id");
        $status = $update->execute([
            'id' => $employee_id
        ]);
        if ($status) {
            $out['status'] = 'success';
            $out['msg'] = 'Delete Employee Data Success!';
        } else {
            $out['status'] = 'error';
            $out['msg'] = 'error database!';
        }
    } else {
        $out['status'] = 'error';
        $out['msg'] = 'Employee ID Not Found!';
    }
} else {
    $out['status'] = 'error';
    $out['msg'] = 'Missing url parameter id!';
}

echo json_encode($out);
return;
