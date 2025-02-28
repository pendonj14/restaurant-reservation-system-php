<?php 
    include("database.php");
    include 'floating/floatingmenu.php';
	
    if(isset($_POST['submit'])){

        $customer_id = $_SESSION['customer_id'];
        $bookingdate=$_POST['date'];
        $bookingtime=$_POST['time'];
        $noseats=$_POST['noSeats'];
        $bno=mt_rand(100000000,9999999999);
        //Code for Insertion
        $query = mysqli_query($conn, 
        "INSERT INTO tblbookings (bookingNo, customer_id, bookingDate, bookingTime, noSeats) 
         VALUES ('$bno', '$customer_id', '$bookingdate', '$bookingtime', '$noseats')"
        );
        if($query){
            echo '<script>alert("Your order sent successfully. Booking number is "+"'.$bno.'")</script>';
            echo "<script type='text/javascript'> document.location = 'reservation.php'; </script>";
        } else {
            die("Error inserting data: " . mysqli_error($conn));
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation</title>
    <link rel="stylesheet" href="reservation.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Forum&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/jpg" href="images/icon.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

    <div class = 'left'>
        <div class = 'container'>
            <div class = 'menu'>
                reserve
            </div>

        </div>
    </div>

    <div class="right">
        <div class="rightcontainer">
            
            <div class = "reservationtext"> 
                <h1 id ="header">Reservation</h1>
                <p id="text">Secure your spot at Soiréir, where exceptional food and a remarkable dining experience await.</p>
            </div>

            
            
            <div class="reserve" style="transform: translateY(30px);">
                <form id="reservationForm" onsubmit="return validateForm()" action= "reservation.php" method="POST">
                    <div class="form-field">
                        <label for="name"></label>
                        <input type="number" id="name" name="noSeats" min="1" required placeholder="No of Seats">
                    </div>

                    <div class="form-field">
                        <label for="date"></label>
                        <input type="date" id="date" name="date" required>
                    </div>

                    <div class="form-field">
                        <label for="time"></label>
                        <input type="time" id="time" name="time" required>
                    </div>
                    
                    </div>
                    <div class="form-field">
                        <button type="submit" name = "submit" style="transform: translateY(90px);">Submit Reservation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    

    <script src="menubar.js"></script>
</body>
</html>