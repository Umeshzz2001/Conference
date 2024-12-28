<?php
// Include the QR code library
require_once('phpqrcode-master/qrlib.php');

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "conference";  // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data from the POST request
$name = $_POST['ticket-form-name'];
$email = $_POST['ticket-form-email'];
$phone = $_POST['ticket-form-phone'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];
$track = $_POST['ticket-form-track'];  // Track selected
$additionalRequest = $_POST['ticket-form-message'];  // Additional request

// Password validation
if ($password !== $confirmPassword) {
    die("<p>Passwords do not match. Please go back and try again.</p>");
}

// Hash the password for security
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check for existing user by email
$checkQuery = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // If user already registered, show an alert and redirect to the login page
    echo '<script type="text/javascript">
            alert("You are already registered. Please login.");
            window.location.href = "login.html";
          </script>';
    exit();
}

// Create the text for the QR code
$qr_text = "Name: $name, Email: $email, Phone: $phone, Track: $track";

// Define file path for the generated QR code
$qr_code_file = 'uploads/qr_code_' . uniqid() . '.png';

// Set QR code generation options
$level = 'L'; // Error correction level: L (low), M, Q, H
$size = 4; // Size of the QR code (increase for larger code)
$margin = 2; // Margin around the QR code

// Generate and save the QR code to the file
QRcode::png($qr_text, $qr_code_file, $level, $size, $margin);

// Insert the data into the database
$sql = "INSERT INTO users (name, email, phone, password, track, additionalRequest, qr_code_picture) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $name, $email, $phone, $hashed_password, $track, $additionalRequest, $qr_code_file);

if ($stmt->execute()) {
    // Redirect to the login page after successful registration
    header("Location: login.html");
    exit();
} else {
    echo "<p>Error: " . $stmt->error . "</p>";
}

// Close connection
$stmt->close();
$conn->close();
?>
