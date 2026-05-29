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
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $isPublicRaw = $_POST['is_public'] ?? 'false';
    $isPublic = $isPublicRaw === 'true' ? 1 : 0;

    if (empty($title) || empty($content)) {
        $message = 'Please enter both a title and content for your blog.';
    } else {
        $insert = $conn->prepare('INSERT INTO blogs (user_id, title, content, is_public) VALUES (?, ?, ?, ?)');
        $insert->bind_param('issi', $userId, $title, $content, $isPublic);
        if ($insert->execute()) {
            header('Location: dashboard.php');
            exit;
        }
        $message = 'Unable to save blog. Please try again.';
    }
}

$vlogs = [];
$stmt = $conn->prepare('SELECT id, title, content, created_at, is_public FROM blogs WHERE user_id = ? ORDER BY created_at DESC');
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
    <title>Your Blogs | Blog App</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f8f9fa; color: #333; 
            margin: 0; 
            padding: 0; 
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
        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #333333;
        }

        ul li {
            float: left;
        }

        ul li a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        ul li div {
            display: flex;
            justify-content: center;
        }
        ul li div {
            margin: 0;
        }
        ul li div {
            border-radius: 4px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <ul>
        <li><a href="dashboard.php">Home</a></li>
        <li><a href="AllBlogs.php">Others blog</a></li>
        <li style="float:right">
            <div>
                <a>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>.</a>
                <a style="background: #dc3545;" href="logout.php">Logout</a>
            </div>
        </li>
    </ul>
    <div class="content">
        <div class="card">
            <h2>Write a new blog</h2>
            <?php if ($message): ?>
                <div class="message"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            <form method="POST" action="dashboard.php">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required>
                <label for="content">Content</label>
                <textarea id="content" name="content" required></textarea>
                <br/>
                <div class="field-group">
                    <span>Public</span>
                    <label><input type="radio" name="is_public" value="true"> true</label>
                    <label><input type="radio" name="is_public" value="false"> false</label>
                </div>
                <br/>
                <button type="submit">Save blog</button>
            </form>
        </div>

        <div class="card">
            <h2>Your previous blogs</h2>
            <?php if (count($vlogs) === 0): ?>
                <p>You have not written any blogs yet.</p>
            <?php else: ?>
                <?php foreach ($vlogs as $vlog): ?>
                    <div class="vlog-item">
                        <h3><?= htmlspecialchars($vlog['title']) ?></h3>
                        <small>Created on <?= htmlspecialchars($vlog['created_at']) ?> | Public: <?= $vlog['is_public'] ? 'true' : 'false' ?></small>
                        <p><?= nl2br(htmlspecialchars($vlog['content'])) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
