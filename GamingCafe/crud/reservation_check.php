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

// ✅ Get active reservation (including computer ID)
$sql = "SELECT id, time, package_id, computer_id FROM reservations WHERE user_id = ? AND DATE(date) = CURDATE() ORDER BY time ASC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "No active reservations."]);
    exit;
}

// ✅ Fetch reservation details
$reservation = $result->fetch_assoc();
$stmt->close();

// ✅ Extract start time from a range like "5:00 PM - 8:00 PM"
$timeRange = explode(" - ", $reservation['time']);
$start_time = date("H:i", strtotime($timeRange[0])); // Convert "5:00 PM" to "17:00"

// ✅ Get package duration
$pkg_stmt = $conn->prepare("SELECT package_time FROM packages WHERE package_id = ?");
$pkg_stmt->bind_param("i", $reservation['package_id']);
$pkg_stmt->execute();
$pkg_result = $pkg_stmt->get_result();
$pkg_stmt->close();

if ($pkg_result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Package details not found."]);
    exit;
}

$package = $pkg_result->fetch_assoc();
$duration = intval($package['package_time']); // Convert hours to integer

// ✅ Return fixed response (Including PC number)
echo json_encode([
    "success" => true,
    "start_time" => $start_time, // Now in "HH:MM" format
    "duration" => $duration, // Duration in hours
    "pc_number" => $reservation['computer_id'] // ✅ Add PC number
]);

$conn->close();
exit;
?>
