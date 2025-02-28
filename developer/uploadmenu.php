<?php 
session_start();
// Database Connection
include('includes/config.php');
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $startD = $_POST['start_date'];
    $endD = $_POST['end_date'];
    $planT = $_POST['plan_type'];
    $renewalF = $_POST['renewal_frequency'];
    $status = $_POST['status'];
    $manager = intval($_POST['restaurant_managerID']); // Ensure it's an integer
    $image = $_FILES['logo']['name']; // Handle image upload
    $target = "images/" . basename($image);

    // Validate and upload the image
    if (move_uploaded_file($_FILES['logo']['tmp_name'], $target)) {
        // Use prepared statements to avoid SQL injection
        $stmt = $conn->prepare("INSERT INTO subscription (start_date, end_date, plan_type, renewal_frequency, status, restaurant_managerID, logo) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssis", $startD, $endD, $planT, $renewalF, $status, $manager, $image);

        if ($stmt->execute()) {
            echo "New Subscriber Added";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Failed to upload image.";
    }
    $stmt->close();
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

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 style = "transform: translateX(200px);">Upload Menu Items</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
              <li class="breadcrumb-item active" >Upload Menu Items</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-8">
            <!-- general form elements -->
            <div class="card card-primary" style = "transform: translateX(200px);">
              <div class="card-header">
                <h3 class="card-title">Upload Menu Items</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method="post" action="uploadmenu.php" enctype="multipart/form-data">
              <div class="card-body">

                  <!-- Start Date -->
                  <div class="form-group">
                      <label for="start_date">Subscription Start:</label>
                      <input class="form-control" id="start_date" name="start_date" type="date" required>
                  </div>

                  <!-- End Date -->
                  <div class="form-group">
                      <label for="end_date">Subscription End:</label>
                      <input class="form-control" id="end_date" name="end_date" type="date" required>
                  </div>

                  <!-- Plan Type -->
                  <div class="form-group">
                      <label for="plan_type">Plan Type:</label>
                      <select class="form-control" id="plan_type" name="plan_type" required>
                          <option value="PREMIUM">PREMIUM</option>
                          <option value="STANDARD">STANDARD</option>
                      </select>
                  </div>

                  <!-- Renewal Frequency -->
                  <div class="form-group">
                      <label for="renewal_frequency">Renewal Frequency:</label>
                      <select class="form-control" id="renewal_frequency" name="renewal_frequency" required>
                          <option value="YEARLY">YEARLY</option>
                          <option value="MONTHLY">MONTHLY</option>
                      </select>
                  </div>

                  <!-- Status -->
                  <div class="form-group">
                      <label for="status">Status:</label>
                      <select class="form-control" id="status" name="status" required>
                          <option value="PAID">PAID</option>
                          <option value="NOT PAID">NOT PAID</option>
                          <option value="ON HOLD">ON HOLD</option>
                      </select>
                  </div>

                  <!-- Manager -->
                  <div class="form-group">
                      <label for="restaurant_managerID">Manager:</label>
                      <input class="form-control" id="restaurant_managerID" name="restaurant_managerID" type="number" required>
                  </div>

                  <!-- Logo -->
                  <div class="form-group">
                      <label for="logo">Restaurant Logo:</label>
                      <input class="form-control" id="logo" name="logo" type="file" accept="image/*" required>
                  </div>

              </div>
              <div class="card-footer">
                  <button type="submit" class="btn btn-primary" name="submit">Upload Subscriber</button>
              </div>
          </form>

            <!-- /.card -->

        
       
          </div>
          <!--/.col (left) -->
  
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php include_once('includes/footer.php');?>

</div>
<!-- ./wrapper -->

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
