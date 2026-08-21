<?php
require_once 'db.php';
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $stmt = $conn->prepare("SELECT * FROM storage_conditions WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $result = $stmt->fetch();
        }
        else {
            $stmt = $conn->prepare("SELECT * FROM storage_conditions ORDER BY id DESC");
            $stmt->execute();
            $result = $stmt->fetchAll();
        }
        echo json_encode($result);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->facility_name)) {
            $last_checked = !empty($data->last_checked) ? $data->last_checked : null;

            $sql = "INSERT INTO storage_conditions (facility_name, current_temp, humidity, ventilation_status, last_checked) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([$data->facility_name, $data->current_temp, $data->humidity, $data->ventilation_status, $last_checked])) {
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
            $last_checked = !empty($data->last_checked) ? $data->last_checked : null;

            $sql = "UPDATE storage_conditions SET facility_name=?, current_temp=?, humidity=?, ventilation_status=?, last_checked=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([$data->facility_name, $data->current_temp, $data->humidity, $data->ventilation_status, $last_checked, $data->id])) {
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
            $stmt = $conn->prepare("DELETE FROM storage_conditions WHERE id = ?");
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
