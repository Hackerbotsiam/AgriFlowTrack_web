<?php
require_once 'db.php';
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $stmt = $conn->prepare("SELECT * FROM perishable_products WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $result = $stmt->fetch();
        }
        else {
            $stmt = $conn->prepare("SELECT * FROM perishable_products ORDER BY id DESC");
            $stmt->execute();
            $result = $stmt->fetchAll();
        }
        echo json_encode($result);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->name)) {
            $added_date = !empty($data->added_date) ? $data->added_date : null;

            $sql = "INSERT INTO perishable_products (name, batch_number, storage_temp, shelf_life_days, status, added_date) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([$data->name, $data->batch_number, $data->storage_temp, $data->shelf_life_days, $data->status, $added_date])) {
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
            $added_date = !empty($data->added_date) ? $data->added_date : null;

            $sql = "UPDATE perishable_products SET name=?, batch_number=?, storage_temp=?, shelf_life_days=?, status=?, added_date=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([$data->name, $data->batch_number, $data->storage_temp, $data->shelf_life_days, $data->status, $added_date, $data->id])) {
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
            $stmt = $conn->prepare("DELETE FROM perishable_products WHERE id = ?");
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
