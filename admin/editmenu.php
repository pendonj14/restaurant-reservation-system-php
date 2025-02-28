<?php
session_start();
include('includes/config.php');

// Validate the 'id' parameter
if (!isset($_GET['edit_id']) || empty($_GET['edit_id'])) {
    die("Error: Missing or invalid menu item ID.");
}
$menuID = $_GET['edit_id'];

// Fetch the menu item details
$sql = "SELECT * FROM tblmenu WHERE menuID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $menuID); // Bind the menuID as an integer
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Error: Menu item not found.");
}

// Fetch the menu item into $menu_item
$menu_item = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $menuName = $_POST['menuName'];
    $details = $_POST['details'];
    $price = $_POST['price'];

    // Handle image upload if provided
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = "images/" . basename($image);

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            die("Error uploading image.");
        }
    } else {
        $image = $menu_item['image']; // Keep the current image if no new image is uploaded
    }

    // Update the menu item
    $sql_update = "UPDATE tblmenu SET menuName = ?, details = ?, price = ?, image = ? WHERE menuID = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssdsi", $menuName, $details, $price, $image, $menuID);

    if ($stmt_update->execute()) {
        echo "Menu item updated successfully!";
        header("Location: deletemenu.php"); // Redirect back to the menu list page
        exit;
    } else {
        die("Error updating menu item: " . $conn->error);
    }
}
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
                                <form method="post" name="bwdatesreport" enctype="multipart/form-data">
                                    <div class="form-group" style = 'width: 500px; transform:translateX(150px)'>
                                        <label for="menuName">Food Name:</label>
                                        <input class="form-control " type="text" name="menuName" id="menuName" value="<?php echo htmlspecialchars($menu_item['menuName']); ?>" required><br>

                                        <label for="details">Details:</label>
                                        <input class="form-control " type="textarea" name="details" id="details" value="<?php echo htmlspecialchars($menu_item['details']); ?>" required><br>

                                        <label for="price">Price:</label>
                                        <input class="form-control " type="number" name="price" id="price" value="<?php echo htmlspecialchars($menu_item['price']); ?>" step="0.01" required><br>

                                        <label for="image">Image:</label>
                                        <input class="form-control " type="file" name="image" id="image"><br>
                                        <img src="images/<?php echo htmlspecialchars($menu_item['image']); ?>" alt="Food Image" width="100"><br>
                                    </div>
                                    
                                    <div class="card-footer" >
                                    <button type="submit" style="transform: translateX(300px);">Save Changes</button>
                                    </div>
                                </form>
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
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
