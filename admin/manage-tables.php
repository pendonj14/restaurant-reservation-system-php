<?php
session_start();
error_reporting(0);
// Database Connection
include('includes/config.php');

// Validating Session
if (strlen($_SESSION['aid']) == 0) {
    header('location:index.php');
} else {
    // Code for updating table details
    if (isset($_POST['update_table'])) {
        $tid = intval($_POST['tid']);
        $tableNumber = $_POST['table_number'];

        $query = mysqli_query($conn, "UPDATE tblrestables SET tableNumber='$tableNumber' WHERE id='$tid'");
        if ($query) {
            echo "<script>alert('Table details updated successfully.');</script>";
            echo "<script type='text/javascript'> document.location = 'manage-tables.php'; </script>";
        } else {
            echo "<script>alert('Something went wrong. Please try again.');</script>";
        }
    }

    // Code for Deleting Table Details
    if ($_GET['action'] == 'delete') {
        $tid = intval($_GET['tid']);

        $query = mysqli_query($conn, "DELETE FROM tblrestables WHERE id='$tid'");
        if ($query) {
            echo "<script>alert('Table details deleted successfully.');</script>";
            echo "<script type='text/javascript'> document.location = 'manage-tables.php'; </script>";
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
    <title>Restaurant Table Booking System | Manage Tables</title>  
    <!-- CSS -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Navbar -->
    <?php include_once("includes/navbar.php"); ?>
    <?php include_once("includes/sidebar.php"); ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Manage Tables</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item active">Manage Tables</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Table Details</h3>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Table No</th>
                                        <th>Added By</th>
                                        <th>Creation Date</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $query = mysqli_query($conn, "SELECT AdminName, tblrestables.id as tid, tblrestables.tableNumber, tblrestables.creationDate FROM tblrestables LEFT JOIN tbladmin ON tbladmin.ID=tblrestables.AddedBy");
                                    $cnt = 1;
                                    while ($result = mysqli_fetch_array($query)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $cnt; ?></td>
                                            <td><?php echo $result['tableNumber']; ?></td>
                                            <td><?php echo $result['AdminName']; ?></td>
                                            <td><?php echo $result['creationDate']; ?></td>
                                            <td>
                                                <!-- Edit Button -->
                                                <button type="button" class="btn btn-primary btn-sm edit-btn" data-id="<?php echo $result['tid']; ?>" data-table-number="<?php echo $result['tableNumber']; ?>">Edit</button>
                                                <!-- Delete Button -->
                                                <a href="manage-tables.php?action=delete&&tid=<?php echo $result['tid']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Do you really want to delete this record?');">Delete</a>
                                            </td>
                                        </tr>
                                        <?php $cnt++;
                                    } ?>
                                    </tbody>
                                </table>
                                <!-- Modal -->
                                <div id="editModal" class="modal" style="display: none;">
                                    <div class="modal-dialog">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Table</h5>
                                            <button id="closeModal" class="close-btn">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post">
                                                <input type="hidden" id="editTid" name="tid">
                                                <div class="form-group">
                                                    <label for="editTableNumber">Table Number</label>
                                                    <input type="text" id="editTableNumber" name="table_number" class="form-control" required>
                                                </div>
                                                <button type="submit" name="update_table" class="btn btn-success">Save Changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Edit Modal -->


<style>
/* Modal Background */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1050;
    align-items: center;
    justify-content: center;
}

.modal-dialog {
    background: white;
    border-radius: 8px;
    padding: 20px;
    max-width: 400px;
    width: 100%;
    z-index: 1060;
    position: relative;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
</style>


<!-- Scripts -->
<script src="../plugins/jquery/jquery.min.js"></script>
<script>
const mainContent = document.querySelector('.wrapper'); // Replace '.wrapper' with your main content container

// Open Modal
document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', () => {
        const modal = document.getElementById('editModal');
        const mainContent = document.querySelector('.wrapper');
        modal.style.display = 'flex'; // Open modal
        mainContent.classList.add('disable-page'); // Disable background interaction
        document.getElementById('editTid').value = button.getAttribute('data-id');
        document.getElementById('editTableNumber').value = button.getAttribute('data-table-number');
        modal.querySelector('input').focus(); // Focus on the first input in the modal
    });
});

document.getElementById('closeModal').addEventListener('click', () => {
    const modal = document.getElementById('editModal');
    const mainContent = document.querySelector('.wrapper');
    modal.style.display = 'none'; // Close modal
    mainContent.classList.remove('disable-page'); // Enable background interaction
});

window.addEventListener('click', (event) => {
    const modal = document.getElementById('editModal');
    const modalContent = modal.querySelector('.modal-dialog');
    if (event.target === modal && !modalContent.contains(event.target)) {
        const mainContent = document.querySelector('.wrapper');
        modal.style.display = 'none';
        mainContent.classList.remove('disable-page'); // Enable background interaction
    }
});



</script>

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
</body>
</html>
<?php } ?>