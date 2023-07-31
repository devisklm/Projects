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
// Define variables and set to empty values
$firstName = $lastName = $email = $phoneNumber = $password = $profilePic = $gender = '';
$error = '';
// Function to validate the uploaded profile picture
function validateProfilePic($file)
{
  $allowedTypes = array('image/jpeg', 'image/png');
  $maxFileSize = 2 * 1024 * 1024; // 2MB
  if ($file['error'] === UPLOAD_ERR_OK) {
    // Check file type
    if (!in_array($file['type'], $allowedTypes)) {
      return 'Invalid file type. Only JPEG and PNG images are allowed.';
    }
    // Check file size
    if ($file['size'] > $maxFileSize) {
      return 'File size exceeds the limit of 2MB.';
    }
  } else {
    return 'Error uploading file.';
  }
  return '';
}
// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Validate and sanitize user inputs
  $firstName = trim($_POST['first_name']);
  $lastName = trim($_POST['last_name']);
  $email = trim($_POST['email']);
  $phoneNumber = trim($_POST['phone_number']);
  $password = $_POST['password'];
  $gender = $_POST['gender'];
  // Validate profile picture
  if ($_FILES['profile_pic']['error'] === UPLOAD_ERR_NO_FILE) {
    $error = 'Profile picture is required.';
  } else {
    $profilePicError = validateProfilePic($_FILES['profile_pic']);
    if ($profilePicError !== '') {
      $error = $profilePicError;
    }
  }
  // If no validation errors, insert user data into the database
  if ($error === '') {
    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    // Move the uploaded profile picture to a specific directory
    $profilePicName = $_FILES['profile_pic']['name'];
    //$profilePicPath = 'profile_pics/' . $profilePicName;
    //move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profilePicPath);
    // Prepare and execute the SQL statement
    $stmt = $conn->prepare('INSERT INTO user (firstname, lastname, email, phone, password, profilepic, gender) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$firstName, $lastName, $email, $phoneNumber, $hashedPassword, $profilePicName, $gender]);
    // Redirect to the admin dashboard
    header('Location: admin_dashboard.php');
    exit;
  }
}
// Close the database connection
$conn = null;
?>

<!DOCTYPE html>
<html>
<head>
  <title>Add User</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <style>
        .center-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 105vh;
        }    
    </style>
</head>
<body>
  <div class="container center-container">
    <?php if ($error !== ''): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
    <h2 class="text-center">Add User</h2>
      <div class="form-group">
        <label for="first_name">First Name:</label>
        <input type="text" class="form-control" id="first_name" name="first_name" required>
      </div>
      <div class="form-group">
        <label for="last_name">Last Name:</label>
        <input type="text" class="form-control" id="last_name" name="last_name" required>
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="phone_number">Phone Number:</label>
        <input type="tel" class="form-control" id="phone_number" name="phone_number" required>
      </div>
      <div class="form-group">
        <label for="profile_pic">Profile Picture:</label>
        <input type="file" class="form-control-file" id="profile_pic" name="profile_pic" required>
      </div>
      <div class="form-group">
        <label for="gender">Gender:</label>
        <select class="form-control" id="gender" name="gender" required>
          <option value="male">Male</option>
          <option value="female">Female</option>
        </select>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      <button type="submit" class="btn btn-primary">Add User</button>
    </form>
  </div>
</body>
</html>
