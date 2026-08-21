<?php
require_once 'db.php';
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $stmt = $conn->prepare("SELECT * FROM harvested_crops WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $result = $stmt->fetch();
        }
        else {
            $stmt = $conn->prepare("SELECT * FROM harvested_crops ORDER BY id DESC");
            $stmt->execute();
            $result = $stmt->fetchAll();
        }
        echo json_encode($result);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->name)) {
            $expiry_date = !empty($data->expiry_date) ? $data->expiry_date : null;

            $sql = "INSERT INTO harvested_crops (name, quantity, storage_condition, movement_details, expiry_date) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([$data->name, $data->quantity, $data->storage_condition, $data->movement_details, $expiry_date])) {
                echo json_encode(["message" => "Crop created."]);
            }
            else {
                echo json_encode(["message" => "Crop creation failed."]);
            }
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->id)) {
            $expiry_date = !empty($data->expiry_date) ? $data->expiry_date : null;

            $sql = "UPDATE harvested_crops SET name=?, quantity=?, storage_condition=?, movement_details=?, expiry_date=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([$data->name, $data->quantity, $data->storage_condition, $data->movement_details, $expiry_date, $data->id])) {
                echo json_encode(["message" => "Crop updated."]);
            }
            else {
                echo json_encode(["message" => "Crop update failed."]);
            }
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->id)) {
            $stmt = $conn->prepare("DELETE FROM harvested_crops WHERE id = ?");
            if ($stmt->execute([$data->id])) {
                echo json_encode(["message" => "Crop deleted."]);
            }
            else {
                echo json_encode(["message" => "Crop deletion failed."]);
            }
        }
        break;
}
?>
