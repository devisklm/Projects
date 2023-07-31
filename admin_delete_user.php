<?php
$host = '127.0.0.1:3307';
$dbname = "Useraccounts";
$user = 'root';
$password = '';
try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo 'Connection failed: ' . $e->getMessage();
  exit;
}
if (isset($_GET['id'])) {
  $userId = $_GET['id'];
  // Delete the user from the database
  $stmt = $conn->prepare('DELETE FROM user WHERE id = ?');
  $stmt->execute([$userId]);
  // Redirect back to the admin dashboard
  header('Location: admin_dashboard.php');
  exit;
}
$conn = null;
?>