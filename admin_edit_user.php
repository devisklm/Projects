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
$firstName = $lastName = $phoneNumber = $profilePic = $gender = '';
$error = '';
// Get the user ID from the URL parameter
if (isset($_GET['id']) && !empty($_GET['id'])) {
  $userId = $_GET['id'];
  // Check if the user exists in the database
  $stmt = $conn->prepare('SELECT * FROM user WHERE id = ?');
  $stmt->execute([$userId]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$user) {
    // Redirect to the admin dashboard if the user does not exist
    header('Location: admin_dashboard.php');
    exit;
  }
  // Populate the form fields with the user data
  $firstName = $user['firstname'];
  $lastName = $user['lastname'];
  $phoneNumber = $user['phone'];
  $gender = $user['gender'];
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
    $phoneNumber = trim($_POST['phone_number']);
    $gender = $_POST['gender'];
    // Validate profile picture
    if ($_FILES['profile_pic']['error'] !== UPLOAD_ERR_NO_FILE) {
      $profilePicError = validateProfilePic($_FILES['profile_pic']);
      if ($profilePicError !== '') {
        $error = $profilePicError;
      }
    }
    // If no validation errors, update user data in the database
    if ($error === '') {
      // Prepare and execute the SQL statement
      if ($_FILES['profile_pic']['error'] !== UPLOAD_ERR_NO_FILE) {
        // If a new profile picture is uploaded, move it to the profile_pics directory
        $profilePicName = $_FILES['profile_pic']['name'];
        //$profilePicPath = 'profile_pics/' . $profilePicName;
        //move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profilePicPath);
      } else {
        // If no new profile picture is uploaded, use the existing one
        $profilePicPath = $user['profile_pic'];
      }
      $stmt = $conn->prepare('UPDATE user SET firstname = ?, lastname = ?, phone = ?, profilepic = ?, gender = ? WHERE id = ?');
      $stmt->execute([$firstName, $lastName, $phoneNumber, $profilePicName, $gender, $userId]);
      // Redirect to the admin dashboard
      header('Location: admin_dashboard.php');
      exit;
    }
  }
} else {
  // Redirect to the admin dashboard if no user ID is provided
  header('Location: admin_dashboard.php');
  exit;
}
// Close the database connection
$conn = null;
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit User</title>
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
      <div class="alert alert-danger" role="alert">
        <?php echo $error; ?>
      </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
    <h3 class="text-center">Edit User</h3>
      <div class="form-group">
        <label for="first_name">First Name</label>
        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $firstName; ?>" required>
      </div>
      <div class="form-group">
        <label for="last_name">Last Name</label>
        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $lastName; ?>" required>
      </div>
      <div class="form-group">
        <label for="phone_number">Phone Number</label>
        <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo $phoneNumber; ?>" required>
      </div>
      <div class="form-group">
        <label for="profile_pic">Profile Picture</label>
        <input type="file" class="form-control-file" id="profile_pic" name="profile_pic">
      </div>
      <div class="form-group">
        <label for="gender">Gender</label>
        <select class="form-control" id="gender" name="gender" required>
          <option value="male" <?php echo ($gender === 'male') ? 'selected' : ''; ?>>Male</option>
          <option value="female" <?php echo ($gender === 'female') ? 'selected' : ''; ?>>Female</option>
          <option value="other" <?php echo ($gender === 'other') ? 'selected' : ''; ?>>Other</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Update User</button>
    </form>
  </div>
</body>
</html>