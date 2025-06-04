<?php
header('Content-Type: application/json'); // ✅ Ensure JSON response

// ✅ Database connection
include '../connect.php';

// ✅ Check connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// ✅ Get action parameter
$action = isset($_GET['action']) ? $_GET['action'] : '';
//payment
// if ($action == "store") {
//     // ✅ Debugging: Print received POST data
//     error_log("Received POST data: " . print_r($_POST, true));

//     // ✅ Fetch Input Data
//     $user_id = intval($_POST['user_id'] ?? 0);
//     $user_name = $_POST['user_name'] ?? ''; // ✅ Debugging
//     $date = $_POST['date'] ?? '';
//     $time = $_POST['time'] ?? '';
//     $computer_id = intval($_POST['computer_id'] ?? 0);
//     $package_id = intval($_POST['package_id'] ?? 0);
//     $start_time = $_POST['start_time'] ?? '';
//     $transaction_id = $_POST['transaction_id'] ?? ''; // ✅ Added for payment tracking
//     $payment_method = $_POST['payment_method'] ?? ''; // ✅ Added for payment tracking

//     // ✅ Debugging: Log user_name to see if it's empty
//     error_log("User Name received: " . $user_name);

//     // ✅ Validate Inputs
//     if ($user_id <= 0 || empty($user_name) || empty($date) || empty($time) || $computer_id <= 0 || $package_id <= 0 || empty($start_time) || empty($transaction_id) || empty($payment_method)) {
//         echo json_encode(["error" => "Missing required fields", "received" => $_POST]);
//         exit;
//     }

//     // ✅ Insert Reservation (Includes Payment Details)
//     $sql = "INSERT INTO reservations (user_id, user_name, date, time, computer_id, package_id, start_time, transaction_id, payment_method, created_at, updated_at) 
//             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("isssissss", $user_id, $user_name, $date, $time, $computer_id, $package_id, $start_time, $transaction_id, $payment_method);

//     if ($stmt->execute()) {
//         echo json_encode(["success" => true, "message" => "✅ Reservation confirmed!"]);
//     } else {
//         echo json_encode(["error" => "❌ Failed to create reservation"]);
//     }

//     exit;
// }


if ($action == "store") {
    // ✅ Debugging: Print received POST data
    error_log("Received POST data: " . print_r($_POST, true));

    // ✅ Fetch Input Data
    $user_id = intval($_POST['user_id'] ?? 0);
    $user_name = $_POST['user_name'] ?? ''; // ✅ Debugging
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $computer_id = intval($_POST['computer_id'] ?? 0);
    $package_id = intval($_POST['package_id'] ?? 0);
    $start_time = $_POST['start_time'] ?? '';

    // ✅ Debugging: Log user_name to see if it's empty
    error_log("User Name received: " . $user_name);

    // ✅ Validate Inputs
    if ($user_id <= 0 || empty($user_name) || empty($date) || empty($time) || $computer_id <= 0 || $package_id <= 0 || empty($start_time)) {
        echo json_encode(["error" => "Missing required fields", "received" => $_POST]);
        exit;
    }

    // ✅ Insert Reservation
    $sql = "INSERT INTO reservations (user_id, user_name, date, time, computer_id, package_id, start_time, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssiss", $user_id, $user_name, $date, $time, $computer_id, $package_id, $start_time);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Reservation confirmed"]);
    } else {
        echo json_encode(["error" => "Failed to create reservation"]);
    }

    exit;
}
// if ($action == "anydata") {
//     // ✅ Fetch reservations along with user name and package info
//     $sql = "SELECT 
//                 r.user_id, 
//                 u.user_name, 
//                 r.date, 
//                 r.time, 
//                 r.computer_id, 
//                 CONCAT(p.package_name, ' - ₹', p.price) AS package 
//             FROM reservations r
//             JOIN users u ON r.user_id = u.id
//             JOIN packages p ON r.package_id = p.id"; // ✅ Join packages

//     $result = $conn->query($sql);

//     $reservations = [];
//     while ($row = $result->fetch_assoc()) {
//         $reservations[] = $row;
//     }

//     echo json_encode(["data" => $reservations]);
//     exit;
// }

if ($action == "anydata") {
    // ✅ Fetch all reservations for DataTables
    $sql = "SELECT r.user_id, u.user_name, r.date, r.time, r.computer_id, r.package_id 
            FROM reservations r
            JOIN users u ON r.user_id = u.id"; // ✅ Join to get user name

    $result = $conn->query($sql);

    $reservations = [];
    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }

    echo json_encode(["data" => $reservations]); // ✅ DataTables format
    exit;
}
if ($action == "geteventdetails") {
    // Check if user_id is set and valid
    $user_id = intval($_GET['user_id'] ?? 0);
    if ($user_id <= 0) {
        echo json_encode(["error" => "Invalid user ID"]);
        exit;
    }

    // Fetch user's registration date
    $sql = "SELECT created_at FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $regdate = $user['created_at'] ?? null;

    // Fetch user's reservations
    $sql = "SELECT id, date, time, computer_id FROM reservations WHERE user_id = ? ORDER BY date ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $reservations = [];
    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }

    // ✅ Correct: Stop execution after sending JSON response
    echo json_encode(["regdate" => $regdate, "reservation" => $reservations]);
    exit;
}
if ($action == "respkgdata") {
    $packid = intval($_GET['packid'] ?? 0);
    $date = $_GET['date'] ?? '';
    $pcid = intval($_GET['pcid'] ?? 0);

    if ($packid <= 0 || empty($date) || $pcid <= 0) {
        echo json_encode(["error" => "Invalid input parameters"]);
        exit;
    }

    // ✅ Step 1: Get Package Details
    $sql = "SELECT package_time, package_price FROM packages WHERE package_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $packid);
    $stmt->execute();
    $result = $stmt->get_result();
    $package = $result->fetch_assoc();
    
    if (!$package) {
        echo json_encode(["error" => "Package not found"]);
        exit;
    }

    // ✅ Step 2: Get Reserved Time Slots for Given Date & Computer
    $sql = "SELECT time FROM reservations WHERE computer_id = ? AND date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $pcid, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    $reservedTimes = [];
    while ($row = $result->fetch_assoc()) {
        $reservedTimes[] = $row['time'];
    }

    // ✅ Step 3: Generate Available Time Slots
    $availableTimes = [];
    $openingHour = 8; // Café opens at 8 AM
    $closingHour = 20; // Café closes at 8 PM
    $pkgTime = $package['package_time'];

    for ($hour = $openingHour; $hour <= ($closingHour - $pkgTime); $hour++) {
        if (!in_array($hour, $reservedTimes)) {
            $availableTimes[] = $hour;
        }
    }

    // ✅ Step 4: Determine Full Day Availability
    $isFullDayAvailable = (count($availableTimes) == ($closingHour - $openingHour));

    // ✅ Step 5: Return JSON Response
    echo json_encode([
        "package" => [$package], 
        "availableTimes" => $availableTimes, 
        "isFullDayAvailable" => $isFullDayAvailable
    ]);
    exit;
}
// if ($action === "userdata") {
//     $draw = isset($_GET['draw']) ? intval($_GET['draw']) : 1;
//     $start = isset($_GET['start']) ? intval($_GET['start']) : 0;
//     $length = isset($_GET['length']) ? intval($_GET['length']) : 10;
//     $searchValue = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';

//     // Base FROM and JOINs
//     $baseQuery = "FROM reservations r
//                   JOIN users u ON r.user_id = u.id
//                   JOIN packages p ON r.package_id = p.id";

//     // Search filter
//     $searchQuery = "";
//     if (!empty($searchValue)) {
//         $searchValue = $conn->real_escape_string($searchValue);
//         $searchQuery = " WHERE u.user_name LIKE '%$searchValue%' 
//                          OR r.date LIKE '%$searchValue%' 
//                          OR r.time LIKE '%$searchValue%' 
//                          OR p.package_name LIKE '%$searchValue%'";
//     }

//     // Total records (no search)
//     $totalRecordsQuery = "SELECT COUNT(*) as total $baseQuery";
//     $totalResult = $conn->query($totalRecordsQuery);
//     $totalRecords = $totalResult->fetch_assoc()['total'];

//     // Total filtered records
//     $filteredRecordsQuery = "SELECT COUNT(*) as total $baseQuery $searchQuery";
//     $filteredResult = $conn->query($filteredRecordsQuery);
//     $filteredRecords = $filteredResult->fetch_assoc()['total'];

//     // Final paginated data query
//     $sql = "SELECT 
//                 r.user_id, 
//                 u.user_name, 
//                 r.date, 
//                 r.time, 
//                 r.computer_id, 
//                 CONCAT(p.package_name, ' - ₹', p.price) AS package 
//             $baseQuery 
//             $searchQuery 
//             LIMIT $start, $length";

//     $result = $conn->query($sql);
//     $data = [];

//     while ($row = $result->fetch_assoc()) {
//         $data[] = $row;
//     }

//     echo json_encode([
//         "draw" => $draw,
//         "recordsTotal" => $totalRecords,
//         "recordsFiltered" => $filteredRecords,
//         "data" => $data
//     ]);
//     exit;
// }


if ($action === "userdata") {
    $draw = isset($_GET['draw']) ? intval($_GET['draw']) : 1;
    $start = isset($_GET['start']) ? intval($_GET['start']) : 0;
    $length = isset($_GET['length']) ? intval($_GET['length']) : 10;
    $searchValue = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';

    // ✅ Base query to fetch reservations
    $sql = "SELECT user_id, user_name, date, time, computer_id, package_id FROM reservations";

    // ✅ Apply search filter
    if (!empty($searchValue)) {
        $sql .= " WHERE user_name LIKE '%$searchValue%' OR date LIKE '%$searchValue%' OR time LIKE '%$searchValue%'";
    }

    // ✅ Get total records count
    $totalRecordsQuery = "SELECT COUNT(*) as total FROM reservations";
    $totalRecordsResult = $conn->query($totalRecordsQuery);
    $totalRecords = $totalRecordsResult->fetch_assoc()['total'];

    // ✅ Apply pagination
    $sql .= " LIMIT $start, $length";
    $result = $conn->query($sql);
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // ✅ Return JSON response for DataTable
    echo json_encode([
        "draw" => $draw,
        "recordsTotal" => $totalRecords,
        "recordsFiltered" => count($data),
        "data" => $data
    ]);
    exit;
}


// if ($action == "store") {
//     $time = $_POST['time'] ?? null;
//     $date = $_POST['date'] ?? null;
//     $packid = $_POST['packid'] ?? null;
//     $pc = $_POST['pc'] ?? null;
//     $start_time = $_POST['start_time'] ?? null;

//     if (!$time || !$date || !$packid || !$pc || !$start_time) {
//         echo json_encode(["error" => "Missing required fields"]);
//         exit;
//     }

//     $sql = "INSERT INTO reservations (time, date, package_id, computer_id, start_time) VALUES (?, ?, ?, ?, ?)";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("ssiii", $time, $date, $packid, $pc, $start_time);

//     if ($stmt->execute()) {
//         echo json_encode(["success" => true, "message" => "Reservation successful"]);
//     } else {
//         echo json_encode(["error" => "Database error: " . $conn->error]);
//     }
// } 
if($action === "viewpopuler") {
    // Fetch popular computers (for home page)
    $sql = "SELECT computer_id, COUNT(*) as count FROM reservations GROUP BY computer_id ORDER BY count DESC LIMIT 6";
    $result = $conn->query($sql);
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
    exit;
}

error_log("Invalid action received: " . $action);
echo json_encode(["error" => "Invalid action"]);
exit;
?>
