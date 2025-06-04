<?php
session_start();
header('Content-Type: application/json');
include '../connect.php';

// ✅ Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in."]);
    exit;
}

$user_id = intval($_SESSION['user_id']);
$action = isset($_POST['action']) ? $_POST['action'] : "";

// ✅ Mark Reservation as Paid
if ($action === "complete_payment") {
    $sql = "UPDATE reservations SET status = 'Paid' WHERE user_id = ? AND DATE(date) = CURDATE() LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Payment successful!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Payment failed."]);
    }

    $stmt->close();
    $conn->close();
    exit;
}

echo json_encode(["success" => false, "message" => "Invalid action."]);
exit;
?>
