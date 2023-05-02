<?php
$title = "Cookorama - " . ucfirst($subscriptionType) . " plan";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';

\Stripe\Stripe::setApiKey($_ENV['API_PRIVATE_KEY']);

global $db;

$priceId = '';
$interval = '';
$role = 0;

switch ($subscriptionType) {

    case 'free':

        $role = 1;

        break;

    case 'starter':

        $role = 2;

        if ($plan == 'monthly') {

            $priceId = \Stripe\Product::retrieve(STARTER_MONTHLY)->default_price;
            $interval = 'mois';

        } else {

            $priceId = \Stripe\Product::retrieve(STARTER_YEARLY)->default_price;
            $interval = 'an';

        }

        break;

    case 'master':

        $role = 3;

        if ($plan == 'monthly') {

            $priceId = \Stripe\Product::retrieve(MASTER_MONTHLY)->default_price;
            $interval = 'mois';

        } else {

            $priceId = \Stripe\Product::retrieve(MASTER_YEARLY)->default_price;
            $interval = 'an';

        }

        break;

}

$selectEmail = $db->prepare("SELECT email FROM users WHERE idUser = :idUser");
$selectEmail->execute([
    'idUser' => $_SESSION['id']
]);
$email = $selectEmail->fetch();

if($subscriptionType == 'free') $price = '0.00€';
else
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
                        <form action="<?= ADDRESS_SITE ?><?= $subscriptionType . '/' . $plan ?>/paiement" method="post" id="subscription-form">
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

                                <input type="hidden" name="priceId" value="<?= $priceId ?>">

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

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
                form.submit();
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
