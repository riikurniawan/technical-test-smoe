<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $data = json_decode(file_get_contents('php://input'));
    $out = [];

    if (!empty($_GET['id'])) {
        $employee_id = str_replace("'", "", $_GET['id']);
        $employee = $pdo->query("SELECT * FROM employee WHERE id = $employee_id")->fetch();

        if ($employee) {
            if (empty($data->name) || empty($data->job_title)) {
                $out['status'] = 'error';

                if (empty($data->name)) $out['msg']['name'] = 'Name must be filled!';
                if (empty($data->job_title)) $out['msg']['job_title'] = 'Job Title must be filled!';
            } else {
                $name = htmlspecialchars($data->name);
                $job_title = htmlspecialchars($data->job_title);

                $update = $pdo->prepare("UPDATE employee SET name = :name, job_title = :job_title WHERE id = :id");
                $status = $update->execute([
                    'name' => $name,
                    'job_title' => $job_title,
                    'id' => $employee_id
                ]);
                if ($status) {
                    $out['status'] = 'success';
                    $out['msg'] = 'Update Employee Data Success!';
                } else {
                    $out['status'] = $status;
                    $out['msg'] = 'error database!';
                }
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
}
