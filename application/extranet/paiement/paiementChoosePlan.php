<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cookorama - Checkout";
include '../../../ressources/script/head.php';
include PATH_SCRIPT . 'header.php';
require_once('../../../vendor/autoload.php');

$subscriptionType = htmlspecialchars($_GET['subscription']);

\Stripe\Stripe::setApiKey($_ENV['API_PRIVATE_KEY']);

$priceMonthly = '';
$priceYearly = '';

switch ($subscriptionType) {
    case 'free':
        $priceMonthly = '0.00';
        $priceYearly = '0.00';
        break;
    case 'starter':
        $priceMonthly = number_format(getPriceDetails($_ENV['SUBSCRIPTION_PRICE_ID_SARTER_MONTHLY'])->unit_amount / 100, 2, '.', '') . getCurrency($_ENV['SUBSCRIPTION_PRICE_ID_SARTER_MONTHLY']);
        $priceYearly = number_format(getPriceDetails($_ENV['SUBSCRIPTION_PRICE_ID_SARTER_YEARLY'])->unit_amount / 100, 2, '.', '') . getCurrency($_ENV['SUBSCRIPTION_PRICE_ID_SARTER_YEARLY']);
        break;
    case 'master':
        $priceMonthly = number_format(getPriceDetails($_ENV['SUBSCRIPTION_PRICE_ID_MASTER_MONTHLY'])->unit_amount / 100, 2, '.', '') . getCurrency($_ENV['SUBSCRIPTION_PRICE_ID_MASTER_MONTHLY']);
        $priceYearly = number_format(getPriceDetails($_ENV['SUBSCRIPTION_PRICE_ID_MASTER_YEARLY'])->unit_amount / 100, 2, '.', '') . getCurrency($_ENV['SUBSCRIPTION_PRICE_ID_MASTER_YEARLY']);
        break;
}

?>

<body>

<div class="container py-3">

    <main>

        <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
            <h1 class="display-4 fw-normal">Choisissez votre abonnement</h1>
        </div>

        <div class="row row-cols-1 row-cols-md-3 mb-3 text-center justify-content-center">
            <div class="col">
                <div class="card mb-4 rounded-3 shadow-sm">
                    <div class="card-header py-3">
                        <h4 class="my-0 fw-normal">Mensuel</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title"><?= $priceMonthly ?><small class="text-body-secondary fw-light">/mo</small></h1>
                        <a href="<?= ADDRESS_SITE ?>subscribe/<?= $subscriptionType ?>/plan/monthly" type="button" class="w-100 btn btn-lg btn-outline-primary">Choisir</a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card mb-4 rounded-3 shadow-sm border-primary">
                    <div class="card-header py-3 text-bg-primary border-primary">
                        <h4 class="my-0 fw-normal">Annuel</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title"><?= $priceYearly ?><small class="text-body-secondary fw-light">/an</small></h1>
                        <a href="<?= ADDRESS_SITE ?>subscribe/<?= $subscriptionType ?>/plan/yearly" type="button" class="w-100 btn btn-lg btn-primary">Choisir</a>
                    </div>
                </div>
            </div>
        </div>

    </main>

</div>

<?php
include PATH_SCRIPT . 'functionsJs.php';
include PATH_SCRIPT . 'footer.php';
?>
</body>
</html>
