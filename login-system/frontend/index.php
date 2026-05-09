<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        form {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        label {
            font-family: Arial, sans-serif;
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
            text-transform: capitalize;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="email"]:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        input[type="submit"]:active {
            background-color: #004085;
        }

        a {
            font-size: 1.5rem;
            color: black;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <form action="./data/handleRegister.php" method="POST">
        <h1>Register</h1>
        <br>
        <label>name</label>
        <input type="text" name="name" require>
        <br>
        <label>email</label>
        <input type="email" name="email" require>
        <br>
        <label>password</label>
        <input type="password" name="password" require>
        <br>
        <input type="submit">
        <br>
        <br>
        <a href="./login/index.php">Log in</a>
    </form>
</body>
</html>