<?php
session_start();
include('includes/config.php');

// Fetch menu items from the database
$sql = "SELECT * FROM tblmenu";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

// Handle delete operation
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql_delete = "DELETE FROM tblmenu WHERE menuID = $delete_id"; // Corrected column name to 'menuID'
    
    if ($conn->query($sql_delete) === TRUE) {
        echo "Menu item deleted successfully!";
    } else {
        echo "Error deleting item: " . $conn->error;
    }
}

// Handle edit operation (redirect to edit page with the menu item details)
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    header("Location: editmenu.php?id=$edit_id"); // Correct file name for edit page
    exit;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Restaurent Table Booking System   | Change Password</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <!--Function Email Availabilty---->
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
<?php include_once("includes/navbar.php");?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
 <?php include_once("includes/sidebar.php");?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Manage Menu</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Food Name</th>
                                            <th>Details</th>
                                            <th>Price</th>
                                            <th>Image</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?php echo $row['menuName']; ?></td>
                                                <td><?php echo $row['details']; ?></td>
                                                <td><?php echo '₱' . number_format($row['price'], 2); ?></td>
                                                <td><img src="images/<?php echo $row['image']; ?>" alt="Food Image" width="100"></td>
                                                <td>
                                                    <!-- Edit and Delete links -->
                                                    <a href="editmenu.php?edit_id=<?php echo $row['menuID']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                    <a href="deletemenu.php?delete_id=<?php echo $row['menuID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include_once('includes/footer.php'); ?>
</div>

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- bs-custom-file-input -->
<script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
<!-- Page specific script -->
<script>
$(function () {
  bsCustomFileInput.init();
});
</script>
</body>
</html>
<?php 
?>
