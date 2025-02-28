<?php
include("database.php");

$response = array("success" => false, "message" => "");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = filter_input(INPUT_POST, "fullname", FILTER_SANITIZE_SPECIAL_CHARS); // Get full name
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
    $phone_number = filter_input(INPUT_POST, "phonenumber", FILTER_SANITIZE_NUMBER_INT);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);

    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Check if username exists
    $check_sql = "SELECT * FROM customer WHERE username = '$username'";
    $result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($result) > 0) {
        // Username exists
        $response["message"] = "Username is already taken.";
    } else {
        // Insert new user
        $sql = "INSERT INTO customer (fullName, username, password, phone_number, email)
                VALUES ('$fullName', '$username', '$hash', '$phone_number', '$email')";
        if (mysqli_query($conn, $sql)) {
            $response["success"] = true;
            $response["message"] = "Registration successful! Please log in.";
        } else {
            $response["message"] = "Error: " . mysqli_error($conn);
        }
    }

    // Return JSON response if requested via AJAX
    if (!empty($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}

mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOIRÉIR</title>
    <link rel="stylesheet" href="regstyle.css">
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
        .close:hover, .close:focus {
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

    <div class="wrapper" style="transform: translateY(20px);">
        <form id="registerForm">
            <h2 style="font-family: forum;">REGISTER</h2>
            
            <div class="input-box">
                <input type="text" name="fullname" placeholder="Full Name" required>
                <i class='bx bxs-user'></i>
            </div>
            
            <div class="input-box">
                <input type="text" name="username" placeholder="Username" required>
                <i class='bx bxs-user'></i>
            </div>
            
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>

            <div class="input-box">
                <input type="tel" name="phonenumber" placeholder="Phone Number" required>
                <i class='bx bxs-phone'></i>
            </div>

            <div class="input-box">
                <input type="email" name="email" placeholder="Email" required>
                <i class='bx bx-envelope'></i>
            </div>
            
            <button type="submit" class="btn">Register</button>
        
            <div class = "register">
                <p>Already have an account? <a 
                href="index.php">Login</a> </p>
            </div>
        
        </form>
    </div>

    <!-- Modal for messages -->
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

        function showModal(message) {
            modalMessage.textContent = message; // Set modal content
            modal.style.display = "block"; // Show modal
        }

        closeModal.addEventListener("click", () => {
            modal.style.display = "none"; // Close modal
        });

        window.addEventListener("click", (event) => {
            if (event.target === modal) {
                modal.style.display = "none"; // Close when clicking outside
            }
        });

        // Form Submission with AJAX
        document.getElementById("registerForm").addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent default form submission
        
            const formData = new FormData(this); // Gather form data

            // Basic validation for full name
            const fullName = formData.get("fullname");
            if (!fullName.trim()) {
                showModal("Please enter your full name.");
                return;
            }

            // AJAX request
            fetch("register.php", {
                method: "POST",
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        showModal(data.message); // Show success modal
                        this.reset(); // Clear form
                    } else {
                        showModal(data.message); // Show error modal
                    }
                })
                .catch((error) => {
                    showModal("An unexpected error occurred. Please try again.");
                    console.error("Error:", error);
                });
        });
    </script>
</body>
</html>
