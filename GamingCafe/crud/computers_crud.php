<?php
include '../connect.php';

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Get action from URL
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == "view") {
    // ✅ Fetch all computers
    $sql = "SELECT cid, spec1 FROM computers";
    $result = $conn->query($sql);
    
    $computers = [];
    while ($row = $result->fetch_assoc()) {
        $computers[] = $row;
    }

    echo json_encode($computers);
}elseif ($action == "viewoneimg") {
    $cid = intval($_GET['cid'] ?? 0);
    if ($cid > 0) {
        // Fetch computer details
        $sql = "SELECT spec1, spec2, spec3, spec4, spec5, spec6, spec7 FROM computers WHERE cid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $cid);
        $stmt->execute();
        $result = $stmt->get_result();
        $computer = $result->fetch_assoc();

        if ($computer) {
            // Fetch games related to this computer
            $game_sql = "SELECT name, path FROM games g 
                         JOIN computer_game cg ON g.id = cg.game_id 
                         WHERE cg.computer_id = ?";
            $stmt = $conn->prepare($game_sql);
            $stmt->bind_param("i", $cid);
            $stmt->execute();
            $game_result = $stmt->get_result();

            $computer['games'] = [];
            while ($game = $game_result->fetch_assoc()) {
                $computer['games'][] = $game;
            }

            echo json_encode([$computer]);
        } else {
            echo json_encode(["error" => "Computer not found", "cid" => $cid]);
        }
    } else {
        echo json_encode(["error" => "Invalid computer ID"]);
    }
}
elseif ($action == "viewone") {
    // ✅ Fetch details of a single computer, including games
    $cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
    $sql = "SELECT * FROM computers WHERE cid = $cid";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $pc = $result->fetch_assoc();

        // Fetch associated games
        $gameQuery = "SELECT g.id, g.name FROM computer_game cg 
                      JOIN games g ON cg.game_id = g.id 
                      WHERE cg.computer_id = $cid";
        $gameResult = $conn->query($gameQuery);
        $games = [];
        while ($row = $gameResult->fetch_assoc()) {
            $games[] = $row;
        }

        // Add games to PC data
        $pc['games'] = $games;
        echo json_encode([$pc]);
    } else {
        echo json_encode([]);
    }
}
elseif ($action == "delete") {
    // ✅ Get computer ID from request
    $computer_id = isset($_GET['cid']) ? intval($_GET['cid']) : 0;

    if ($computer_id <= 0) {
        echo json_encode(["success" => false, "message" => "Invalid Computer ID"]);
        exit;
    }

    // ✅ Delete computer from database
    $stmt = $conn->prepare("DELETE FROM computers WHERE id = ?");
    $stmt->bind_param("i", $computer_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "cid" => $computer_id]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete computer."]);
    }

    $stmt->close();
    exit;
}
elseif ($action == "store") {
    $spec1 = isset($_POST['spec1']) ? trim($_POST['spec1']) : "";
    $spec2 = isset($_POST['spec2']) ? trim($_POST['spec2']) : "";
    $spec3 = isset($_POST['spec3']) ? trim($_POST['spec3']) : "";
    $spec4 = isset($_POST['spec4']) ? trim($_POST['spec4']) : "";
    $spec5 = isset($_POST['spec5']) ? trim($_POST['spec5']) : "";
    $spec6 = isset($_POST['spec6']) ? trim($_POST['spec6']) : "";
    $spec7 = isset($_POST['spec7']) ? trim($_POST['spec7']) : "";
    $games = isset($_POST['games']) ? json_decode($_POST['games'], true) : [];

    if (!$spec1 || !$spec2 || !$spec3 || !$spec4 || !$spec5 || !$spec6 || !$spec7 || empty($games)) {
        echo json_encode(["success" => false, "message" => "All PC specifications and at least one game are required."]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO computers (spec1, spec2, spec3, spec4, spec5, spec6, spec7) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $spec1, $spec2, $spec3, $spec4, $spec5, $spec6, $spec7);

    if ($stmt->execute()) {
        $pc_id = $stmt->insert_id;

        // Insert selected games into computer_game table
        $stmtGame = $conn->prepare("INSERT INTO computer_game (computer_id, game_id) VALUES (?, ?)");
        foreach ($games as $game_id) {
            $stmtGame->bind_param("ii", $pc_id, $game_id);
            $stmtGame->execute();
        }

        echo json_encode(["success" => true, "cid" => $pc_id]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add PC."]);
    }

    $stmt->close();
    exit;
}
else {
    // If action is invalid
    echo json_encode(["error" => "Invalid action"]);
}
?>
