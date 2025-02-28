<?php session_start();
// Database Connection
include('includes/config.php');
$error_message = "";

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
<form method="post"  name="bwdatesreport" action="uploadmenu.php" enctype="multipart/form-data">  
                <div class="card-body">

<!-- From Date--->
          <div class="form-group">
            <label for="exampleInputFullname">Food Name:</label>
            <input class="form-control" id="menuName" name="menuName"  type="text" required="true">
          </div>
<!---To Date---->
          <div class="form-group">
            <label for="exampleInputEmail1">Food Details:</label>
            <input class="form-control " id="details" type="text" name="details" required="true">
          </div>

          <div class="form-group">
            <label for="exampleInputEmail1">Price:</label>
            <input class="form-control " id="price" type="text" name="price" required="true">
          </div>

          <div class="form-group">
            <label for="exampleInputEmail1">Upload Image:</label>
            <input class="form-control " id="image" type="file" accept = "image/*" name="image" required="true">
          </div>

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary" name="submit" id="submit">Upload Menu Item</button>
                </div>
              </form>
</div>
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
