<?php
header('Content-Type: application/json'); // ✅ Ensure JSON response

// ✅ Database connection
include '../connect.php';

// ✅ Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// ✅ Get action parameter
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == "viewall") {
    // ✅ Fetch all packages
    $sql = "SELECT package_id, package_name, package_time, package_price FROM packages";
    $result = $conn->query($sql);

    $packages = [];
    while ($row = $result->fetch_assoc()) {
        $packages[] = $row;
    }

    echo json_encode($packages); // ✅ Correct JSON response
    exit;
}
elseif ($action == "viewone") {
    $packid = isset($_GET['packid']) ? intval($_GET['packid']) : 0;
    
    if ($packid === 0) {
        echo json_encode(["error" => "Invalid package ID"]);
        exit;
    }

    // ✅ Fetch package details
    $sql = "SELECT package_name, package_time, package_price FROM packages WHERE package_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $packid);
    $stmt->execute();
    $result = $stmt->get_result();

    $package = [];
    while ($row = $result->fetch_assoc()) {
        $package[] = $row;
    }

    echo json_encode($package); // ✅ Return package details
    exit;
}
elseif ($action == "store") {
    // ✅ Get input data
    $packname = isset($_POST['packname']) ? trim($_POST['packname']) : "";
    $packtime = isset($_POST['packtime']) ? trim($_POST['packtime']) : "";
    $packprice = isset($_POST['packprice']) ? trim($_POST['packprice']) : "";

    // ✅ Basic validation
    $errors = [];
    if (empty($packname)) {
        $errors['packname'] = ["Package name is required."];
    }
    if (empty($packtime) || !is_numeric($packtime)) {
        $errors['packtime'] = ["Valid package time is required."];
    }
    if (empty($packprice) || !is_numeric($packprice)) {
        $errors['packprice'] = ["Valid package price is required."];
    }

    if (!empty($errors)) {
        echo json_encode(["success" => false, "message" => $errors]);
        exit;
    }

    // ✅ Insert package into database
    $stmt = $conn->prepare("INSERT INTO packages (package_name, package_time, package_price) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $packname, $packtime, $packprice);

    if ($stmt->execute()) {
        $new_package_id = $stmt->insert_id;
        echo json_encode([
            "success" => true,
            "package_id" => $new_package_id,
            "package_name" => $packname,
            "package_time" => $packtime,
            "package_price" => $packprice
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add package"]);
    }

    $stmt->close();
    exit;
}
elseif ($action == "update") {
    // ✅ Get input data
    $packid = isset($_POST['packid']) ? intval($_POST['packid']) : 0;
    $packname = isset($_POST['packname']) ? trim($_POST['packname']) : "";
    $packtime = isset($_POST['packtime']) ? trim($_POST['packtime']) : "";
    $packprice = isset($_POST['packprice']) ? trim($_POST['packprice']) : "";

    // ✅ Basic validation
    $errors = [];
    if ($packid === 0) {
        $errors['packid'] = ["Invalid package ID."];
    }
    if (empty($packname)) {
        $errors['packname'] = ["Package name is required."];
    }
    if (empty($packtime) || !is_numeric($packtime)) {
        $errors['packtime'] = ["Valid package time is required."];
    }
    if (empty($packprice) || !is_numeric($packprice)) {
        $errors['packprice'] = ["Valid package price is required."];
    }

    if (!empty($errors)) {
        echo json_encode(["success" => false, "message" => $errors]);
        exit;
    }

    // ✅ Update package in database
    $stmt = $conn->prepare("UPDATE packages SET package_name = ?, package_time = ?, package_price = ? WHERE package_id = ?");
    $stmt->bind_param("siii", $packname, $packtime, $packprice, $packid);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "package_id" => $packid,
            "package_name" => $packname,
            "package_time" => $packtime,
            "package_price" => $packprice
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update package"]);
    }

    $stmt->close();
    exit;
}
elseif ($action == "delete") {
    // ✅ Get package ID
    $packid = isset($_POST['packid']) ? intval($_POST['packid']) : 0;

    if ($packid === 0) {
        echo json_encode(["success" => false, "message" => "Invalid package ID."]);
        exit;
    }

    // ✅ Check if package exists
    $checkQuery = $conn->prepare("SELECT * FROM packages WHERE package_id = ?");
    $checkQuery->bind_param("i", $packid);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["success" => false, "message" => "Package not found."]);
        exit;
    }

    // ✅ Delete package from database
    $stmt = $conn->prepare("DELETE FROM packages WHERE package_id = ?");
    $stmt->bind_param("i", $packid);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "package_id" => $packid]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete package."]);
    }

    $stmt->close();
    exit;
}
else{
    echo json_encode(["error" => "Invalid action"]);
exit;
}

echo json_encode(["error" => "Invalid action"]);
exit;
?>
