<?php
include '../config.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'process') {
    $data = json_decode(file_get_contents("php://input"), true);

    $user_id = $data['user_id'];
    $user_name = $data['user_name'];
    $amount = $data['amount'];
    $payment_method = $data['payment_method'];
    $transaction_id = uniqid("TXN_", true); // Generate a unique transaction ID

    $stmt = $conn->prepare("INSERT INTO payments (user_id, user_name, amount, payment_method, transaction_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isdss", $user_id, $user_name, $amount, $payment_method, $transaction_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "transaction_id" => $transaction_id]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to process payment."]);
    }
    exit;
}
?>
