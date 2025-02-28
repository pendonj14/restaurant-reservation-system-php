<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
    include("database.php");

// Ensure the user is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: index.php"); // Redirect to login if not logged in
    exit;
}

// Retrieve the logged-in customer's data
$customerID = intval($_SESSION['customer_id']);
$query = mysqli_query($conn, "SELECT * FROM customer WHERE customer_id = '$customerID'");
$row = mysqli_fetch_assoc($query); // Fetch the customer details
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MenuBar</title>
    <link rel="stylesheet" href="floating/floatingmenu.css">
</head>
<body>
    <div class="floating-menu">
        <!-- Button with icon -->
        <button class="menu-icon-button" onclick="toggleDropdown(event)">
        <i class='bx bxs-user'></i> <!-- Boxicons icon -->
        </button>
        
        <!-- Main menu options -->
        <ul id="menu-options" class="menu-options">
            <li><a href="welcomepage.php" style="font-size: 30px; font-weight: 600; font-family: forum;">SOIRÉIR</a></li>
            <li class="sub"><a href="menu.php">MENU</a></li>
            <li class="sub"><a href="about.php">ABOUT</a></li>
            <li class="sub"><a href="reservation.php">BOOK A TABLE</a></li>
        </ul>
        
        <!-- Dropdown menu (hidden by default) -->
        <div id="dropdown" class="dropdown">
            <ul>
                <!-- Profile Link -->
                <li>
                    <a href="userprofile.php?edit_id=<?php echo $row['customer_id']; ?>" style="font-family: roboto;">Profile</a>
                </li>
                <!-- Logout Form -->
                <li>
                    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?>" method="POST">
                        <button type="submit" id="logout" name="logout" value="logout" style="font-family: roboto; background:none; border:none; cursor:pointer; color:white">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</body>
</html>

<?php
// Handle Logout
if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}
?>
