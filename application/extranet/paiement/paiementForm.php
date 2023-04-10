<?php
session_start();
require_once('../../../vendor/autoload.php');
$title = "Cookorama - Starter Plan";
include '../../../ressources/script/head.php';
require_once(PATH_SCRIPT . 'connectDB.php');

$subscriptionType = htmlspecialchars($_GET['subscription']);
$plan = htmlspecialchars($_GET['plan']);
$priceId = '';
$interval = '';
$role = 0;

switch ($subscriptionType) {

    case 'free':

        $role = 1;

        $priceId = $_ENV['SUBSCRIPTION_PRICE_ID_FREE'];

        break;

    case 'starter':

        $role = 2;

        if ($plan == 'monthly') {

            $priceId = $_ENV['SUBSCRIPTION_PRICE_ID_SARTER_MONTHLY'];
            $interval = 'mois';

        } else {

            $priceId = $_ENV['SUBSCRIPTION_PRICE_ID_SARTER_YEARLY'];
            $interval = 'an';

        }

        break;

    case 'master':

        $role = 3;

        if ($plan == 'monthly') {

            $priceId = $_ENV['SUBSCRIPTION_PRICE_ID_MASTER_MONTHLY'];
            $interval = 'mois';

        } else {

            $priceId = $_ENV['SUBSCRIPTION_PRICE_ID_MASTER_YEARLY'];
            $interval = 'an';

        }

        break;

}

$selectEmail = $db->prepare("SELECT email FROM user WHERE id = :id");
$selectEmail->execute([
    'id' => $_SESSION['id']
]);
$email = $selectEmail->fetch();

\Stripe\Stripe::setApiKey($_ENV['API_PRIVATE_KEY']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stripeToken = $_POST['stripeToken'];
    $email = $_POST['cardholderEmail'];
    $name = $_POST['cardholderName'];

    // Créer le client dans Stripe avec le nom
    $customer = \Stripe\Customer::create([
        'email' => $email,
        'name' => $name,
        'preferred_locales' => ['fr'],
    ]);

    // Créer la source de paiement avec le nom du titulaire de la carte
    $card = \Stripe\Customer::createSource(
        $customer->id,
        ['source' => $stripeToken]
    );

    // Associer le nom du titulaire à la source de paiement (carte)
    $updatedCard = \Stripe\Customer::updateSource(
        $customer->id,
        $card->id,
        ['name' => $name]
    );

    // Créer l'abonnement associé au produit
    $subscription = \Stripe\Subscription::create([
        'customer' => $customer->id,
        'items' => [
            ['price' => $priceId],
        ],
    ]);

    $updateUser = $db->prepare("UPDATE user SET role = :role WHERE id = :id");
    $updateUser->execute([
        'role' => $role,
        'id' => $_SESSION['id']
    ]);

    $customerID = $customer->id;
    $userId = $_SESSION['id'];
    $invoiceId = $subscription->latest_invoice;
    $subscriptionId = $subscription->id;
    $subscriptionStatus = $subscription->status;
    $subscriptionPlan = $subscription->items->data[0]->price->id;
    $subscriptionStart = date('Y-m-d H:i:s', $subscription->current_period_start);
    $subscriptionEnd = date('Y-m-d H:i:s', $subscription->current_period_end);

    $insertSubscription = $db->prepare("INSERT INTO stripe_consumer(
                            customer_id, user_id, invoice_id, subscription_id, 
                            subscription_status, subscription_plan, subscription_start_date, 
                            subscription_end_date, path_invoice
                            ) VALUES(
                                     :customer_id, :user_id, :invoice_id, :subscription_id, :subscription_status, 
                                     :subscription_plan, :subscription_start_date, :subscription_end_date, :path_invoice
                                     )");
    $insertSubscription->execute([
        'customer_id' => $customerID,
        'user_id' => $userId,
        'invoice_id' => $invoiceId,
        'subscription_id' => $subscriptionId,
        'subscription_status' => $subscriptionStatus,
        'subscription_plan' => $subscriptionPlan,
        'subscription_start_date' => $subscriptionStart,
        'subscription_end_date' => $subscriptionEnd,
        'path_invoice' => ''
    ]);

    if($subscription->status == 'active') {

        header('Location: ' . ADDRESS_SITE . 'confirmPaiement/1/' . $subscriptionType . '/' . $plan);
        exit();

    } else {

        header('Location: ' . ADDRESS_SITE . 'confirmPaiement/0/' . $subscriptionType . '/' . $plan);
        exit();

    }
}


include PATH_SCRIPT . 'header.php';

$price = number_format(getPriceDetails($priceId)->unit_amount / 100, 2, '.', '');

?>

<body>

    <main class="container mt-5">

        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Abonnement <?= ucfirst($subscriptionType) ?> - <strong><?= $price ?><?= getCurrency($priceId) ?> / <?= $interval ?></strong></h3>
                    </div>
                    <div class="card-body">
                        <form method="post" id="subscription-form">
                            <div class="form-group mb-3">
                                <label for="cardholderName">Nom du titulaire</label>
                                <input type="text" name="cardholderName" id="cardholderName" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="email">E-mail</label>
                                <input type="email" name="cardholderEmail" id="cardholderEmail" class="form-control" value="<?= $email['email'] ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Numéro de carte</label>
                                <div id="card-number-element" class="form-control" style="border: 2px solid #FF9B90; border-radius: 10px 10px 0 0;padding: 10px;"></div>
                            </div>
                            <div class="form-group">
                                <div class="d-flex" style="border-bottom: 2px solid #FF9B90; border-left: 2px solid #FF9B90; border-right: 2px solid #FF9B90; border-radius: 0 0 10px 10px;">
                                    <div id="card-expiry-element" class="form-control mr-2 flex-grow-1" style="border-right: 2px solid #FF9B90; border-radius: 0 0 0 10px; padding: 10px;"></div>
                                    <div id="card-cvc-element" class="form-control flex-grow-1" style="border-radius: 0 0 10px 0; padding: 10px;"></div>
                                </div>
                            </div>

                            <div id="card-errors" role="alert"></div>
                            <button type="submit" class="btn btn-primary btn-block mt-4">
                                <span id="sendButton">Payer maintenant <i class="fa-solid fa-lock fa-xl" style="margin-left: 25px; color: #ffffff;"></i></span>
                                <img src="<?= ADDRESS_IMG ?>loader_circle.gif" id="loader" style="width: 30px; height: 30px; display: none; margin: 0 auto;">
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <?php
    include PATH_SCRIPT . 'functionsJs.php';
    include PATH_SCRIPT . 'footer.php';
    ?>

</body>

<script src="https://js.stripe.com/v3/"></script>
<script>

    document.addEventListener('DOMContentLoaded', function() {

        const stripe = Stripe('<?= $_ENV['API_PUBLIC_KEY'] ?>');
        const elements = stripe.elements();
        const style = {
            base: {
                color: '#32325d',
                fontFamily: '"Roboto", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
        };

        const cardNumber = elements.create('cardNumber', { style: style, placeholder: 'Numéro de carte' });
        cardNumber.mount('#card-number-element');

        const cardExpiry = elements.create('cardExpiry', { style: style, placeholder: 'MM/AA' });
        cardExpiry.mount('#card-expiry-element');

        const cardCvc = elements.create('cardCvc', { style: style, placeholder: 'CVC' });
        cardCvc.mount('#card-cvc-element');

        cardNumber.addEventListener('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        cardExpiry.addEventListener('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        cardCvc.addEventListener('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        const form = document.getElementById('subscription-form');
        form.addEventListener('submit', async function(event) {
            event.preventDefault();
            const cardholderEmail = document.getElementById('cardholderEmail');
            const cardholderName = document.getElementById('cardholderName');
            const sendButton = document.getElementById('sendButton');
            const loader = document.getElementById('loader');

            if (cardholderName.value === '') {
                alert('Veuillez saisir le nom du titulaire de la carte');
                return;
            }

            if (cardholderEmail.value === '') {
                alert('Veuillez saisir l\'e-mail');
                return;
            }

            sendButton.style.display = 'none';
            loader.style.display = 'flex';

            const result = await stripe.createToken(cardNumber);

            if (result.error) {
                const displayError = document.getElementById('card-errors');
                displayError.textContent = result.error.message;
                sendButton.style.display = 'block';
                loader.style.display = 'none';
            } else {
                stripeTokenHandler(result.token);
            }
        });



        function stripeTokenHandler(token) {
            const form = document.getElementById('subscription-form');
            const hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            form.submit();
        }

    });

</script>
