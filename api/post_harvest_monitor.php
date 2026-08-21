<?php
require_once 'db.php';
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $stmt = $conn->prepare("SELECT * FROM post_harvest_monitor WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $result = $stmt->fetch();
        }
        else {
            $stmt = $conn->prepare("SELECT * FROM post_harvest_monitor ORDER BY id DESC");
            $stmt->execute();
            $result = $stmt->fetchAll();
        }
        echo json_encode($result);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->crop_name)) {
            $inspection_date = !empty($data->inspection_date) ? $data->inspection_date : null;

            $sql = "INSERT INTO post_harvest_monitor (crop_name, moisture_level, temperature, visual_inspection, inspection_date, inspector) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([$data->crop_name, $data->moisture_level, $data->temperature, $data->visual_inspection, $inspection_date, $data->inspector])) {
                echo json_encode(["message" => "Record created."]);
            }
            else {
                echo json_encode(["message" => "Record creation failed."]);
            }
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->id)) {
            $inspection_date = !empty($data->inspection_date) ? $data->inspection_date : null;

            $sql = "UPDATE post_harvest_monitor SET crop_name=?, moisture_level=?, temperature=?, visual_inspection=?, inspection_date=?, inspector=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([$data->crop_name, $data->moisture_level, $data->temperature, $data->visual_inspection, $inspection_date, $data->inspector, $data->id])) {
                echo json_encode(["message" => "Record updated."]);
            }
            else {
                echo json_encode(["message" => "Record update failed."]);
            }
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->id)) {
            $stmt = $conn->prepare("DELETE FROM post_harvest_monitor WHERE id = ?");
            if ($stmt->execute([$data->id])) {
                echo json_encode(["message" => "Record deleted."]);
            }
            else {
                echo json_encode(["message" => "Record deletion failed."]);
            }
        }
        break;
}
?>
