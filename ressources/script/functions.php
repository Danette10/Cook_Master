<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/*
 * TODO: Function to send mail
 */

function mailHtml($to, $subject, $message, $headers, $attachement = null) {

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

        if ($attachement != null) {
            $mail->addAttachment($attachement);
        }

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

/**
 * Fonction qui écris dans un fichier
 *
 * @param string $file    Chemin du fichier
 * @param string $message Message à écrire dans le fichier
 */
function writeLog($file, $message) {

    $dir = dirname($file);

    if (!is_dir($dir)) {

        mkdir($dir, 0777, true);

    }

    if (!file_exists($file)) {

        touch($file);

    }

    if (is_writable($file)) {

        file_put_contents($file, $message . PHP_EOL, FILE_APPEND);

    } else {

        echo "Erreur: Le fichier de journalisation n'est pas accessible en écriture. Veuillez vérifier les permissions.";

    }

}

/**
 * Lit un fichier
 *
 * @param string $file Chemin du fichier
 * @return string
 */
function readLog($file) {

    if (file_exists($file)) {

        return file_get_contents($file);

    } else {

        return "Erreur: Le fichier de journalisation n'existe pas.";

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
 * TODO: Function to generte an invoice
 */

function generateInvoice($invoiceData){

    $invoiceNumber = $invoiceData['invoice_number'];
    $invoiceDate = $invoiceData['invoice_date'];
    $invoiceDueDate = $invoiceData['invoice_due_date'];
    $invoiceNameClient = $invoiceData['invoice_name_client'];
    $invoiceEmailClient = $invoiceData['invoice_email_client'];
    $price = number_format(getPriceDetails($invoiceData['price_id'])->unit_amount / 100, 2, '.', '') . ' ' . getCurrency($invoiceData['price_id']);
    $productName = $invoiceData['product_name'];
    $invoiceQuantity = $invoiceData['invoice_quantity'];
    $invoiceTotal = number_format($invoiceData['invoice_total'] / 100, 2, '.', '') . ' ' . getCurrency($invoiceData['price_id']);
    $invoicePriceUnit = number_format(getPriceDetails($invoiceData['price_id'])->unit_amount / 100, 2, '.', '') . ' ' . getCurrency($invoiceData['price_id']);
    $subscriptionEndDate = date('d/m/Y', strtotime($invoiceData['next_invoice_date']));

    $logo = ADDRESS_IMG . 'logo.png';

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetTitle('Facture - ' . $invoiceNumber);
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);


    $pdf->SetFont('helvetica', '', 12, '', true);

    $pdf->AddPage();

    $pdf->SetFillColor(255, 155, 144);
    $pdf->Rect(0, 0, 250, 2, 'F');

    $header = <<<EOD
    <table>
        <tr>
            <td style="width: 50%;"><h1>Facture</h1></td>
            <td style="width: 50%; text-align: right;">
                <img src="$logo" style="width: 150px;" alt="Logo">
            </td>
        </tr>
    </table>
    
EOD;

    $pdf->writeHTML($header, true, false, true, false, '');

    $invoiceInformation = <<<EOD
    <table>
        <tr>
            <td><p><strong>Numéro de facture</strong></p></td>
            <td colspan="3"><p><strong>$invoiceNumber</strong></p></td>
        </tr>
        <tr>
            <td><p>Date d'émission</p></td>
            <td><p>$invoiceDate</p></td>
        </tr>
        <tr>
            <td><p>Date d'échéance</p></td>
            <td><p>$invoiceDueDate</p></td>
        </tr>
    </table>
EOD;

    $pdf->writeHTML($invoiceInformation, true, false, true, false, '');

    $companyInformation = <<<EOD
    <table>
        <tr>
            <td><p><strong>Cookorama</strong></p></td>
            <td><p><strong>Facturer à</strong></p></td>
        </tr>
        <tr>
            <td><p>+33 6 00 00 00 00</p></td>
            <td><p>$invoiceNameClient</p></td>
        </tr>
        <tr>
            <td></td>
            <td><p>$invoiceEmailClient</p></td>
        </tr>
    </table>
EOD;

    $pdf->ln(5);

    $pdf->writeHTML($companyInformation, true, false, true, false, '');

    $pdf->ln(10);

    $duePrice = <<<EOD
    <h2>$price payé le $invoiceDate</h2>
EOD;

    $pdf->writeHTML($duePrice, true, false, true, false, '');

    $pdf->ln(10);

    $invoiceTable = <<<EOD
    <style>
        *{
            font-size: 10pt;
        }
        table {
            border-collapse: collapse;
        }
        tr th {
            border-bottom: 1px solid black;
        }
        th, td {
            padding: 5px;
        }
        </style>
    <table border="0" cellpadding="5">
        <tr>
            <th style="width: 25%;"><p>Description</p></th>
            <th style="text-align: right;"><p>Quantité</p></th>
            <th style="text-align: right;"><p>Prix unitaire</p></th>
            <th style="text-align: right;"><p>Montant</p></th>
        </tr>
        <tr>
            <td><p>$productName<br>$invoiceDate - $subscriptionEndDate</p></td>
            <td style="text-align: right;"><p>$invoiceQuantity</p></td>
            <td style="text-align: right;"><p>$invoicePriceUnit</p></td>
            <td style="text-align: right;"><p>$price</p></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="1" style="text-align: right;">
                
                <p><strong>Total</strong></p>
                
            </td>
            
            <td></td>
            
            <td style="text-align: right;">
                
                <p><strong>$invoiceTotal</strong></p>
                
            </td>
        </tr>
    </table>
EOD;

    $pdf->writeHTML($invoiceTable, true, false, true, false, '');

    $pdf->setY(-35);

    $pdf->SetFont('helvetica', '', 8, '', true);

    $footer = <<<EOD
    <hr>
    <p>$invoiceNumber - $price payer le $invoiceDate</p>
EOD;

    $pdf->writeHTML($footer, true, false, true, false, '');

    $year = date('Y');
    $month = date('m');

    $pdfPath = $year . '/' . $month;

    $pdfPathSuite = $pdfPath . '/' . $_SESSION['id'] . '_' . 'facture-' . $invoiceNumber . '.pdf';

    $fullPath = PATH_INVOICES . $pdfPathSuite;

    if (!file_exists(PATH_INVOICES . $pdfPath)) {
        mkdir(PATH_INVOICES . $pdfPath, 0777, true);
    }

    $pdf->Output($fullPath, 'F');

    return $pdfPathSuite;

}

/**
 * Function to get invoice by year and month
 *
 * @param string $customerId
 *
 * @return array
 */

function getUserInvoicesByYear($customerId) {

    \Stripe\Stripe::setApiKey($_ENV['API_PRIVATE_KEY']);

    $invoicesByYearAndMonth = [];

    try {
        $invoices = \Stripe\Invoice::all(['customer' => $customerId, 'limit' => 100]);

        foreach ($invoices->autoPagingIterator() as $invoice) {
            // Vérifie si le champ 'metadata' contient au moins une clé
            if (count($invoice->metadata) > 0) {
                $year = date('Y', $invoice->created);
                $month = date('m', $invoice->created);

                if (!isset($invoicesByYearAndMonth[$year])) {
                    $invoicesByYearAndMonth[$year] = [];
                }
                if (!isset($invoicesByYearAndMonth[$year][$month])) {
                    $invoicesByYearAndMonth[$year][$month] = [];
                }

                $invoicesByYearAndMonth[$year][$month][] = $invoice;
            }
        }

        krsort($invoicesByYearAndMonth);

    } catch (\Stripe\Exception\ApiErrorException $e) {
        echo "Erreur lors de la récupération des factures : " . $e->getMessage();
    }

    return $invoicesByYearAndMonth;

}




?>
