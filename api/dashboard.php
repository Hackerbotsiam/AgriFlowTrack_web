<?php
require_once 'db.php';

// Dashboard endpoints only need GET for now.
$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    $dashboardData = [];

    // Count Products
    $stmt = $conn->query("SELECT COUNT(*) as count FROM agricultural_products");
    $dashboardData['productsCount'] = $stmt->fetch()['count'];

    // Count Agri Inputs
    $stmt = $conn->query("SELECT COUNT(*) as count FROM agri_inputs");
    $dashboardData['agriInputsCount'] = $stmt->fetch()['count'];

    // Count Perishables
    $stmt = $conn->query("SELECT COUNT(*) as count FROM perishable_products");
    $dashboardData['perishablesCount'] = $stmt->fetch()['count'];

    // Count Post-Harvest
    $stmt = $conn->query("SELECT COUNT(*) as count FROM post_harvest_monitor");
    $dashboardData['postHarvestCount'] = $stmt->fetch()['count'];

    // Fetch Latest Market Prices
    $stmt = $conn->query("SELECT * FROM market_data ORDER BY id DESC LIMIT 5");
    $dashboardData['marketData'] = $stmt->fetchAll();

    echo json_encode($dashboardData);
}
?>
