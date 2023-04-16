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

// Si il a déjà un abonnement actif, on le désactive
$selectSubscription = $db->prepare("SELECT * FROM stripe_consumer WHERE userId = :userId AND subscriptionStatus = 'active'");
$selectSubscription->execute([
    'userId' => $userId
]);
$existingSubscription = $selectSubscription->fetch();
if($existingSubscription) {
    $updateSubscription = $db->prepare("UPDATE stripe_consumer SET subscriptionStatus = 'canceled' WHERE userId = :userId");
    $updateSubscription->execute([
        'userId' => $userId
    ]);

    $subscriptionLast = \Stripe\Subscription::retrieve($existingSubscription['subscriptionId']);
    try {
        $subscriptionLast->cancel();
    } catch (\Stripe\Exception\ApiErrorException $e) {
        // The subscription has already been canceled.

    }
}

// Si il a déjà eu le même abonnement, on update la ligne
$selectSubscription = $db->prepare("SELECT * FROM stripe_consumer WHERE userId = :userId AND subscriptionPlan = :subscriptionPlan");
$selectSubscription->execute([
    'userId' => $userId,
    'subscriptionPlan' => $subscriptionPlan
]);
$existingSubscription = $selectSubscription->fetch();
if($existingSubscription) {
    $updateSubscription = $db->prepare("UPDATE stripe_consumer SET 
                            customerId = :customerId, 
                            invoiceId = :invoiceId, 
                            subscriptionId = :subscriptionId, 
                            subscriptionStatus = :subscriptionStatus, 
                            subscriptionStartDate = :subscriptionStartDate, 
                            subscriptionEndDate = :subscriptionEndDate, 
                            pathInvoice = :pathInvoice
                            WHERE userId = :userId AND subscriptionPlan = :subscriptionPlan");
    $updateSubscription->execute([
        'customerId' => $customerID,
        'invoiceId' => $invoiceId,
        'subscriptionId' => $subscriptionId,
        'subscriptionStatus' => 'active',
        'subscriptionStartDate' => date('Y-m-d H:i:s', strtotime('+2 hour')),
        'subscriptionEndDate' => date('Y-m-d H:i:s', strtotime('+2 hour +1 month')),
        'pathInvoice' => '',
        'userId' => $userId,
        'subscriptionPlan' => $subscriptionPlan
    ]);
}else{
    $insertSubscription = $db->prepare("INSERT INTO stripe_consumer(
                            customerId, userId, invoiceId, subscriptionId, 
                            subscriptionStatus, subscriptionPlan, subscriptionStartDate, 
                            subscriptionEndDate, pathInvoice
                            ) VALUES(
                                     :customerId, :userId, :invoiceId, :subscriptionId, :subscriptionStatus, 
                                     :subscriptionPlan, :subscriptionStartDate, :subscriptionEndDate, :pathInvoice
                                     )");
    $insertSubscription->execute([
        'customerId' => $customerID,
        'userId' => $userId,
        'invoiceId' => $invoiceId,
        'subscriptionId' => $subscriptionId,
        'subscriptionStatus' => 'active',
        'subscriptionPlan' => $subscriptionPlan,
        'subscriptionStartDate' => $subscriptionStart,
        'subscriptionEndDate' => $subscriptionEnd,
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