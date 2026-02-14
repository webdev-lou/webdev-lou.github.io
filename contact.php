<?php
header('Content-Type: application/json');

// Prevent direct access
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method Not Allowed"]);
    exit;
}

// Helper to sanitize input
function sanitize_input($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

// Get and sanitize form data
$name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
$email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
$phone = isset($_POST['phone']) ? sanitize_input($_POST['phone']) : '';
$website = isset($_POST['website']) ? sanitize_input($_POST['website']) : '';
$message = isset($_POST['message']) ? sanitize_input($_POST['message']) : '';

// Validate required fields
if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Please fill in all required fields."]);
    exit;
}

// Prepare email content
$email_content = "Name: $name\n";
$email_content .= "Email: $email\n";
$email_content .= "Phone: $phone\n";
$email_content .= "Website: $website\n\n";
$email_content .= "Message:\n$message\n";

// Log locally
$logEntry = "Time: " . date("Y-m-d H:i:s") . "\n" . $email_content . "---------------------------------\n";
file_put_contents(__DIR__ . "/submissions.txt", $logEntry, FILE_APPEND);

// Try to send email (suppress warnings on localhost where mail isn't configured)
$to = "webdev.lou@gmail.com";
$subject = "New Contact Form Submission from " . $name;
$headers = "From: $name <$email>";
@mail($to, $subject, $email_content, $headers);

// Always return success since we've logged the submission
echo json_encode(["status" => "success", "message" => "Thank you! Your message has been sent."]);
?>