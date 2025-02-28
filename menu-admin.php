<?php
session_start();
include("database.php"); // Include your database connection file

$error_message = ""; // Initialize error message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $title = $_POST['menuName'];
    $details = $_POST['details'];
    $price = $_POST['price'];

    // Handle image upload
    $image = $_FILES['image']['name'];
    $target = "images/".basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        // Insert data into database
        $sql = "INSERT INTO tblmenu (menuName, details, price, image) VALUES ('$title', '$details', '$price', '$image')";
        if ($conn->query($sql) === TRUE) {
            echo "New menu item uploaded successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Failed to upload image.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Menu Upload</title>
    <link rel="stylesheet" href="">
</head>
<body>
    <h2>Upload New Menu Item</h2>
    <form action="menu-admin.php" method="POST" enctype="multipart/form-data">
        <label for="title">Food Title:</label>
        <input type="text" name="menuName" id="menuName" required><br><br>

        <label for="details">Food Details:</label>
        <textarea name="details" id="details" required></textarea><br><br>

        <label for="price">Price:</label>
        <input type="text" name="price" id="price" required><br><br>

        <label for="image">Upload Image:</label>
        <input type="file" name="image" id="image" accept="image/*" required><br><br>

        <button type="submit">Upload Menu Item</button>
    </form>
</body>
</html>
