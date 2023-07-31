<?php
// Get form data
$email = $_POST['email'];
$pass= $_POST['password'];
// Perform validation and authentication checks
$servername = "127.0.0.1:3307";
$username = "root";
$password = "";
$dbname = "Useraccounts";    
$conn = new mysqli($servername, $username, $password, $dbname);
// Check if the email exists in the database
$result = $conn->query("SELECT * FROM user WHERE email = '$email'");
$user = $result->fetch_assoc();
if (!$user) {
  // Email doesn't exist
  $errorMessage = "Invalid email. Please try again.";
  header("Location: login.html?error=" . urlencode($errorMessage));
  exit();
}
$result = $conn->query("SELECT * FROM user WHERE password = '$pass'");
$passwordMatches = $result->fetch_assoc();
// Check if the provided password matches the stored password
if (!$passwordMatches) {
  $errorMessage = "Invalid password. Please try again.";
  header("Location: login.html?error=" . urlencode($errorMessage));
  exit();  
}
$firstName=$user['firstname'];
header("Location: welcome.php?email=" . urlencode($email)."&firstName=" . urlencode($firstName));
exit();
?>