<?php 
$conn = new mysqli("localhost", "root", "");
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
$conn->query("CREATE DATABASE IF NOT EXISTS quotes");
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotes</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 800px;
        }

        .quote-card {
            background: white;
            padding: 30px;
            margin: 20px 0;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .quote-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }

        .quote-text {
            font-size: 22px;
            color: #333;
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .author {
            text-align: right;
            font-size: 18px;
            color: #764ba2;
            font-weight: bold;
        }

        h1 {
            text-align: center;
            color: white;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <?php 
    $conn = new mysqli("localhost", "root", "", "quotes");
    if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

    $conn->query("CREATE TABLE IF NOT EXISTS quotes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        quote TEXT NOT NULL,
        author TEXT NOT NULL
    )");

    // ✅ Handle Form Submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $quote = $conn->real_escape_string($_POST['quote']);
        $author = $conn->real_escape_string($_POST['author']);

        if (!empty($quote) && !empty($author)) {
            $conn->query("INSERT INTO quotes (quote, author) VALUES ('$quote', '$author')");
        }

        // Prevent form resubmission on refresh
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    ?>  

    <div class='container'>
    <h1>✨ Inspirational Quotes ✨</h1>

    <!-- /* ✅ Add Quote Form */ -->
    <div class='quote-card'>
        <form method='POST' action='<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>'>
            <div class='quote-text'>
                <textarea name='quote' placeholder='Enter your quote...' required 
                style='width:100%;padding:10px;border-radius:8px;border:1px solid #ccc;resize:none;height:80px;'></textarea>
            </div>
            <div style='margin-top:10px;'>
                <input type='text' name='author' placeholder='Author name' required
                style='width:100%;padding:10px;border-radius:8px;border:1px solid #ccc;'>
            </div>
            <div style='text-align:center;margin-top:15px;'>
                <button type='submit' 
                style='background:#764ba2;color:white;border:none;padding:10px 20px;border-radius:8px;cursor:pointer;font-size:16px;'>
                    ➕ Add Quote
                </button>
            </div>
        </form>
    </div>

    <!-- /* ✅ Display Quotes */ -->
    <?php
    $result = $conn->query("SELECT quote, author FROM quotes ORDER BY id DESC");

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $q = htmlspecialchars($row["quote"]);
            $a = htmlspecialchars($row["author"]);

            echo "
            <div class='quote-card'>
                <div class='quote-text'>$q</div>
                <div class='author'>— $a</div>
            </div>";
        }
    } else {
        echo "<p style='color:white;text-align:center;'>No quotes found.</p>";
    }
    ?>
    </div>
</body>
</html>