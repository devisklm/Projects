<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];
  // Check if the entered username and password match the admin credentials
  if ($username === 'admin' && $password === 'admin') {
    // Store admin information in session
    $_SESSION['admin'] = true;
    // Redirect to the admin dashboard
    header('Location: admin_dashboard.php');
    exit;
  } else {
    // Display error message for invalid credentials
    $error = 'Invalid username or password';
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function() {
      // Load the user list using AJAX
      function loadUserList() {
        $.ajax({
          url: 'admin_get_user_list.php',
          type: 'GET',
          success: function(response) {
            $('#user-table tbody').html(response);
            $('#user-table').DataTable();
          }
        });
      }
      // Initial load of the user list
      loadUserList();
      // Search filter
      $('#search-input').on('keyup', function() {
        var searchValue = $(this).val().toLowerCase();
        $('#user-table tbody tr').filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(searchValue) > -1);
        });
      });
    });
  </script>
</head>
<body>
  <div class="container">
    <h1 class="text-center"php_check_syntax>User List</h1>
    <table id="user-table" class="table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Phone Number</th>
          <th>Gender</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <!-- User data will be dynamically loaded here -->
      </tbody>
    </table>
    <a href="admin_add_user.php" class="btn btn-primary">Add User</a>
  </div>
</body>
</html>
