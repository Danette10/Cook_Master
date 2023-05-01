<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cookorama - Gestion des factures";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';

global $db;

if (!isset($_SESSION['id'])) {
    header('Location: ' . PATH_SITE);
    exit();
}

$selectCustomerID = $db->prepare('SELECT idConsumer FROM stripe_consumer WHERE idUser = :idUser');
$selectCustomerID->execute(array(
    'idUser' => $_SESSION['id']
));

$customerID = $selectCustomerID->fetch();

if(!$customerID){
    header('Location: ' . ADDRESS_SITE . 'profil');
    exit();
}
ob_end_flush();

$getInvoice = getUserInvoicesByYear($customerID['idConsumer']);

?>

<body>

    <main>

        <h2 class="text-center mt-4">Gestion des factures</h2>

        <div class="container mt-5">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <h3 class="text-center">Factures</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="invoice-tree">
                                <?php foreach ($getInvoice as $year => $months): ?>
                                    <div class="year">
                                        <h4><?= $year ?></h4>
                                        <?php foreach ($months as $month => $invoices): ?>
                                            <div class="month">
                                                <h5><?= $month ?></h5>
                                                <ul id="invoice-tree">
                                                    <table class="table table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th scope="col">Numéro de facture</th>
                                                            <th scope="col">Montant</th>
                                                            <th scope="col">Date</th>
                                                            <th scope="col">Télécharger</th>
                                                        </tr>
                                                        </thead>
                                                    <?php foreach ($invoices as $invoice):

                                                    ?>
                                                        <li>
                                                                <tbody>
                                                                    <tr>
                                                                        <th scope="row"><?= $invoice->id ?></th>
                                                                        <td><?= $invoice->amount_due / 100 ?> <?= strtoupper($invoice->currency) ?></td>
                                                                        <td><?= date('d/m/Y', $invoice->created) ?></td>
                                                                        <td><a href="<?= ADDRESS_INVOICES . $invoice->metadata['pathInvoice'] ?>" target="_blank">Télécharger</a></td>
                                                                    </tr>
                                                                </tbody>
                                                    <?php endforeach; ?>
                                                        </table>
                                                    </li>
                                                </ul>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

</body>

