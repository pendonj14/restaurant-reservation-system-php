<?php
session_start();
// Database Connection
include('database.php');
include('floating/floatingmenu.php');

// Validating Session
if (strlen($_SESSION['customer_id']) == 0) { 
    header('location: index.php');
} else {
    $customer_id = intval($_SESSION['customer_id']); // Get user ID from session

    // Handle Account Deletion
    if (isset($_POST['delete_account'])) {
        $deleteQuery = mysqli_query($conn, "DELETE FROM customer WHERE customer_id='$customer_id'");
        if ($deleteQuery) {
            session_unset();
            session_destroy();
            echo "<script>alert('Your account has been deleted successfully.');</script>";
            echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
        } else {
            echo "<script>alert('Error deleting account. Please try again.');</script>";
        }
    }

    if (isset($_POST['update'])) {
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];

        // Build the base update query
        $updateQuery = "UPDATE customer SET email='$email', phone_number='$phone_number'";

        // Check if the password field is not empty
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash new password
            $updateQuery .= ", password='$password'";
        }

        // Complete the query
        $updateQuery .= " WHERE customer_id='$customer_id'";

        // Execute the update query
        $query = mysqli_query($conn, $updateQuery);
        if ($query) {
            echo "<script>alert('Profile details updated successfully.');</script>";
            echo "<script type='text/javascript'> document.location = 'welcomepage.php'; </script>";
        } else {
            echo "<script>alert('Something went wrong. Please try again.');</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restaurant System | My Profile</title>
    <link rel="stylesheet" href="userprofile.css">
    <!-- Google Fonts -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Forum&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <?php 
    $query = mysqli_query($conn, "SELECT * FROM customer WHERE customer_id='$customer_id'");
    $result = mysqli_fetch_array($query);
    ?>
    <section class="content">
        <div class="container-fluid">
            <!-- Profile Update Form -->
            <div class="card-primary">
                <div class="card-header">
                    <h3 class="card-title">PROFILE</h3>
                </div>
                <form name="user_profile" method="post">
                    <div class="card-body">
                        <!-- Username (read-only) -->
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" class="form-control" value="<?php echo htmlspecialchars($result['username']); ?>" readonly>
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter new password (leave blank to keep current password)">
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($result['email']); ?>" required>
                        </div>

                        <!-- Phone Number -->
                        <div class="form-group">
                            <label for="phone_number">Phone Number</label>
                            <input type="text" name="phone_number" id="phone_number" class="form-control" value="<?php echo htmlspecialchars($result['phone_number']); ?>" pattern="[0-9]{11}" title="11 numeric characters only" maxlength="11" required>
                        </div>

                        <div class="submit-group">
                            <button class="testbutton" type="submit" name="update" id="update">Update</button>
                        </div>

                        <!-- Delete Account Button -->
                        <div class="delete-group" style="text-align: center; margin-top: 15px;">
                            <button type="button" id="deleteAccount" class="btn btn-danger" style="color: white; text-align: center; margin-top: 15px; background:none; border:none; ">Delete Account</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<!-- Modal for Delete Confirmation -->
<div id="deleteModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); z-index:1000;">
    <div class="modal-content" style="background:#fff; padding:20px; margin:15% auto; width:300px; border-radius:8px; text-align:center;">
        <p>Are you sure you want to delete your account?</p>
        <form method="post" style="margin-top: 15px;">
            <button type="submit" name="delete_account" class="btn btn-danger" style="margin-right: 10px;">Yes</button>
            <button type="button" id="cancelDelete" class="btn btn-secondary">No</button>
        </form>
    </div>
</div>

<script>
    // Show Delete Modal
    document.getElementById('deleteAccount').addEventListener('click', function() {
        document.getElementById('deleteModal').style.display = 'block';
    });

    // Hide Delete Modal
    document.getElementById('cancelDelete').addEventListener('click', function() {
        document.getElementById('deleteModal').style.display = 'none';
    });

    // Hide modal when clicking outside of it
    window.onclick = function(event) {
        const modal = document.getElementById('deleteModal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    };
</script>
<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
<script src="menubar.js"></script>
</body>
</html>
<?php } ?>
