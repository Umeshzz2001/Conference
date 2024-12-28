<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Database connection
$pdo = new PDO('mysql:host=localhost;dbname=conference', 'root', ''); // Update with your DB credentials

$user_id = $_SESSION['user_id']; // Logged-in user ID

// Fetch admin data
$stmt = $pdo->prepare("SELECT name, email, track, phone FROM users WHERE participant_id = :id");
$stmt->bindParam(':id', $user_id, PDO::PARAM_INT); // Ensure the parameter is explicitly bound as an integer
$stmt->execute();

$admin_data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin_data) {
    echo "Error: Admin data not found.";
    exit();
}

$admin_name = $admin_data['name'];
$admin_email = $admin_data['email'];
$admin_phone = $admin_data['phone'];

// Fetch the total number of participants
$stmt = $pdo->query("SELECT COUNT(*) FROM users");
$participant_count = $stmt->fetchColumn();

// Handle removal of participant
if (isset($_GET['remove_participant_id'])) {
    $remove_stmt = $pdo->prepare("DELETE FROM users WHERE participant_id = :id");
    $remove_stmt->bindParam(':id', $_GET['remove_participant_id'], PDO::PARAM_INT);
    $remove_stmt->execute();
    header("Location: admin_dashboard.php");
    exit();
}

// Handle registering a new admin
if (isset($_POST['register_admin'])) {
    $new_admin_name = $_POST['new_admin_name'];
    $new_admin_email = $_POST['new_admin_email'];
    $new_admin_password = password_hash($_POST['new_admin_password'], PASSWORD_DEFAULT);

    $admin_stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, 'admin')");
    $admin_stmt->bindParam(':name', $new_admin_name);
    $admin_stmt->bindParam(':email', $new_admin_email);
    $admin_stmt->bindParam(':password', $new_admin_password);
    $admin_stmt->execute();

    header("Location: admin_dashboard.php");
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Admin Dashboard - IRC 2024</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/conference.css" rel="stylesheet">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex justify-content-between">
                    <p class="mb-0">
                      <strong class="text-dark">IRC 2024 - Admin Dashboard</strong>
                    </p>
                    <p class="mb-0">
                        <img src="images/Untitled1.png" alt="ITUM Logo" class="me-2" style="height: 40px;">
                    </p>
                </div>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <!-- Left Panel -->
            <div class="col-lg-4 left-panel">
                <div class="text-center">
                    <h2>WELCOME ADMIN</h2><br>
                    <h2><?php echo ucwords(strtolower($admin_name)); ?></h2> <!-- Display admin name dynamically -->
                </div>
                <div class="mt-5">
                    <ul class="list-unstyled">
                        <li class="dark-font mb-3"><i class="bi bi-person me-3"></i><?php echo ucwords(strtolower($admin_name)); ?></li>
                        <li class="dark-font mb-3"><i class="bi bi-envelope me-3"></i><?php echo $admin_email; ?></li>
                        <li class="dark-font mb-3"><i class="bi bi-phone me-3"></i><?php echo $admin_phone; ?></li>
                    </ul>
                </div>
                
                <!-- Participant Count Section -->
                <div class="mt-5">
                    <h4>Total Participants: <?php echo $participant_count; ?></h4>
                </div>

                <!-- Register New Admin Form -->
                <div class="mt-5">
                    <h5>Register New Admin</h5>
                    <form action="admin_dashboard.php" method="POST">
                        <div class="mb-3">
                            <label for="new_admin_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="new_admin_name" name="new_admin_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_admin_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="new_admin_email" name="new_admin_email" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_admin_password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="new_admin_password" name="new_admin_password" required>
                        </div>
                        <button type="submit" name="register_admin" class="btn btn-primary">Register Admin</button>
                    </form>
                </div>
            </div>

            <!-- Right Panel -->
            <div class="col-lg-8 right-panel">
                <h3>Manage Participants</h3>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Participant Name</th>
                            <th>Email</th>
                            <th>Track</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch all participants
                        $stmt = $pdo->query("SELECT * FROM users WHERE role = 'participant'");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>
                                    <td>{$row['name']}</td>
                                    <td>{$row['email']}</td>
                                    <td>{$row['track']}</td>
                                    <td><a href='admin_dashboard.php?remove_participant_id={$row['participant_id']}' class='btn btn-danger btn-sm'>Remove</a></td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer class="site-footer"> 
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-12 mt-5">
                    <img src="images/MicrosoftTeams-image.png" alt="ITUM Logo" class="me-2" style="height: 40px;">
                    <p class="copyright-text">Institute of Technology <br> University of Moratuwa</p>
                </div>
            </div>
        </div>     
    </footer>
</body>
</html>
