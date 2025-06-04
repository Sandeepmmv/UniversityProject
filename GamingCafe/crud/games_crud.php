<?php
header('Content-Type: application/json'); // Ensure JSON response
include '../connect.php';

ob_clean(); 
// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Debug: Print the received action parameter
$action = isset($_GET['action']) ? $_GET['action'] : '';
// if (!$action) {
//     die(json_encode(["error" => "Missing action parameter"]));
// }
// if (!in_array($action, ["view", "viewone"])) {
//     die(json_encode(["error" => "Invalid action value", "received_action" => $action]));
// }

// Fetch all games
if ($action == "store") {
    // ✅ Get input data
    $name = isset($_POST['name']) ? trim($_POST['name']) : "";
    $imagelink = isset($_POST['imagelink']) ? trim($_POST['imagelink']) : "";

    // ✅ Basic validation
    $errors = [];
    if (empty($name)) {
        $errors['name'] = ["Game name is required."];
    }
    if (empty($imagelink)) {
        $errors['imagelink'] = ["Game image link is required."];
    }

    if (!empty($errors)) {
        echo json_encode(["success" => false, "message" => $errors]);
        exit;
    }

    // ✅ Insert game into database
    $stmt = $conn->prepare("INSERT INTO games (name, path) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $imagelink);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Game added successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add game"]);
    }

    $stmt->close();
    exit;
}
elseif ($action == "view") {
    // ✅ Fetch all games
    $sql = "SELECT id, name FROM games";
    $result = $conn->query($sql);

    $games = [];
    while ($row = $result->fetch_assoc()) {
        $games[] = $row;
    }

    echo json_encode($games); // ✅ Correctly formatted JSON output
    exit; // ✅ Ensure script stops here
}
elseif ($action == "anydata") {
    // ✅ Fetch all games for DataTables
    $sql = "SELECT id, name FROM games";
    $result = $conn->query($sql);

    $games = [];
    while ($row = $result->fetch_assoc()) {
        $games[] = $row;
    }

    echo json_encode(["data" => $games]); // ✅ Correct DataTables JSON format
    exit; // ✅ Prevents extra data from being sent
}
elseif ($action == "getlatest") {
    $sql = "SELECT name, path FROM games ORDER BY id DESC LIMIT 6";
    $result = $conn->query($sql);

    $latest_games = [];
    while ($row = $result->fetch_assoc()) {
        $latest_games[] = $row;
    }

    // ✅ Debugging: Log JSON response
    error_log("✅ getlatest response: " . json_encode($latest_games));

    echo json_encode($latest_games);
    exit;
}

elseif ($action === "viewone") {
    header("Content-Type: application/json");

    $gameid = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($gameid === 0) {
        echo json_encode(["error" => "Invalid game ID."]);
        exit;
    }

    // ✅ Fetch game details
    $stmt = $conn->prepare("SELECT id, name, path FROM games WHERE id = ?");
    $stmt->bind_param("i", $gameid);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(["error" => "Game not found."]);
        exit;
    }

    $game = $result->fetch_assoc();
    
    // ✅ Validate image path (fallback if missing)
    if (empty($game['path'])) {
        $game['path'] = "css/img/default_game.jpg"; // ✅ Fallback image
    }

    echo json_encode([$game]);
    exit;
}

elseif ($action == "delete") {
    // ✅ Get game ID
    $gameid = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($gameid === 0) {
        echo json_encode(["success" => false, "message" => "Invalid game ID."]);
        exit;
    }

    // ✅ Check if game exists
    $checkQuery = $conn->prepare("SELECT * FROM games WHERE id = ?");
    $checkQuery->bind_param("i", $gameid);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["success" => false, "message" => "Game not found."]);
        exit;
    }

    // ✅ Delete game from database
    $stmt = $conn->prepare("DELETE FROM games WHERE id = ?");
    $stmt->bind_param("i", $gameid);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "id" => $gameid]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete game."]);
    }

    $stmt->close();
    exit;
}
elseif ($action == "update") {
    // ✅ Get game details
    $gameid = isset($_POST['gameid']) ? intval($_POST['gameid']) : 0;
    $gamename = isset($_POST['name']) ? trim($_POST['name']) : "";
    $gameurl = isset($_POST['imagelink']) ? trim($_POST['imagelink']) : "";

    // ✅ Validation
    if ($gameid === 0) {
        echo json_encode(["success" => false, "message" => "Invalid game ID."]);
        exit;
    }
    if (empty($gamename) || empty($gameurl)) {
        echo json_encode(["success" => false, "message" => "Game name and image link are required."]);
        exit;
    }

    // ✅ Check if game exists
    $checkQuery = $conn->prepare("SELECT * FROM games WHERE id = ?");
    $checkQuery->bind_param("i", $gameid);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["success" => false, "message" => "Game not found."]);
        exit;
    }

    // ✅ Update game details in the database
    $stmt = $conn->prepare("UPDATE games SET name = ?, path = ? WHERE id = ?");
    $stmt->bind_param("ssi", $gamename, $gameurl, $gameid);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "gameid" => $gameid,
            "name" => $gamename,
            "imagelink" => $gameurl
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update game."]);
    }

    $stmt->close();
    exit;
}
elseif ($action == "partofdata") {
    $page = intval($_GET['page'] ?? 1);
    $limit = 18;
    $offset = ($page - 1) * $limit;

    $sql = "SELECT name, path FROM games LIMIT ?, ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    $games = [];
    while ($row = $result->fetch_assoc()) {
        $games[] = $row;
    }

    $response = [
        "data" => $games,
        "current_page" => $page,
        "prev_page_url" => ($page > 1) ? "games_crud.php?action=partofdata&page=" . ($page - 1) : null,
        "next_page_url" => count($games) == $limit ? "games_crud.php?action=partofdata&page=" . ($page + 1) : null,
        "links" => [["label" => $page, "url" => "games_crud.php?action=partofdata&page=" . $page]]
    ];

    echo json_encode($response);
    exit;
}
else {
    echo json_encode(["error" => "Invalid action"]); // ✅ Error handling
}
?>
?>
