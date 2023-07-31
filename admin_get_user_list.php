<?php
// Database connection
$conn = mysqli_connect('127.0.0.1:3307', 'root', '', 'useraccounts');
// Retrieve user list from the database
$query = "SELECT * FROM user";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>".$row['firstname']." ".$row['lastname']."</td>";
    echo "<td>".$row['email']."</td>";
    echo "<td>".$row['phone']."</td>";
    echo "<td>".$row['gender']."</td>";
    echo "<td>
            <a href='admin_edit_user.php?id=".$row['id']."' class='btn btn-sm btn-primary'>Edit</a>
            <a href='admin_delete_user.php?id=".$row['id']."' class='btn btn-sm btn-danger'>Delete</a>
          </td>";
    echo "</tr>";
  }
} else {
  echo "<tr><td colspan='4'>No users found</td></tr>";
}

mysqli_close($conn);
?>
