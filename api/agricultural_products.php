<?php
require_once 'db.php';
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $stmt = $conn->prepare("SELECT * FROM agricultural_products WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $result = $stmt->fetch();
        }
        else {
            $stmt = $conn->prepare("SELECT * FROM agricultural_products ORDER BY id DESC");
            $stmt->execute();
            $result = $stmt->fetchAll();
        }
        echo json_encode($result);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->product_name)) {
            $growing_date = !empty($data->growing_date) ? $data->growing_date : null;
            $harvest_date = !empty($data->harvest_date) ? $data->harvest_date : null;

            $sql = "INSERT INTO agricultural_products (product_name, category, growing_date, harvest_date, storage_requirements, shelf_life, packaging_details) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([$data->product_name, $data->category, $growing_date, $harvest_date, $data->storage_requirements, $data->shelf_life, $data->packaging_details])) {
                echo json_encode(["message" => "Product created."]);
            }
            else {
                echo json_encode(["message" => "Product creation failed."]);
            }
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->id)) {
            $growing_date = !empty($data->growing_date) ? $data->growing_date : null;
            $harvest_date = !empty($data->harvest_date) ? $data->harvest_date : null;

            $sql = "UPDATE agricultural_products SET product_name=?, category=?, growing_date=?, harvest_date=?, storage_requirements=?, shelf_life=?, packaging_details=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([$data->product_name, $data->category, $growing_date, $harvest_date, $data->storage_requirements, $data->shelf_life, $data->packaging_details, $data->id])) {
                echo json_encode(["message" => "Product updated."]);
            }
            else {
                echo json_encode(["message" => "Product update failed."]);
            }
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->id)) {
            $stmt = $conn->prepare("DELETE FROM agricultural_products WHERE id = ?");
            if ($stmt->execute([$data->id])) {
                echo json_encode(["message" => "Product deleted."]);
            }
            else {
                echo json_encode(["message" => "Product deletion failed."]);
            }
        }
        break;
}
?>
