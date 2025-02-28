<?php
session_start();
include("database.php"); // Include your database connection file

$error_message = ""; // Initialize error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    // Query to find the user by username
    $sql = "SELECT customer_id, username, password FROM customer WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['customer_id'] = $user['customer_id'];
            $_SESSION['username'] = $user['username'];

            // Redirect to a protected page (e.g., dashboard)
            header("Location: welcomepage.php");
            exit;
        } else {
            $error_message = "Invalid username or password.";
        }
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOIRÉIR</title>
    <link rel="stylesheet" href="styling.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Forum&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/jpg" href="images/icon.png">
    <style>
        /* Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <header class="hero">
        <div class="overlay">
            <h1 class="title">SOIRÉIR</h1>
        </div>
    </header>
    
    
    <div class="wrapper">
        <form action="index.php" method = "post">
            <h2>LOGIN</h2>
            <div class = input-box>
                <input type="text" name = "username" placeholder="Username" required>
                <i class='bx bxs-user'></i>
            </div>
            
            <div class = input-box>
                <input type="password" name = "password" placeholder="Password" required>
                <i class='bx bxs-lock-alt' ></i>
            </div>

            <input type = "submit" name = "login" class = "btn" value = "Login"></input>

            <div class = "register">
                <p>Don't have an account? <a 
                href="register.php">Create Account</a> </p>
            </div>
            
            <div class = "recover" style="font-size: 14.5px; text-align: center; margin: 0px 0 15px; transform:translateY(-5px);">
                <p>Forgot account? <a 
                href="recover.php" style="text-decoration: none; color: #fff; font-weight: 600;" id ="recovertxt">Recover</a> </p>
            </div>
        </form>
    </div>

    <!-- Modal for Error Message -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span id="closeModal" class="close">&times;</span>
            <p id="modalMessage"></p>
        </div>
    </div>

    <script>
        // Modal Control
        const modal = document.getElementById("modal");
        const modalMessage = document.getElementById("modalMessage");
        const closeModal = document.getElementById("closeModal");

        // Show the modal with a message
        function showModal(message) {
            modalMessage.textContent = message;
            modal.style.display = "block";
        }

        // Close the modal when clicking the close button or outside the modal
        closeModal.addEventListener("click", () => {
            modal.style.display = "none";
        });
        window.addEventListener("click", (event) => {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });

        // Display error message in modal if it exists
        <?php if (!empty($error_message)): ?>
            showModal(<?php echo json_encode($error_message); ?>);
        <?php endif; ?>
    </script>
</body>
</html>
