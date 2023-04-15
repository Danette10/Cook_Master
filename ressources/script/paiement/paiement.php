<?php
ob_start();
$stripeToken = $_POST['stripeToken'];
$email = $_POST['cardholderEmail'];
$name = $_POST['cardholderName'];
$priceId = $_POST['priceId'];
global $db;


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

// Si il a déjà un abonnement actif, on le désactive
$selectSubscription = $db->prepare("SELECT * FROM stripe_consumer WHERE user_id = :user_id AND subscription_status = 'active'");
$selectSubscription->execute([
    'user_id' => $userId
]);
$existingSubscription = $selectSubscription->fetch();
if($existingSubscription) {
    $updateSubscription = $db->prepare("UPDATE stripe_consumer SET subscription_status = 'inactive' WHERE user_id = :user_id");
    $updateSubscription->execute([
        'user_id' => $userId
    ]);

    $subscriptionLast = \Stripe\Subscription::retrieve($existingSubscription['subscription_id']);
    try {
        $subscriptionLast->cancel();
    } catch (\Stripe\Exception\ApiErrorException $e) {
        // The subscription has already been canceled.

    }
}


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

    header('Location: ' . ADDRESS_SITE . 'confirm/1/' . $subscriptionType . '/' . $plan);
    exit();

} else {

    header('Location: ' . ADDRESS_SITE . 'confirm/0/' . $subscriptionType . '/' . $plan);
    exit();

}
ob_end_flush();