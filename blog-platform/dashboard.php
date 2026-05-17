<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once 'db.php';

$userId = $_SESSION['user_id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title === '' || $content === '') {
        $message = 'Please enter both a title and content for your vlog.';
    } else {
        $insert = $mysqli->prepare('INSERT INTO vlogs (user_id, title, content) VALUES (?, ?, ?)');
        $insert->bind_param('iss', $userId, $title, $content);
        if ($insert->execute()) {
            header('Location: dashboard.php');
            exit;
        }
        $message = 'Unable to save vlog. Please try again.';
    }
}

$vlogs = [];
$stmt = $mysqli->prepare('SELECT id, title, content, created_at FROM vlogs WHERE user_id = ? ORDER BY created_at DESC');
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $vlogs[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Vlogs | Vlog App</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f8f9fa; color: #333; 
            margin: 0; 
            padding: 0; 
        }
        .header { 
            background: #343a40; 
            color: #fff; 
            padding: 20px; 
        }
        .content { 
            max-width: 900px; 
            margin: 20px auto; 
            padding: 20px; 
        }
        .card { 
            background: #fff; 
            border-radius: 8px; 
            padding: 20px; 
            margin-bottom: 20px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.05); 
        }
        input[type=text], textarea { 
            width: 100%; 
            padding: 10px;
            margin: 8px 0; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
        }
        textarea { 
            min-height: 120px; 
        }
        button { 
            padding: 10px 18px; 
            background: #007bff; 
            color: #fff; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
        }
        button:hover { 
            background: #0056b3; 
        }
        .message { 
            margin-bottom: 16px; 
            color: #d8000c; 
        }
        .vlog-item { 
            margin-bottom: 16px; 
        }
        .vlog-item h3 { 
            margin: 0 0 8px; 
        }
        .vlog-item small { 
            color: #666; 
        }
        .top-bar { 
            display: flex;
            align-items: center; 
            justify-content: space-between; 
            flex-wrap: wrap; 
            gap: 10px; 
        }
        .top-bar a { 
            color: #fff; 
            text-decoration: none; 
            background: #dc3545; 
            padding: 10px 14px; 
            border-radius: 4px; 
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="top-bar">
            <div>
                <p>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>.</p>
            </div>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="content">
        <div class="card">
            <h2>Write a new vlog</h2>
            <?php if ($message): ?>
                <div class="message"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            <form method="POST" action="dashboard.php">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required>
                <label for="content">Content</label>
                <textarea id="content" name="content" required></textarea>
                <button type="submit">Save Vlog</button>
            </form>
        </div>

        <div class="card">
            <h2>Your previous vlogs</h2>
            <?php if (count($vlogs) === 0): ?>
                <p>You have not written any vlogs yet.</p>
            <?php else: ?>
                <?php foreach ($vlogs as $vlog): ?>
                    <div class="vlog-item">
                        <h3><?= htmlspecialchars($vlog['title']) ?></h3>
                        <small>Created on <?= htmlspecialchars($vlog['created_at']) ?></small>
                        <p><?= nl2br(htmlspecialchars($vlog['content'])) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
