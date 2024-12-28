<?php
// Prevent caching of the page
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
session_start();

// Check if POST data is received
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from the POST request
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Validate input
    if (empty($email) || empty($password)) {
        echo "<script>alert('Both fields are required!'); window.location.href='login.html';</script>";
        exit();
    }

    // Proceed with user verification
    $servername = "localhost";
    $username = "root";
    $password_db = "";
    $dbname = "conference";

    // Create connection
    $conn = new mysqli($servername, $username, $password_db, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the user exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Store user data in session
            $_SESSION['user_id'] = $row['participant_id'];  // Assuming 'id' is the primary key in the users table
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_track'] = $row['track'];

            // Check if admin_code is not null
            if (!is_null($row['admin_code'])) {
                // Redirect to admin dashboard if admin_code is not null
                echo "<script>window.location.href='adminDashboard.html';</script>";
            } else {
                // Redirect to user dashboard if admin_code is null
                echo "<script>window.location.href='userdashboard.php';</script>";
            }
            exit();
        } else {
            echo "<script>alert('Invalid password!'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('No account found with this email!'); window.location.href='login.html';</script>";
    }

    // Close connection
    $stmt->close();
    $conn->close();
}
?>
