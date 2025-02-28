<?php 
session_start();
include('includes/config.php');
include('includes/navbar.php');
include('includes/sidebar.php');

// Validating Session
if (strlen($_SESSION['aid']) == 0) { 
    header('location:index.php');
} else {
    // Handle Deletion
    if (isset($_POST['delete'])) {
        $bid = intval($_POST['bid']);
        $deleteQuery = mysqli_query($conn, "DELETE FROM tblbookings WHERE id = '$bid'");
        if ($deleteQuery) {
            echo "<script>alert('Reservation deleted successfully.');</script>";
            echo "<script type='text/javascript'> document.location = 'all-bookings.php'; </script>";
        } else {
            echo "<script>alert('Error deleting reservation: " . mysqli_error($conn) . "');</script>";
        }
    }

    // Handle Booking Status Update
    if (isset($_POST['submit'])) {
        $bid = intval($_GET['bid']);
        $estatus = $_POST['status'];
        $oremark = $_POST['officialremak'];
        $bdate = $_POST['bdate'];
        $btime = strtotime($_POST['btime']);
        $tableid = isset($_POST['table']) ? $_POST['table'] : NULL;

        if ($estatus === 'Accepted') {
            // Check if table is already booked
            $endTime = date("H:i:s", strtotime('+30 minutes', $btime));
            $ret = mysqli_query($conn, "SELECT * FROM tblbookings 
                                        WHERE ('$btime' BETWEEN TIME(bookingTime) AND '$endTime' 
                                        OR '$endTime' BETWEEN TIME(bookingTime) AND '$endTime' 
                                        OR bookingTime BETWEEN '$btime' AND '$endTime') 
                                        AND tableId='$tableid' 
                                        AND bookingDate='$bdate' 
                                        AND boookingStatus='Accepted'");
            if (mysqli_num_rows($ret) > 0) {
                echo "<script>alert('Table already booked for given Date and Time. Please choose another table.');</script>";
                exit;
            }

            // Validate if table exists in tblrestables
            $checkTable = mysqli_query($conn, "SELECT id FROM tblrestables WHERE id='$tableid'");
            if (mysqli_num_rows($checkTable) == 0) {
                die("Invalid table selected.");
            }
        }

        // Update query
        if ($estatus === 'Rejected') {
            // For Rejected, do not update tableId
            $query = mysqli_query($conn, "UPDATE tblbookings 
                                          SET adminremark='$oremark', boookingStatus='$estatus' 
                                          WHERE id='$bid'");
        } else {
            // For Accepted, update tableId
            $query = mysqli_query($conn, "UPDATE tblbookings 
                                          SET adminremark='$oremark', boookingStatus='$estatus', tableId='$tableid' 
                                          WHERE id='$bid'");
        }

        if ($query) {
            echo "<script>alert('Booking Details Updated Successfully.');</script>";
        } else {
            die("Error updating booking: " . mysqli_error($conn));
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Restaurent Table Booking System  | Booking Details</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Booking Details</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Booking Details</h3>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <tbody>
<?php 
$bid = intval($_GET['bid']);
$query = mysqli_query($conn, "SELECT tblbookings.*, customer.fullName, customer.email, customer.phone_number
                              FROM tblbookings JOIN customer ON tblbookings.customer_id = customer.customer_id
                              WHERE id='$bid'");
while ($result = mysqli_fetch_array($query)) {
?>
                                    <tr>
                                        <th>Booking Number</th>
                                        <td colspan="3"><?php echo $result['bookingNo']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Name</th>
                                        <td><?php echo $result['fullName']; ?></td>
                                        <th>Email Id</th>
                                        <td><?php echo $result['email']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Mobile No</th>
                                        <td><?php echo $result['phone_number']; ?></td>
                                        <th>Number of Seats</th>
                                        <td><?php echo $result['noSeats']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Booking Date / Time</th>
                                        <td><?php echo $result['bookingDate'] . '/' . $result['bookingTime']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Posting Date</th>
                                        <td colspan="3"><?php echo $result['postingDate']; ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="text-align:center;">
                                            <!-- Delete Form -->
                                            <form method="post" style="display:inline-block;">
                                                <input type="hidden" name="bid" value="<?php echo $bid; ?>">
                                                <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this reservation?');">Delete Reservation</button>
                                            </form>
                                            <!-- Update Status Modal Button -->
                                            <?php if (empty($result['boookingStatus'])): ?>
                                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">Take Action</button>
                                            <?php endif; ?>
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

<!-- Modal for Update Status -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Update Booking Status</h4>
      </div>
      <div class="modal-body">
        <form name="takeaction" method="post">
          <p>
            <select class="form-control" name="status" id="status" required>
                <option value="">Select Booking Status</option>
                <option value="Accepted">Accepted</option>
                <option value="Rejected">Rejected</option>
            </select>
          </p>
          <p id="rtable">
            <input type="hidden" name="bdate" value="<?php echo $date; ?>">
            <input type="hidden" name="btime" value="<?php echo $btime; ?>">
            <select class="form-control" name="table" id="table">
                <option value="">Select Table</option>
                <?php 
                $ret = mysqli_query($conn, "SELECT id, tableNumber FROM tblrestables");
                while ($row = mysqli_fetch_array($ret)) {
                ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['tableNumber']; ?></option>
                <?php } ?>
            </select>
          </p>
          <p>
            <textarea class="form-control" name="officialremak" placeholder="Official Remark" rows="5" required></textarea>
          </p>
          <input type="submit" class="btn btn-primary" name="submit" value="Update">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
</body>
</html>

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../plugins/jszip/jszip.min.js"></script>
<script src="../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
<!-- Page specific script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
<script type="text/javascript">

  //For report file
  $('#rtable').hide();
  $(document).ready(function(){
  $('#status').change(function(){
  if($('#status').val()=='Accepted')
  {
  $('#rtable').show();
  jQuery("#table").prop('required',true);  
  }
  else{
  $('#rtable').hide();
  }
})}) 
</script>
</body>
</html>
<?php } ?>