<!DOCTYPE html>
<html lang="fr">

<?php

$title = "Cookorama - Confirmation";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';

global $db;

\Stripe\Stripe::setApiKey($_ENV['API_PRIVATE_KEY']);
$selectSubscription = $db->prepare("SELECT idInvoice FROM orders, stripe_consumer WHERE orders.idUser = :idUser AND subscriptionStatus = 'active' ORDER BY idCart DESC LIMIT 1");
$selectSubscription->execute(array(
    'idUser' => $_SESSION['id']
));
$subscription = $selectSubscription->fetch();
$invoiceId = $subscription['idInvoice'];

$invoice = \Stripe\Invoice::retrieve($invoiceId);
$priceId = $invoice->lines->data[0]->price->id;
$client = \Stripe\Customer::retrieve($invoice->customer);
$subscription = \Stripe\Subscription::retrieve($invoice->subscription);
$product = \Stripe\Product::retrieve($subscription->items->data[0]->price->product);

$price = number_format(getPriceDetails($priceId)->unit_amount / 100, 2, '.', '');
$interval = '';

switch ($plan){
    case 'monthly':
        $interval = 'mois';
        break;
    case 'yearly':
        $interval = 'an';
        break;
}

$text = '';

if($confirm == '0'){

    $text = "<p class='text-center'>Une erreur est survenue lors de votre paiement, veuillez réessayer</p>";

}else{

    $text = "<p class='text-justify'>Merci pour votre achat !</p>";
    $text .= "<p class='text-justify'>Vous avez choisi l'abonnement <strong>" . ucfirst($subscriptionType) . "</strong></p>";
    $text .= "<p class='text-justify'>Vous allez recevoir un mail de confirmation de paiement</p>";
    $text .= "<p class='text-justify'>Vous pouvez désormais accéder à votre espace personnel et profiter de fonctionnalités exclusives à votre abonnement !</p>";
    $text .= "<p class='text-justify'>Nous espérons que vous allez apprécier notre site !</p>";
    $text .= "<p class='text-justify'>L'équipe Cookorama</p>";

    $messageMail = "<p><img src='cid:logo' alt='logo' width='300'></p>";
    $messageMail .= "<h1>Merci pour votre achat !</h1>";
    $messageMail .= "<p>Vous avez choisi l'abonnement <strong>" . ucfirst($subscriptionType) . "</strong></p>";
    $messageMail .= "<p>Vous allez être facturé de <strong>" . $price  . getCurrency($priceId) . "</strong> / " . $interval . "</p>";
    $messageMail .= "<p>Vous pouvez désormais accéder à votre espace personnel et profiter de fonctionnalités exclusives à votre abonnement !</p>";
    $messageMail .= "<p>Nous espérons que vous allez apprécier notre site !</p>";
    $messageMail .= "<p>L'équipe Cookorama</p>";

    $invoiceLines = $invoice->lines->data;

    $amount = $invoiceLines[0]->amount;
    $date = date('d/m/Y', $invoice->period_start);
    $dateEnd = date('d/m/Y', $invoiceLines[0]->period->end);
    $invoiceNumber = $invoice->number;
    $quantity = $invoiceLines[0]->quantity;
    $total = $invoice->total;
    $priceUnit = $invoiceLines[0]->price->unit_amount;
    $productName = $product->name;

    $nameClient = $client->name;
    $emailClient = $client->email;

    $priceId = $invoiceLines[0]->price->id;

    $invoiceData = [
        'amount' => $amount,
        'product_name' => $productName,
        'invoice_date' => $date,
        'invoice_due_date' => $date,
        'invoice_number' => $invoiceNumber,
        'invoice_quantity' => $quantity,
        'invoice_total' => $total,
        'invoice_name_client' => $nameClient,
        'invoice_email_client' => $emailClient,
        'next_invoice_date' => $dateEnd,
        'price_id' => $priceId
    ];


    $pdfSuite = generateInvoice($invoiceData);

    $updateInvoice = $db->prepare('UPDATE orders SET pathInvoice = :pathInvoice WHERE idUser = :idUser AND idInvoice = :idInvoice');
    $updateInvoice->execute([
        'pathInvoice' => $pdfSuite,
        'idUser' => $_SESSION['id'],
        'idInvoice' => $invoiceId
    ]);

    // Stocker le chemin de la facture dans les métadonnées de la facture
    $invoice->metadata = array('pathInvoice' => $pdfSuite);

    $invoice->save();

    // Joindre la facture au mail
    $messageMail .= "<p>Vous trouverez ci-joint votre facture</p>";

    $subject = "Cookorama - Confirmation de paiement - " . ucfirst($subscriptionType);
    $header = "Cookorama < " . MAIL . " >";
    $attachement = PATH_INVOICES . $pdfSuite;

    mailHtml($_SESSION['email'], $subject, $messageMail, $header, $attachement, PATH_IMG . 'logo.png');

}

?>

<body>

    <main>

        <div class="container" style="padding: 40px; font-size: 1.2rem;">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-center">Confirmation</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-12">

                    <?= $text; ?>

                </div>
            </div>
        </div>

    </main>

</body>
