<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    // Generate default password as username + 'utmkl'
    $plaintext_password = $username . 'utmkl';

    // Hash the default password
    $hashed_password = password_hash($plaintext_password, PASSWORD_DEFAULT);

    // Insert the user into the database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>User added successfully! Default password is: " . htmlspecialchars($plaintext_password) . "</p>";
    } else {
        if ($stmt->errno === 1062) { // Duplicate entry
            echo "<p style='color:red;'>Username or email already exists!</p>";
        } else {
            echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Add User</title>
</head>
<body>
    <h1>Add User Manually</h1>
    <form action="" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Enter username" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter email" required><br><br>

        <button type="submit">Add User</button>
    </form>
</body>
</html>
