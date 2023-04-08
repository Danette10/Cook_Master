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
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'cookmasterpa.2023@gmail.com';
        $mail->Password   = 'pntwvqrintbfsavc';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        //Recipients
        $mail->setFrom('cookmasterpa.2023@gmail.com', 'Cookorama');
        $mail->addAddress($to);

        $mail->CharSet = 'UTF-8';

        // Content
        $mail->isHTML(true);
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
    $month = date('m');
    $path = PATH_IMG . 'profilePicture/' . $year . '/' . $month . '/';

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
        return $year . '/' . $month . '/' . $unique_name;
    } else {
        return 4; // Code 4: Error occurred while uploading the file
    }
}

/*
 * TODO: Function to return the price of a product
 */

function getPriceDetails($priceId) {
    try {
        $price = \Stripe\Price::retrieve($priceId);
        return $price;
    } catch (\Stripe\Exception\ApiErrorException $e) {
        // Gestion des erreurs
        echo 'Error: ' . $e->getMessage();
        return null;
    }
}

/*
 * TODO: Function to return the currency of a product
 * @param $priceId
 */

function getCurrency($priceId) {
    $price = getPriceDetails($priceId);

    switch ($price->currency) {
        case 'eur':
            $price->currency = '€';
            break;
        case 'usd':
            $price->currency = '$';
            break;
        case 'gbp':
            $price->currency = '£';
            break;
    }

    return $price->currency;
}
