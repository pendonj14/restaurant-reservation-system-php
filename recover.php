<?php
include("database.php"); // Include database connection
$error_message = ""; // Initialize error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $phonenumber = filter_input(INPUT_POST, "phonenumber", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);

    // Query to match user data
    $sql = "SELECT customer_id FROM customer WHERE username = ? AND phone_number = ? AND email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $phonenumber, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['customer_id'];

        // Redirect to password reset page with user ID
        header("Location: reset_password.php?user_id=" . $user_id);
        exit;
    } else {
        $error_message = "No matching account found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOIRÉIR</title>
    <link rel="stylesheet" href="recover.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Forum&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/jpg" href="images/icon.png">
    <script src="recover.js" defer></script>
</head>
<body>
    <header class="hero">
        <div class="overlay">
            <h1 class="title">SOIRÉIR</h1>
        </div>
    </header>

    <div class="wrapper">
        <form id="recoverForm" action="recover.php" method="POST">
            <h2>RECOVER</h2>

            <?php
            if (!empty($error_message)) {
                echo "<div class='error-message'>$error_message</div>";
            }
            ?>

            <div class="input-box">
                <input type="text" name="username" placeholder="Username" required>
                <i class='bx bxs-user'></i>
            </div>

            <div class="input-box">
                <input type="tel" name="phonenumber" placeholder="Phone Number" required>
                <i class='bx bxs-phone'></i>
            </div>

            <div class="input-box">
                <input type="email" name="email" placeholder="Email" required>
                <i class='bx bx-envelope'></i>
            </div>

            <button type="submit" class="btn">Recover</button>

            <div class="register">
                <p>Already have an account? <a href="index.php">Login</a></p>
            </div>
        </form>
    </div>
</body>
</html>
