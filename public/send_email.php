<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);
    
    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit;
    }

    // Email content
    $to = "your-email@example.com"; // Replace with your email address
    $headers = "From: $email\r\n";
    $email_subject = "New Contact Form Message: $subject";
    $email_body = "Name: $name\nEmail: $email\n\nMessage:\n$message";

    // Sending email
    if (mail($to, $email_subject, $email_body, $headers)) {
        echo "Message sent successfully!";
    } else {
        echo "There was a problem sending your message. Please try again.";
    }
} else {
    echo "Invalid request.";
}
?>
