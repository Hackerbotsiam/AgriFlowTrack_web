<?php
require_once 'db.php';
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $stmt = $conn->prepare("SELECT * FROM agri_inputs WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $result = $stmt->fetch();
        }
        else {
            $stmt = $conn->prepare("SELECT * FROM agri_inputs ORDER BY id DESC");
            $stmt->execute();
            $result = $stmt->fetchAll();
        }
        echo json_encode($result);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->item)) {
            $date_received = !empty($data->date_received) ? $data->date_received : null;
            $procurement_date = !empty($data->procurement_date) ? $data->procurement_date : null;

            $sql = "INSERT INTO agri_inputs (item, quantity, unit, date_received, input_type, name, stock_level, usage_rate_per_week, procurement_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([$data->item, $data->quantity, $data->unit, $date_received, $data->input_type, $data->name, $data->stock_level, $data->usage_rate_per_week, $procurement_date])) {
                echo json_encode(["message" => "Input created."]);
            }
            else {
                echo json_encode(["message" => "Input creation failed."]);
            }
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->id)) {
            $date_received = !empty($data->date_received) ? $data->date_received : null;
            $procurement_date = !empty($data->procurement_date) ? $data->procurement_date : null;

            $sql = "UPDATE agri_inputs SET item=?, quantity=?, unit=?, date_received=?, input_type=?, name=?, stock_level=?, usage_rate_per_week=?, procurement_date=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([$data->item, $data->quantity, $data->unit, $date_received, $data->input_type, $data->name, $data->stock_level, $data->usage_rate_per_week, $procurement_date, $data->id])) {
                echo json_encode(["message" => "Input updated."]);
            }
            else {
                echo json_encode(["message" => "Input update failed."]);
            }
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->id)) {
            $stmt = $conn->prepare("DELETE FROM agri_inputs WHERE id = ?");
            if ($stmt->execute([$data->id])) {
                echo json_encode(["message" => "Input deleted."]);
            }
            else {
                echo json_encode(["message" => "Input deletion failed."]);
            }
        }
        break;
}
?>
