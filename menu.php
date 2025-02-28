<?php
session_start();
include("database.php"); // Include your database connection file
include 'floating/floatingmenu.php';

$error_message = ""; // Initialize error message

$sql = "SELECT * FROM tblmenu";
$result = $conn->query($sql);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Menu Layout</title>
    <link rel="stylesheet" href="menu.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Forum&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.1/css/boxicons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

</head>
<body>

    

    <div class = 'left'>
        <div class = 'container'>
            <div class = 'menu'>
                Menu
            </div>

        </div>
    </div>

    <div class="right" style="background-color: transparent">
        <div class="menu-container">
            <?php
            if ($result->num_rows > 0) {
                // Output each menu item
                while($row = $result->fetch_assoc()) {
                    echo "<div class='menu-item'>";
                    echo "<img src='images/" . $row['image'] . "' alt='" . $row['menuName'] . "'>";
                    echo "<div class='item-details'>";
                    echo "<h3>" . $row['menuName'] . "</h3>";
                    echo "<p>" . $row['details'] . "</p>";
                    echo "<p class='price'>₱" . $row['price'] . "</p>";
                    echo "</div></div>";
                }
            } else {
                echo "No menu items available.";
            }
            ?>
        </div>
    </div>

    <script src="menubar.js"></script>
</body>
</html>

<?php
$conn->close();
?>
