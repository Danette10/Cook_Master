<?php
ob_start();
$stripeToken = $_POST['stripeToken'];
$email = $_POST['cardholderEmail'];
$name = $_POST['cardholderName'];
$priceId = $_POST['priceId'];
global $db;

switch ($subscriptionType) {

    case 'free':

        $role = 1;

        break;

    case 'starter':

        $role = 2;

        break;

    case 'master':

        $role = 3;

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

// Si il a déjà un abonnement actif, on le désactive
$selectSubscription = $db->prepare("SELECT idConsumer, subscriptionId, subscriptionStatus FROM stripe_consumer WHERE idUser = :userId");
$selectSubscription->execute([
    'userId' => $userId
]);
$existingSubscription = $selectSubscription->fetch();

if($existingSubscription['subscriptionStatus'] == 'active') {
    $updateSubscription = $db->prepare("UPDATE stripe_consumer SET subscriptionStatus = 'canceled' WHERE idUser = :idUser");
    $updateSubscription->execute([
        'idUser' => $userId
    ]);

    $subscriptionLast = \Stripe\Subscription::retrieve($existingSubscription['subscriptionId']);
    try {
        $subscriptionLast->cancel();
    } catch (\Stripe\Exception\ApiErrorException $e) {
        // The subscription has already been canceled.

    }
}

// Si il a déjà eu le même abonnement, on update la ligne
$selectSubscription = $db->prepare("SELECT * FROM stripe_consumer WHERE idUser = :idUser AND subscriptionId = :subscriptionId");
$existingSubscription = $selectSubscription->fetch();
if($existingSubscription) {

    $updateSubscription = $db->prepare("UPDATE stripe_consumer SET subscriptionStatus = :subscriptionStatus WHERE idUser = :idUser AND subscriptionId = :subscriptionId");
    $updateSubscription->execute([
        'subscriptionStatus' => $subscriptionStatus,
        'idUser' => $userId,
        'subscriptionId' => $subscriptionId
    ]);

}else{
    // Sinon on insert une nouvelle ligne
    $insertCart = $db->prepare("INSERT INTO cart (idUser) VALUES (:idUser)");
    $insertCart->execute([
        'idUser' => $userId
    ]);

    $cartId = $db->lastInsertId();

    $idProduct = \Stripe\Price::retrieve($priceId)->product;

    $insertCartItem = $db->prepare("INSERT INTO cart_item (quantity, idProduct, idCart) VALUES (:quantity, :idProduct, :idCart)");
    $insertCartItem->execute([
        'quantity' => 1,
        'idProduct' => $idProduct,
        'idCart' => $cartId
    ]);

    $insertSubscription = $db->prepare("INSERT INTO stripe_consumer (idConsumer, creation, idUser, subscriptionId, subscriptionStatus) VALUES (:idConsumer, :creation, :idUser, :subscriptionId, :subscriptionStatus)");
    $insertSubscription->execute([
        'idConsumer' => $customerID,
        'creation' => date('Y-m-d H:i:s'),
        'idUser' => $userId,
        'subscriptionId' => $subscriptionId,
        'subscriptionStatus' => 'active'
    ]);

    $insertOrder = $db->prepare("INSERT INTO orders (idUser, idCart, idInvoice, pathInvoice) VALUES (:idUser, :idCart, :idInvoice, :pathInvoice)");
    $insertOrder->execute([
        'idUser' => $userId,
        'idCart' => $cartId,
        'idInvoice' => $invoiceId,
        'pathInvoice' => ''
    ]);
}

if($subscription->status == 'active') {

    header('Location: ' . ADDRESS_SITE . 'confirm/1/' . $subscriptionType . '/' . $plan);
    exit();

} else {

    header('Location: ' . ADDRESS_SITE . 'confirm/0/' . $subscriptionType . '/' . $plan);
    exit();

}
ob_end_flush();