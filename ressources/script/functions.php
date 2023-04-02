<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/*
 * TODO: Function to send mail
 */

function mailHtml($to, $subject, $message, $headers) {

    require $_SERVER['DOCUMENT_ROOT'] . '/Cook_Master/vendor/autoload.php';

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 0;                                       // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'cookmasterpa.2023@gmail.com';          // SMTP username
        $mail->Password   = 'pntwvqrintbfsavc';                         // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('cookmasterpa.2023@gmail.com', 'CookMaster');
        $mail->addAddress($to);     // Add a recipient

        $mail->CharSet = 'UTF-8';

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->AltBody = $message;

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

/*
 * TODO: Function to upload profile picture
 */

function uploadProfilePicture($file) {

    // Create folder with year if not exist
    $year = date('Y');
    $path = PATH_IMG . 'profilePicture/' . $year . '/';

    // Check if the folder exists, if not, create it
    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }

    // Generate a unique file name
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $unique_name = uniqid() . '.' . $extension;

    // Set the target file path
    $target_file = $path . $unique_name;

    // Check if the uploaded file is an image
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        return 1; // Code 1: Uploaded file is not an image
    }


     if ($file["size"] > 500000) {
         return 2; // Code 2: File is too large
     }

    // Allow certain file formats (optional, you can set allowed formats)
     $allowed_extensions = array("jpg", "jpeg", "png");
     if (!in_array($extension, $allowed_extensions)) {
         return 3; // Code 3: File format is not allowed
     }

    // Upload the file
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $year . '/' . $unique_name . $extension;
    } else {
        return 4; // Code 4: Error occurred while uploading the file
    }
}
