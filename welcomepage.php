<?php
    session_start();
    include 'floating/floatingmenu.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOIRÉIR</title>
    <link rel="stylesheet" href="welcomepage.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Forum&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/jpg" href="images/icon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
</head>

    
    <div class="container">
        <!-- Main content section with the background image -->
        
        <div class="main-content">
            <h1>SOIRÉIR</h1>
        </div>
        
        <div class="video-background">
            <video autoplay muted loop class="background-video">
                <source src="videos/menu.mp4" type="video/mp4">
                <!-- Add other video formats if needed for compatibility -->
                Your browser does not support the video tag.
            </video>
        </div>
        
        <!-- Side menu with four items -->
        <div class="side-menu">
            <!-- Menu item 1 (Replace 'image1.jpg' with your image file for "Menu") -->
            <div class="menu-item">
                <a href="menu.php"><img src="images/menu.jpg" alt="Menu" /></a>
                <p class="tiks">menu</p>
            </div>
            
            <!-- Menu item 2 (Replace 'image2.jpg' with your image file for "Reservation") -->
            <div class="menu-item">
                <a href="reservation.php"><img src="images/reserve.jpg" alt="Reservation" /></a>
                <p class="tiks">Reservation</p>
            </div>
            
            <!-- Menu item 3 (Replace 'image3.jpg' with your image file for "Our Restaurant") -->
            <div class="menu-item">
                <a href="about.php"> <img src="images/dahon.jpg" alt="Our Restaurant" /> </a>
                <p class="tiks">Our Restaurant</p>
            </div>
            
            <!-- Menu item 4 (Replace 'image4.jpg' with your image file for "Our Interior") -->
        </div>
    </div>

    <script src="menubar.js"></script>
</body>
</html>
