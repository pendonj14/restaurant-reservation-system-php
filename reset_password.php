<?php
include("database.php"); // Include your database connection

$error_message = ""; // Initialize error message
$success_message = ""; // Initialize success message

if (!isset($_GET['user_id']) || !filter_var($_GET['user_id'], FILTER_VALIDATE_INT)) {
    die("Invalid access.");
}

$user_id = intval($_GET['user_id']); // Sanitize the user ID

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    if (strlen($new_password) < 6) {
        $error_message = "Password must be at least 6 characters long.";
    } else {
        $hash = password_hash($new_password, PASSWORD_DEFAULT); // Hash the new password

        // Update the password in the database
        $sql = "UPDATE customer SET password = ? WHERE customer_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $hash, $user_id);

        if ($stmt->execute()) {
            $success_message = "Password updated successfully! You can now <a href='index.php' text-decoration: none; color: #fff; font-weight: 600;>log in</a>.";
        } else {
            $error_message = "Failed to update password. Please try again.";
        }
    }
}
?>

<html lang="en">
<head>
    < <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOIRÉIR</title>
    <link rel="stylesheet" href="reset_password.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Forum&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/jpg" href="images/icon.png">
    <script src="reset_password.js" defer></script>
</head>
<body>
    <header class="hero">
        <div class="overlay">
            <h1 class="title">SOIRÉIR</h1>
        </div>
    </header>

    <div class="wrapper">
        <form id="resetForm" action="reset_password.php?user_id=<?php echo htmlspecialchars($user_id); ?>" method="POST">
            <h2>Reset Password</h2>

            <!-- Display success or error messages -->
            <?php
            if (!empty($error_message)) {
                echo "<div class='error-message'>$error_message</div>";
            }
            if (!empty($success_message)) {
                echo "<div class='success-message'>$success_message</div>";
            }
            ?>

            <div class="input-box">
                <input type="password" name="password" placeholder="Enter New Password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>

            <button type="submit" class="btn">Update Password</button>
        </form>
    </div>
</body>
</html>
</html>