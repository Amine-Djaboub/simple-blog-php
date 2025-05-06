<?php
//$host = "localhost";
//$dbname = "BlogDB";
//$username = "root";
//$password = "";

require_once "db.php";

$register_error = "";
$register_success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST["username"];
    $pass = $_POST["password"];

    // Check if username exists
    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $user);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $register_error = "Username already taken.";
    } else {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $insert = $conn->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
        $insert->bind_param("ss", $user, $hash);
        if ($insert->execute()) {
            $register_success = "User registered! You can now <a href='index.php'>log in</a>.";
        } else {
            $register_error = "Registration failed.";
        }
    }

    $check->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Simple Blog</title>
</head>
<body>
    <h2>Register</h2>

    <?php if ($register_error): ?>
        <p style="color:red;"><?= htmlspecialchars($register_error) ?></p>
    <?php endif; ?>

    <?php if ($register_success): ?>
        <p style="color:green;"><?= $register_success ?></p>
    <?php endif; ?>

    <form method="POST" action="register.php">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Register</button>
    </form>

    <p><a href="index.php">Back to Login</a></p>
</body>
</html>