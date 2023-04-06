<?php
session_start();
require_once('../../../vendor/autoload.php');
$title = "Cook Master - Starter Plan";
include '../../../ressources/script/head.php';
require_once(PATH_SCRIPT . 'connectDB.php');

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
            ['price' => $_ENV['SUBSCRIPTION_PRICE_ID_SARTER_MONTHLY']],
        ],
    ]);

    // Envoyer un e-mail de confirmation à l'utilisateur
    // Mettre à jour la base de données de votre site web pour indiquer que l'utilisateur a un abonnement actif (par exemple, en créant une nouvelle table STRIPE (id, user_id, subscription_id, status) et en y insérant les données de l'abonnement)


    if($subscription->status == 'active') {

        header('Location: ' . ADDRESS_SCRIPT . 'paiement/confirmation.php?succes=1');
        exit();

    } else {

        header('Location: ' . ADDRESS_FORM . 'paiement/confirmation.php?succes=0');
        exit();

    }
}


include PATH_SCRIPT . 'header.php';

switch (getPriceDetails($_ENV['SUBSCRIPTION_PRICE_ID_SARTER_MONTHLY'])->currency) {
    case 'eur':
        $currency = '€';
        break;
    case 'usd':
        $currency = '$';
        break;
    case 'gbp':
        $currency = '£';
        break;
    default:
        $currency = '';
        break;
}

$price = getPriceDetails($_ENV['SUBSCRIPTION_PRICE_ID_SARTER_MONTHLY'])->unit_amount / 100;

$price = number_format($price, 2, '.', '');

?>

<body>

    <main class="container mt-5">

        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Abonnement Starter - <strong><?= $price ?><?= $currency ?> / mois</strong></h3>
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
                                Payer maintenant <i class="fa-solid fa-lock fa-xl" style="margin-left: 25px; color: #ffffff;"></i>
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

            if (cardholderName.value === '') {
                alert('Veuillez saisir le nom du titulaire de la carte');
                return;
            }

            if (cardholderEmail.value === '') {
                alert('Veuillez saisir l\'e-mail');
                return;
            }

            const result = await stripe.createToken(cardNumber);

            if (result.error) {
                const displayError = document.getElementById('card-errors');
                displayError.textContent = result.error.message;
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
