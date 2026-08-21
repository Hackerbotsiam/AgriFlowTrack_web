<?php
require_once 'db.php';
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $stmt = $conn->prepare("SELECT * FROM inventory WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $result = $stmt->fetch();
        }
        else {
            $stmt = $conn->prepare("SELECT * FROM inventory ORDER BY id DESC");
            $stmt->execute();
            $result = $stmt->fetchAll();
        }
        echo json_encode($result);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->item_name)) {
            $date_entered = !empty($data->date_entered) ? $data->date_entered : null;
            $expiry_date = !empty($data->expiry_date) ? $data->expiry_date : null;

            $sql = "INSERT INTO inventory (item_name, amount, unit, date_entered, expiry_date, destination, warehouse, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([$data->item_name, $data->amount, $data->unit, $date_entered, $expiry_date, $data->destination, $data->warehouse, $data->notes])) {
                echo json_encode(["message" => "Inventory item created."]);
            }
            else {
                echo json_encode(["message" => "Inventory item creation failed."]);
            }
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->id)) {
            $date_entered = !empty($data->date_entered) ? $data->date_entered : null;
            $expiry_date = !empty($data->expiry_date) ? $data->expiry_date : null;

            $sql = "UPDATE inventory SET item_name=?, amount=?, unit=?, date_entered=?, expiry_date=?, destination=?, warehouse=?, notes=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([$data->item_name, $data->amount, $data->unit, $date_entered, $expiry_date, $data->destination, $data->warehouse, $data->notes, $data->id])) {
                echo json_encode(["message" => "Inventory item updated."]);
            }
            else {
                echo json_encode(["message" => "Inventory item update failed."]);
            }
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->id)) {
            $stmt = $conn->prepare("DELETE FROM inventory WHERE id = ?");
            if ($stmt->execute([$data->id])) {
                echo json_encode(["message" => "Inventory item deleted."]);
            }
            else {
                echo json_encode(["message" => "Inventory item deletion failed."]);
            }
        }
        break;
}
?>
