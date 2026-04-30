<?php
require_once("../../includes/db_connection.php");

session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login/index.php");
    exit;
}

$user_id = $_SESSION["user_id"];

// Fetch subjects
$result = $conn->query("SELECT id, name FROM subjects WHERE user_id = $user_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Task</title>
</head>
<body>

<h1>Add New Study Task</h1>

<form id="taskForm">
    <label>Title:</label><br>
    <input type="text" name="title" required><br><br>

    <label>Subject:</label><br>
    <select name="subject_id">
        <option value="">Select a subject</option>
        <?php while ($row = $result->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Estimated Hours:</label><br>
    <input type="number" name="estimated_hours" step="0.5" required><br><br>

    <label>Deadline:</label><br>
    <input type="date" name="deadline" required><br><br>

    <button type="submit">Add Task</button>
</form>

<a href="../dashboard/index.php">Back to Dashboard</a>
<a href="../../includes/logout.php">Logout</a>

<script>
document.getElementById("taskForm").addEventListener("submit", function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append("user_id", <?= $user_id ?>);

    fetch("../../ajax/add_task.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.status === "success") {
            document.getElementById("taskForm").reset();
        }
    });
});
</script>

</body>
</html>
