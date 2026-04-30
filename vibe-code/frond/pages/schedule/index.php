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
    <title>My Schedule</title>
</head>
<body>

<h1>My Study Schedule</h1>

<button onclick="generateSchedule()">Generate Schedule</button>
<button onclick="loadSchedule()">Refresh</button>
<a href="../../includes/logout.php"><button>Logout</button></a>

<div id="schedule"></div>

<script>
function generateSchedule() {
    fetch("../../ajax/generate_schedule.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "user_id=1"
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
    });
}

function loadSchedule() {
    fetch("../../ajax/get_schedule.php?user_id=1")
    .then(res => res.json())
    .then(data => {
        let output = "<h2>Your Schedule</h2>";
        data.forEach(item => {
            output += `<p>${item.scheduled_date} - ${item.title} (${item.allocated_hours}h)</p>`;
        });
        document.getElementById("schedule").innerHTML = output;
    });
}
</script>

</body>
</html>
