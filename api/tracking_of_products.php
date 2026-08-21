<?php
require_once 'db.php';
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $stmt = $conn->prepare("SELECT * FROM tracking_of_products WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $result = $stmt->fetch();
        }
        else {
            $stmt = $conn->prepare("SELECT * FROM tracking_of_products ORDER BY id DESC");
            $stmt->execute();
            $result = $stmt->fetchAll();
        }
        echo json_encode($result);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->product_name)) {
            $dispatch_date = !empty($data->dispatch_date) ? $data->dispatch_date : null;

            $sql = "INSERT INTO tracking_of_products (product_name, current_location, destination, transit_status, dispatch_date) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([$data->product_name, $data->current_location, $data->destination, $data->transit_status, $dispatch_date])) {
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
            $dispatch_date = !empty($data->dispatch_date) ? $data->dispatch_date : null;

            $sql = "UPDATE tracking_of_products SET product_name=?, current_location=?, destination=?, transit_status=?, dispatch_date=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([$data->product_name, $data->current_location, $data->destination, $data->transit_status, $dispatch_date, $data->id])) {
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
            $stmt = $conn->prepare("DELETE FROM tracking_of_products WHERE id = ?");
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
