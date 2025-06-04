<?php
// ✅ Fix: Only start a session if none exists
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json'); // ✅ Ensure JSON response

error_reporting(E_ALL);
ini_set('display_errors', 1);
// ✅ Database connection
include '../connect.php';

// ✅ Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// ✅ Get action parameter
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === "viewone") {
    $user_id = $_SESSION['user_id'];

    // ✅ Fetch user details
    $sql = "SELECT first_name, last_name, user_name, phone_number, address, email, propic FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $userResult = $stmt->get_result();
    $user = $userResult->fetch_assoc();
    $stmt->close();

    // ✅ Fetch user reservation count
    $sql = "SELECT COUNT(*) as reservations FROM reservations WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $reservationResult = $stmt->get_result();
    $reservation = $reservationResult->fetch_assoc();
    $stmt->close();

    $user['reservations'] = $reservation['reservations'] ?? 0; // ✅ Default to 0 if no reservations

    // ✅ Send JSON response
    echo json_encode([$user]);
    exit;
}
 
elseif ($action == "anydata") {
    // ✅ Fetch all users for DataTables
    $sql = "SELECT id, first_name, last_name, user_name, phone_number, address, email FROM users";
    $result = $conn->query($sql);

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    echo json_encode(["data" => $users]); // ✅ DataTables format
    exit;
}
elseif ($action === "updatep") {
    $user_id = $_SESSION['user_id'];
    $oldpassword = $_POST['oldpassword'] ?? "";
    $newpassword = $_POST['newpassword'] ?? "";
    $confirmPassword = $_POST['newpassword_confirmation'] ?? "";

    // ✅ Validate input
    if (empty($oldpassword) || empty($newpassword) || empty($confirmPassword)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }
    if ($newpassword !== $confirmPassword) {
        echo json_encode(["success" => false, "message" => "Passwords do not match."]);
        exit;
    }

    // ✅ Fetch stored password from database
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["success" => false, "message" => "User not found."]);
        exit;
    }

    $user = $result->fetch_assoc();
    $hashedPassword = $user['password'];

    // ✅ Check if old password matches stored password
    if (!password_verify($oldpassword, $hashedPassword)) {
        echo json_encode(["success" => false, "message" => "Old password is incorrect."]);
        exit;
    }

    // ✅ Hash the new password
    $new_hashed_password = password_hash($newpassword, PASSWORD_BCRYPT);

    // ✅ Update the password
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $new_hashed_password, $user_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Password updated successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error updating password."]);
    }

    exit;
}

elseif ($action === "register") {
    $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : "";
    $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : "";
    $user_name = isset($_POST['user_name']) ? trim($_POST['user_name']) : "";
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : "";
    $address = isset($_POST['address']) ? trim($_POST['address']) : "";
    $email = isset($_POST['email']) ? trim($_POST['email']) : "";
    $password = isset($_POST['password']) ? trim($_POST['password']) : "";
    $pass2 = isset($_POST['pass2']) ? trim($_POST['pass2']) : "";

    // ✅ Validation: Ensure all fields are filled
    if (empty($first_name) || empty($last_name) || empty($user_name) || empty($phone) || empty($address) || empty($email) || empty($password) || empty($pass2)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    // ✅ Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Invalid email format."]);
        exit;
    }

    // ✅ Validate password length
    if (strlen($password) < 6) {
        echo json_encode(["success" => false, "message" => "Password must be at least 6 characters."]);
        exit;
    }

    // ✅ Check if passwords match
    if ($password !== $pass2) {
        echo json_encode(["success" => false, "message" => "Passwords do not match."]);
        exit;
    }

    // ✅ Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Email is already registered."]);
        exit;
    }
    $stmt->close();

    // ✅ Check if username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE user_name = ?");
    $stmt->bind_param("s", $user_name);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Username is already taken."]);
        exit;
    }
    $stmt->close();

    // ✅ Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $propic = rand(1, 21); // Random profile picture selection
    $isadmin = 0; // Default user role

    // ✅ Insert user into database with timestamp
    $stmt = $conn->prepare("INSERT INTO users 
        (first_name, last_name, user_name, phone_number, address, propic, email, password, isadmin, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("sssssissi", $first_name, $last_name, $user_name, $phone, $address, $propic, $email, $hashed_password, $isadmin);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "User registered successfully!", "redirect" => "login.php"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
    }
    
    $stmt->close();
    exit;
}
   
elseif ($action === "login") {
    $uname = isset($_POST['uname']) ? trim($_POST['uname']) : "";
    $password = isset($_POST['password']) ? trim($_POST['password']) : "";

    error_log("Received login request: Username = $uname, Password = $password");

    // ✅ Validation: Ensure all fields are filled
    if (empty($uname) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Username and password are required."]);
        exit;
    }

    // ✅ Check user in database
    $stmt = $conn->prepare("SELECT id, password, isadmin FROM users WHERE user_name = ?");
    $stmt->bind_param("s", $uname);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        error_log("Login failed: User not found.");
        echo json_encode(["success" => false, "message" => "Invalid username or password."]);
        exit;
    }

    $user = $result->fetch_assoc();
    $hashedPassword = $user['password'];
    $isadmin = $user['isadmin']; // ✅ Get the admin status

    error_log("Checking password for user: " . $uname);
    error_log("Hashed password in DB: " . $hashedPassword);

    // ✅ Verify password
    if (!password_verify($password, $hashedPassword)) {
        error_log("Password verification failed!");
        echo json_encode(["success" => false, "message" => "Invalid username or password."]);
        exit;
    }

    // ✅ Store session data
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $uname;
    $_SESSION['isadmin'] = $isadmin; // ✅ Store admin status in session

    // ✅ Redirect based on `isadmin`
    $redirectUrl = ($isadmin == 1) ? "admin.php" : "user.php";

    error_log("Login successful. Redirecting to: " . $redirectUrl);
    echo json_encode(["success" => true, "redirect" => $redirectUrl]); // ✅ Return redirect URL
    exit;
}
elseif ($action === "update") {
    $user_id = $_SESSION['user_id'];
    $firstname = trim($_POST['firstname'] ?? "");
    $lastname = trim($_POST['lastname'] ?? "");
    $username = trim($_POST['username'] ?? "");
    $phonenumber = trim($_POST['phonenumber'] ?? "");
    $address = trim($_POST['address'] ?? "");
    $email = trim($_POST['email'] ?? "");
    $propic = trim($_POST['propic'] ?? "");

    // ✅ Validation
    $errors = [];

    // ✅ Check if fields are empty
    if (empty($firstname)) $errors['firstname'][] = "First name is required.";
    if (empty($lastname)) $errors['lastname'][] = "Last name is required.";
    if (empty($username)) $errors['username'][] = "Username is required.";
    if (empty($phonenumber)) $errors['phonenumber'][] = "Phone number is required.";
    if (empty($address)) $errors['address'][] = "Address is required.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'][] = "Valid email is required.";

    // ✅ Check if username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE user_name = ? AND id != ?");
    $stmt->bind_param("si", $username, $user_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $errors['username'][] = "Username already exists.";
    }
    $stmt->close();

    // ✅ Check if phone number already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE phone_number = ? AND id != ?");
    $stmt->bind_param("si", $phonenumber, $user_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $errors['phonenumber'][] = "Phone number already exists.";
    }
    $stmt->close();
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->bind_param("si", $email, $user_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $errors['email'][] = "Email already exists.";
    }
    $stmt->close();

    if (!empty($errors)) {
        echo json_encode(["success" => false, "message" => $errors]);
        exit;
    }
    $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, user_name=?, phone_number=?, address=?, email=?, propic=? WHERE id=?");
    $stmt->bind_param("sssssssi", $firstname, $lastname, $username, $phonenumber, $address, $email, $propic, $user_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Profile updated successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error: " . $stmt->error]);
    }

    exit;
}
else {
    echo json_encode(["error" => "Invalid action"]); // ✅ Handle incorrect actions
    exit;
}
?>
