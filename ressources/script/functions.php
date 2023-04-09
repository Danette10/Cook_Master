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

/*
 * TODO: Function to create an invoice for a customer with tcpdf
 */

function generateInvoice($invoiceData) {
    require $_SERVER['DOCUMENT_ROOT'] . '/Cook_Master/vendor/autoload.php';

    // Créez une instance de la classe TCPDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Supprimez les en-têtes et pieds de page par défaut
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

// Ajoutez une nouvelle page
    $pdf->AddPage();

// Définissez le logo et les informations de facture
    $logo = $invoiceData['logo']; // Remplacez 'logo.png' par le chemin du logo de votre site
    $invoice_header = <<<EOD
<img src="$logo" width="100" />
<h1>Facture</h1>
<p>
    Numéro de facture: $invoice_number<br />
    Date de facturation: $invoice_date<br />
    Date d'échéance: $due_date<br />
    Client: $customer_name
</p>
EOD;

// Ajoutez le logo et les informations de facture au PDF
    $pdf->writeHTML($invoice_header, true, false, true, false, '');

// Définissez un tableau contenant les informations sur les produits ou services facturés
    $invoice_items = [
        ['description' => 'Produit 1', 'quantity' => 1, 'unit_price' => '49.99', 'amount' => '49.99'],
        ['description' => 'Produit 2', 'quantity' => 1, 'unit_price' => '50.00', 'amount' => '50.00']
    ];

// Créez le tableau HTML pour les éléments de facture
    $invoice_table = '<table cellspacing="0" cellpadding="4" border="1">';
    $invoice_table .= '<tr><th>Description</th><th>Quantité</th><th>Prix unitaire</th><th>Montant</th></tr>';

    foreach ($invoice_items as $item) {
        $invoice_table .= '<tr>';
        $invoice_table .= '<td>' . $item['description'] . '</td>';
        $invoice_table .= '<td>' . $item['quantity'] . '</td>';
        $invoice_table .= '<td>' . $item['unit_price'] . ' ' . $currency . '</td>';
        $invoice_table .= '<td>' . $item['amount'] . ' ' . $currency . '</td>';
        $invoice_table .= '</tr>';
    }

    $invoice_table .= '<tr><td colspan="3" align="right">Total:</td><td>' . $total . ' ' . $currency . '</td></tr>';
    $invoice_table .= '</table>';

    // Ajoutez le tableau HTML des éléments de facture au PDF
    $pdf->writeHTML($invoice_table, true, false, true, false, '');



    // Définissez le chemin de sauvegarde du fichier PDF
    $file_path = PATH_INVOICES . $invoice_number . '.pdf';

    // Enregistrez le fichier PDF
    $pdf->Output($file_path, 'F');

    // Retournez le chemin du fichier PDF
    return $file_path;

}
