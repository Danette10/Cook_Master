<?php
ob_start();
$stripeToken = $_POST['stripeToken'];
$email = $_POST['cardholderEmail'];
$name = $_POST['cardholderName'];
$priceId = $_POST['priceId'];
global $db;

$fidelity = 0;
switch ($subscriptionType) {

    case 'free':

        $role = 1;

        break;

    case 'starter':

        $role = 2;
        $fidelity = 30;

        break;

    case 'master':

        $role = 3;
        $fidelity = 60;

        break;

}


\Stripe\Stripe::setApiKey($_ENV['API_PRIVATE_KEY']);
$allCustomers = \Stripe\Customer::all();

$customer = null;

foreach ($allCustomers->data as $existingCustomer) {
    if (isset($existingCustomer->metadata['user_id']) && $existingCustomer->metadata['user_id'] == $_SESSION['id']) {
        $customer = $existingCustomer;
        break;
    }
}

if ($customer === null) {
    $customer = \Stripe\Customer::create([
        'email' => $email,
        'name' => $name,
        'preferred_locales' => ['fr'],
        'metadata' => [
            'user_id' => $_SESSION['id'],
        ],
    ]);
}


// Récupérer les empreintes des sources de paiement du client
$sources = \Stripe\Customer::allSources(
    $customer->id,
    ['object' => 'card']
);

// Récupérer l'empreinte de la carte soumise
$newCard = \Stripe\Token::retrieve($stripeToken);
$newCardFingerprint = $newCard->card->fingerprint;

// Vérifier si le moyen de paiement existe déjà
$existingCard = null;
foreach ($sources->data as $source) {
    if ($source->fingerprint == $newCardFingerprint) {
        $existingCard = $source;
        break;
    }
}

if ($existingCard === null) {
    // Ajouter le moyen de paiement au client
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

    // Mettre à jour la source de paiement par défaut
    $updatedCustomer = \Stripe\Customer::update(
        $customer->id,
        ['invoice_settings' => ['default_payment_method' => $updatedCard->id]]
    );
} else {
    // Utiliser le moyen de paiement existant comme source de paiement par défaut
    $updatedCustomer = \Stripe\Customer::update(
        $customer->id,
        ['invoice_settings' => ['default_payment_method' => $existingCard->id]]
    );
}

// Annuler tout les abonnements existants sur stripe
$subscriptions = \Stripe\Subscription::all([
    'customer' => $customer->id,
]);

foreach ($subscriptions->data as $subscription) {
    $subscription->cancel();
}


// Créer l'abonnement associé au produit
$subscription = \Stripe\Subscription::create([
    'customer' => $customer->id,
    'items' => [
        ['price' => $priceId],
    ],
]);

$updateUser = $db->prepare("UPDATE users SET role = :role WHERE idUser = :idUser");
$updateUser->execute([
    'role' => $role,
    'idUser' => $_SESSION['id']
]);

$customerID = $customer->id;
$userId = $_SESSION['id'];
$invoiceId = $subscription->latest_invoice;
$subscriptionId = $subscription->id;
$subscriptionStatus = $subscription->status;
$subscriptionPlan = $subscription->items->data[0]->price->id;
$subscriptionStart = date('Y-m-d H:i:s', $subscription->current_period_start);
$subscriptionEnd = date('Y-m-d H:i:s', $subscription->current_period_end);


// Si il existe un cart associé à l'utilisateur alors on le supprime pour le remplacer par le nouveau
$checkCart = $db->prepare("SELECT * FROM cart WHERE idUser = :idUser");
$checkCart->execute([
    'idUser' => $_SESSION['id']
]);

if($checkCart->rowCount() > 0) {

    $deleteCart = $db->prepare("DELETE FROM cart WHERE idUser = :idUser");
    $deleteCart->execute([
        'idUser' => $_SESSION['id']
    ]);

}

// On crée le cart
$createCart = $db->prepare("INSERT INTO cart (idUser) VALUES (:idUser)");
$createCart->execute([
    'idUser' => $_SESSION['id']
]);

// On récupère l'id du cart
$cartId = $db->lastInsertId();

$productId = \Stripe\Price::retrieve($priceId)->product;

$selectProduct = $db->prepare("SELECT id FROM products WHERE idProduct = :idProduct");
$selectProduct->execute([
    'idProduct' => $productId
]);

$productId = $selectProduct->fetch(PDO::FETCH_OBJ)->id;

$createCartItem = $db->prepare("INSERT INTO cart_item (quantity, id, idCart) VALUES (:quantity, :idProduct, :idCart)");
$createCartItem->execute([
    'quantity' => 1,
    'idProduct' => $productId,
    'idCart' => $cartId
]);

// On crée la commande
$createOrder = $db->prepare("INSERT INTO orders (idUser, idCart, idInvoice, pathInvoice) VALUES (:idUser, :idCart, :idInvoice, :pathInvoice)");
$createOrder->execute([
    'idUser' => $_SESSION['id'],
    'idCart' => $cartId,
    'idInvoice' => $invoiceId,
    'pathInvoice' => ''
]);

$cancelSubscription = $db->prepare("UPDATE stripe_consumer SET subscriptionStatus = :subscriptionStatus WHERE idUser = :idUser");
$cancelSubscription->execute([
    'subscriptionStatus' => 'canceled',
    'idUser' => $_SESSION['id']
]);

$insertStripeCustomer = $db->prepare("INSERT INTO stripe_consumer (idConsumer, creation, idUser, subscriptionId, subscriptionStatus) VALUES (:idConsumer, :creation, :idUser, :subscriptionId, :subscriptionStatus)");
$insertStripeCustomer->execute([
    'idConsumer' => $customerID,
    'creation' => date('Y-m-d H:i:s'),
    'idUser' => $userId,
    'subscriptionId' => $subscriptionId,
    'subscriptionStatus' => 'active'
]);

$updateUser = $db->prepare("UPDATE users SET fidelityCounter = fidelityCounter + :fidelity WHERE idUser = :idUser");
$updateUser->execute([
    'fidelity' => $fidelity,
    'idUser' => $_SESSION['id']
]);

if($subscription->status == 'active') {

    header('Location: ' . ADDRESS_SITE . 'confirm/1/' . $subscriptionType . '/' . $plan);
    exit();

} else {

    header('Location: ' . ADDRESS_SITE . 'confirm/0/' . $subscriptionType . '/' . $plan);
    exit();

}
ob_end_flush();