<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $data = json_decode(file_get_contents('php://input'));
    $out = [];

    if (empty($data->name) || empty($data->job_title)) {
        $out['status'] = 'error';

        if (empty($data->name)) $out['msg']['name'] = 'Name must be filled!';
        if (empty($data->job_title)) $out['msg']['job_title'] = 'Job Title must be filled!';
    } else {
        $name = htmlspecialchars($data->name);
        $job_title = htmlspecialchars($data->job_title);

        $save = $pdo->prepare("INSERT INTO employee (name, job_title) VALUES (:name, :job_title)");
        $status = $save->execute([
            'name' => $name,
            'job_title' => $job_title
        ]);
        if ($status) {
            $out['status'] = 'success';
            $out['msg'] = 'New Employee Data Has Added!';
        } else {
            $out['status'] = $status;
            $out['msg'] = 'error database!';
        }
    }
    echo json_encode($out);
    return;
}
