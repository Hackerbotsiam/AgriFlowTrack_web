<?php
require_once 'db.php';
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $stmt = $conn->prepare("SELECT * FROM market_data WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $result = $stmt->fetch();
        }
        else {
            $stmt = $conn->prepare("SELECT * FROM market_data ORDER BY id DESC");
            $stmt->execute();
            $result = $stmt->fetchAll();
        }
        echo json_encode($result);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->market)) {
            $date = !empty($data->date) ? $data->date : null;

            $sql = "INSERT INTO market_data (market, product, price_per_unit, date) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([$data->market, $data->product, $data->price_per_unit, $date])) {
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
            $date = !empty($data->date) ? $data->date : null;

            $sql = "UPDATE market_data SET market=?, product=?, price_per_unit=?, date=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([$data->market, $data->product, $data->price_per_unit, $date, $data->id])) {
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
            $stmt = $conn->prepare("DELETE FROM market_data WHERE id = ?");
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
