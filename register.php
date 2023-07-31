<?php
ini_set("SMTP", "smtp.gmail.com");
ini_set("smtp_port", "587");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phone'];
    $profilePic = $_FILES['profilePic']['name'];
    $gender = $_POST['gender'];
    $pass = $_POST['password'];

    // Validate and process the form data (e.g., store in the database and send email)

    $servername = "127.0.0.1:3307";
    $username = "root";
    $password = "";
    $dbname = "Useraccounts";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if email already exists in the user table
    $checkEmailQuery = "SELECT * FROM user WHERE email = '$email'";
    $checkEmailResult = $conn->query($checkEmailQuery);

    if ($checkEmailResult->num_rows > 0) {
        // Email already exists, display error message
        echo "Error: Email already exists.";
    } else {
        // Email does not exist, proceed with registration
        // Store the data in the user table
        $insertQuery = "INSERT INTO user (firstname, lastname, email, phone, profilePic, gender, password) VALUES ('$firstName', '$lastName', '$email', '$phoneNumber', '$profilePic', '$gender', '$pass')";

        if ($conn->query($insertQuery) === TRUE) {
            // Registration successful
            // Send email to the user
            $to = $email;
            $subject = "Registration Successful";
            $message = "Thank you for registering! You can now log in.";
            $headers = "From: devilokarapu123@gmail.com";

            // Attempt to send the email
            if (mail($to, $subject, $message, $headers)) {
                // Redirect to the welcome page with user information
                header("Location: welcome.php?email=" . urlencode($email) . "&firstName=" . urlencode($firstName));
                exit();
            } else {
                // Error sending the email
                echo "Error: Unable to send registration email.";
                // Log the error for further investigation
                error_log("Error sending email to: " . $to . " | Subject: " . $subject);
            }

        } else {
            // Error storing data in the user table
            echo "Error: " . $conn->error;
        }
    }

    // Close the database connection
    $conn->close();
}
?>
