<?php
session_start();
require_once('../../../vendor/autoload.php');
$title = "Cook Master - Starter Plan";
include '../../../ressources/script/head.php';
require_once(PATH_SCRIPT . 'connectDB.php');

// Configurez votre clé API secrète depuis votre tableau de bord Stripe
\Stripe\Stripe::setApiKey($_ENV['API_PRIVATE_KEY']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $selectEmail = $db->prepare("SELECT email FROM user WHERE id = :id");
    $selectEmail->execute([
        'id' => $_SESSION['id']
    ]);
    $email = $selectEmail->fetch();

    $stripeToken = $_POST['stripeToken'];

    // Créer le client dans Stripe
    $customer = \Stripe\Customer::create([
        'email' => $email['email'],
        'source' => $stripeToken,
    ]);

    // Créer l'abonnement associé au produit
    $subscription = \Stripe\Subscription::create([
        'customer' => $customer->id,
        'items' => [
            ['price' => $_ENV['SUBSCRIPTION_PRICE_ID_SARTER_MONTHLY']],
        ],
    ]);

    // Envoyer un e-mail de confirmation à l'utilisateur
    // Mettre à jour la base de données de votre site web pour indiquer que l'utilisateur a un abonnement actif

    // Rediriger l'utilisateur vers une page de confirmation
    header('Location: confirmation.php');
    exit();
}

include PATH_SCRIPT . 'header.php';

?>
<main class="container mt-5">

    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Abonnement Starter</h3>
                </div>
                <div class="card-body">
                    <form method="post" id="subscription-form">
                        <div class="form-group">
                            <label>Informations de carte</label>
                            <div id="card-element" class="form-control"></div>
                            <div id="card-errors" role="alert"></div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            Payer maintenant
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('<?= $_ENV['API_PUBLIC_KEY'] ?>');
    const elements = stripe.elements();

    const card = elements.create('card');
    card.mount('#card-element');
    card.addEventListener('change', function(event) {
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

        const { token, error } = await stripe.createToken(card);

        if (error) {
            const displayError = document.getElementById('card-errors');
            displayError.textContent = error.message;
        } else {
            stripeTokenHandler(token);
        }
    });

    function stripeTokenHandler(token) {
        // Insérer le token dans le formulaire
        const form = document.getElementById('subscription-form');
        const hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);

        // Envoyer le formulaire
        form.submit();
    }
</script>
