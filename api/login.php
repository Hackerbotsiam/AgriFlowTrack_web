<?php
require_once 'db.php';
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->username) && !empty($data->password)) {
        $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ? AND password = ?");
        // Note: In production, use password_hash and password_verify instead of plain text, 
        // but sticking to plain text based on the specific requirements asked by user for "admin123"
        $stmt->execute([$data->username, $data->password]);
        $user = $stmt->fetch();

        if ($user) {
            echo json_encode(["success" => true, "username" => $user['username']]);
        }
        else {
            http_response_code(401);
            echo json_encode(["success" => false, "message" => "Invalid username or password"]);
        }
    }
    else {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Username and password are required"]);
    }
}
else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Method not allowed"]);
}
?>
