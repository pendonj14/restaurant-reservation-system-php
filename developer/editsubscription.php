<?php
session_start();
include('includes/config.php');

// Validate the 'id' parameter
if (!isset($_GET['edit_id']) || empty($_GET['edit_id'])) {
    die("Error: Missing or invalid subscription ID.");
}
$subscriptionID = intval($_GET['edit_id']);

// Fetch the subscription details
$sql = "SELECT * FROM subscription WHERE subscription_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $subscriptionID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Error: Subscription not found.");
}

// Fetch the subscription into $subscription_item
$subscription_item = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $plan_type = $_POST['plan_type'];
    $renewal_frequency = $_POST['renewal_frequency'];
    $status = $_POST['status'];
    $manager_id = intval($_POST['restaurant_managerID']);

    // Handle logo upload if provided
    if (!empty($_FILES['logo']['name'])) {
        $logo = $_FILES['logo']['name'];
        $target = "images/" . basename($logo);

        if (!move_uploaded_file($_FILES['logo']['tmp_name'], $target)) {
            die("Error uploading logo.");
        }
    } else {
        $logo = $subscription_item['logo']; // Keep the current logo if no new logo is uploaded
    }

    // Update the subscription details
    $sql_update = "UPDATE subscription SET start_date = ?, end_date = ?, plan_type = ?, renewal_frequency = ?, status = ?, restaurant_managerID = ?, logo = ? WHERE subscription_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sssssssi", $start_date, $end_date, $plan_type, $renewal_frequency, $status, $manager_id, $logo, $subscriptionID);

    if ($stmt_update->execute()) {
        echo "<script>alert('Subscription updated successfully!');</script>";
        echo "<script>window.location.href = 'managesubscriptions.php';</script>";
        exit;
    } else {
        die("Error updating subscription: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Subscription</title>

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

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Subscription</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
              <li class="breadcrumb-item active">Edit Subscription</li>
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
            <div class="card card-primary" style="transform: translateX(200px);">
              <div class="card-header">
                <h3 class="card-title">Edit Subscription</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method="post" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="form-group">
                    <label for="start_date">Start Date:</label>
                    <input class="form-control" type="date" name="start_date" id="start_date" value="<?php echo htmlspecialchars($subscription_item['start_date']); ?>" required>
                  </div>

                  <div class="form-group">
                    <label for="end_date">End Date:</label>
                    <input class="form-control" type="date" name="end_date" id="end_date" value="<?php echo htmlspecialchars($subscription_item['end_date']); ?>" required>
                  </div>

                  <div class="form-group">
                    <label for="plan_type">Plan Type:</label>
                    <select class="form-control" name="plan_type" id="plan_type" required>
                      <option value="PREMIUM" <?php echo $subscription_item['plan_type'] === 'PREMIUM' ? 'selected' : ''; ?>>Premium</option>
                      <option value="STANDARD" <?php echo $subscription_item['plan_type'] === 'STANDARD' ? 'selected' : ''; ?>>Standard</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="renewal_frequency">Renewal Frequency:</label>
                    <select class="form-control" name="renewal_frequency" id="renewal_frequency" required>
                      <option value="YEARLY" <?php echo $subscription_item['renewal_frequency'] === 'YEARLY' ? 'selected' : ''; ?>>Yearly</option>
                      <option value="MONTHLY" <?php echo $subscription_item['renewal_frequency'] === 'MONTHLY' ? 'selected' : ''; ?>>Monthly</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="status">Status:</label>
                    <select class="form-control" name="status" id="status" required>
                      <option value="PAID" <?php echo $subscription_item['status'] === 'PAID' ? 'selected' : ''; ?>>Paid</option>
                      <option value="NOT PAID" <?php echo $subscription_item['status'] === 'NOT PAID' ? 'selected' : ''; ?>>Not Paid</option>
                      <option value="ON HOLD" <?php echo $subscription_item['status'] === 'ON HOLD' ? 'selected' : ''; ?>>On Hold</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="restaurant_managerID">Manager ID:</label>
                    <input class="form-control" type="text" name="restaurant_managerID" id="restaurant_managerID" value="<?php echo htmlspecialchars($subscription_item['restaurant_managerID']); ?>" required>
                  </div>

                  <div class="form-group">
                    <label for="logo">Logo:</label>
                    <input class="form-control" type="file" name="logo" id="logo">
                    <br>
                    <img src="images/<?php echo htmlspecialchars($subscription_item['logo']); ?>" alt="Logo" width="100">
                  </div>

                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>
<!-- ./wrapper -->

<!-- Scripts -->
<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
</body>
</html>
