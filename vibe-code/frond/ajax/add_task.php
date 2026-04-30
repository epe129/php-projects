<?php
header("Content-Type: application/json");

try {
    require_once("../includes/db_connection.php");

    $title = $_POST['title'] ?? '';
    $subject_id = intval($_POST['subject_id'] ?? 0);
    $user_id = intval($_POST['user_id'] ?? 0);
    $hours = floatval($_POST['estimated_hours'] ?? 0);
    $deadline = $_POST['deadline'] ?? '';

    if (empty($title) || !$user_id || !$hours || empty($deadline)) {
        echo json_encode(["status" => "error", "message" => "Missing required fields"]);
        exit;
    }

    // Ensure "test" subject exists for the user
    $subject_check = $conn->prepare("SELECT id FROM subjects WHERE name = 'test' AND user_id = ?");
    $subject_check->bind_param("i", $user_id);
    $subject_check->execute();
    $subject_result = $subject_check->get_result();

    if ($subject_result->num_rows === 0) {
        // Create "test" subject if it doesn't exist
        $create_subject = $conn->prepare("INSERT INTO subjects (user_id, name) VALUES (?, 'test')");
        $create_subject->bind_param("i", $user_id);
        $create_subject->execute();
        $subject_id = $conn->insert_id;
        $create_subject->close();
    } else {
        $subject_row = $subject_result->fetch_assoc();
        $subject_id = $subject_row['id'];
    }
    $subject_check->close();


    $stmt = $conn->prepare("
        INSERT INTO tasks
        (user_id, subject_id, title, estimated_hours, remaining_hours, deadline)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Database prepare failed: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("iisdds", $user_id, $subject_id, $title, $hours, $hours, $deadline);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Task added successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error adding task: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Exception: " . $e->getMessage()]);
}
?>