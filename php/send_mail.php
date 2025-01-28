<?php
session_start();

// Function to sanitize input  
function sanitize_input($data)
{
    return htmlspecialchars(trim($data));
}

// Anti-spam measures  
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input  
    $name = sanitize_input($_POST['name']);
    $email = filter_var(sanitize_input($_POST['email']), FILTER_SANITIZE_EMAIL);
    $subject = sanitize_input($_POST['subject']);
    $message = sanitize_input($_POST['message']);

    // Validate email  
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    // Honeypot check  
    if (!empty($_POST['honeypot'])) {
        die("Spam detected!");
    }

    // CAPTCHA verification  
    $recaptcha_response = $_POST['g-recaptcha-response'];
    $secret_key = 'YOUR_SECRET_KEY'; // Replace with your actual secret key  
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$recaptcha_response");
    $response_keys = json_decode($response, true);

    if (intval($response_keys["success"]) !== 1) {
        die("Please complete the CAPTCHA");
    }

    // Rate limiting  
    if (isset($_SESSION['last_submit_time'])) {
        $time_since_last_submit = time() - $_SESSION['last_submit_time'];
        if ($time_since_last_submit < 30) { // 30 seconds limit  
            die("You are submitting too quickly. Please wait a moment.");
        }
    }
    $_SESSION['last_submit_time'] = time();

    // Content filtering  
    $spam_words = ['buy now', 'free', 'click here', 'discount', 'limited time'];
    if (preg_match('/\b(' . implode('|', $spam_words) . ')\b/i', $message)) {
        die("Message contains suspicious content.");
    }

    // Proceed with email sending  
    $to = 'your-email@example.com'; // Replace with your own email address  
    $headers = "From: $name <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";

    $mailBody = "Name: $name\nEmail: $email\nSubject: $subject\n\nMessage:\n$message";

    if (mail($to, $subject, $mailBody, $headers)) {
        echo "Email sent successfully.";
    } else {
        echo "Email sending failed.";
    }
} else {
    die("Invalid request.");
}
