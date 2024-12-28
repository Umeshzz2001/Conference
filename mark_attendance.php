<?php
// Database connection
$pdo = new PDO('mysql:host=localhost;dbname=conference', 'root', ''); // Update with your DB credentials
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get the JSON data from the request
$inputData = json_decode(file_get_contents('php://input'), true);

// Ensure the necessary data is provided
if (isset($inputData['session_id']) && isset($inputData['email'])) {
    $session_id = $inputData['session_id'];
    $email = $inputData['email'];

    // Query to check if the participant exists (based on email)
    $stmt = $pdo->prepare("SELECT participant_id FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // Participant not found, return error
        echo json_encode(['success' => false, 'message' => 'Participant not found.']);
        exit();
    }

    $participant_id = $user['participant_id'];

    // Insert attendance record
    $stmt = $pdo->prepare("INSERT INTO attendance (session_id, participant_id, attendance_time) VALUES (:session_id, :participant_id, NOW())");
    $stmt->bindParam(':session_id', $session_id, PDO::PARAM_INT);
    $stmt->bindParam(':participant_id', $participant_id, PDO::PARAM_INT);
    $stmt->execute();

    // Return success response
    echo json_encode(['success' => true, 'participant_id' => $participant_id]);

} else {
    // Missing data, return error
    echo json_encode(['success' => false, 'message' => 'Session ID or email missing.']);
}
?>
