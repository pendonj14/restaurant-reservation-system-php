<?php
session_start();
include('includes/config.php');

// Fetch subscription data from the database
$sql = "SELECT * FROM subscription";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

// Handle delete operation
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']); // Ensure it's an integer for security
    $sql_delete = "DELETE FROM subscription WHERE subscription_id = $delete_id";
    
    if ($conn->query($sql_delete) === TRUE) {
        echo "<script>alert('Subscription deleted successfully!');</script>";
        echo "<script>window.location.href = 'managesubscriptions.php';</script>";
    } else {
        echo "Error deleting subscription: " . $conn->error;
    }
}

// Handle edit operation (redirect to edit page with the subscription details)
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']); // Ensure it's an integer for security
    header("Location: editsubscription.php?id=$edit_id");
    exit;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Restaurant System | Manage Subscriptions</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
<?php include_once("includes/navbar.php"); ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
 <?php include_once("includes/sidebar.php"); ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Manage Subscriptions</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Logo</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Plan Type</th>
                                            <th>Renewal Frequency</th>
                                            <th>Status</th>
                                            <th>Manager ID</th>
                                            
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $cnt = 1; while ($row = $result->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?php echo $cnt++; ?></td>
                                                <td><img src="images/<?php echo $row['logo']; ?>" alt="Logo" width="100"></td>
                                                <td><?php echo $row['start_date']; ?></td>
                                                <td><?php echo $row['end_date']; ?></td>
                                                <td><?php echo $row['plan_type']; ?></td>
                                                <td><?php echo $row['renewal_frequency']; ?></td>
                                                <td><?php echo $row['status']; ?></td>
                                                <td><?php echo $row['restaurant_managerID']; ?></td>
                                                
                                                <td>
                                                    <!-- Edit and Delete links -->
                                                    <a href="editsubscription.php?edit_id=<?php echo $row['subscription_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                    <a href="managesubscriptions.php?delete_id=<?php echo $row['subscription_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this subscription?')">Delete</a>
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
<script>
$(function () {
  bsCustomFileInput.init();
});
</script>
</body>
</html>
