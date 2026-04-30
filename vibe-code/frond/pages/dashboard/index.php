<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login/index.php");
    exit;
}
$user_id = $_SESSION["user_id"];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Study Planner</title>
</head>
<body>

<h1>Smart Study Planner</h1>

<button onclick="generateSchedule()">Generate Schedule</button>
<button onclick="loadSchedule()">Load Schedule</button>
<button onclick="addTask()">Add Task</button>
<a href="../../includes/logout.php"><button>Logout</button></a>

<div id="output"></div>

<script>
function generateSchedule() {
    const user_id = <?= $user_id ?>;
    fetch("../../ajax/generate_schedule.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "user_id=" + user_id
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
    });
}

function loadSchedule() {
    const user_id = <?= $user_id ?>;
    fetch("../../ajax/get_schedule.php?user_id=" + user_id)
    .then(res => res.json())
    .then(data => {
        let output = "<h2>Your Schedule</h2>";
        data.forEach(item => {
            output += `<p>${item.scheduled_date} - ${item.title} (${item.allocated_hours}h)</p>`;
        });
        document.getElementById("output").innerHTML = output;
    });
}

function addTask() {
    window.location.href = "../add-task/index.php";
}
</script>

</body>
</html>
