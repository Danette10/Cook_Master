<?php
include PATH_SCRIPT . "functions.php";

global $db;

\Stripe\Stripe::setApiKey($_ENV['API_PRIVATE_KEY']);

$name = htmlspecialchars($_POST['name']);
$description = htmlspecialchars($_POST['description']);
$price = htmlspecialchars(intval($_POST['price']));
$quantity = htmlspecialchars(intval($_POST['quantity']));
$image = $_FILES['image'];

if(!empty($name) && !empty($description) && !empty($price) && !empty($quantity) && !empty($image)) {

    if($price > 0 && $quantity > 0) {

        if(isset($image) && $image['error'] == 0) {

            if($image['size'] <= 5000000) {

                $imageExtension = pathinfo($image['name'], PATHINFO_EXTENSION);

                $allowedExtensions = ['jpg', 'jpeg', 'png'];
                if(in_array($imageExtension, $allowedExtensions)) {

                    $file = uploadPicture('shopImage', $image);

                    $date = date('Y-m-d H:i:s');

                    // Ajout du produit sur stripe
                    $product = \Stripe\Product::create([
                        'name' => $name,
                        'description' => $description,
                        'type' => 'good',
                        'metadata' => ['quantity' => $quantity],
                    ]);

                    $priceStripe = \Stripe\Price::create([
                        'product' => $product->id,
                        'unit_amount' => $price * 100,
                        'currency' => 'eur',
                    ]);

                    $addProduct = $db->prepare('INSERT INTO products (idProduct, name, description, image, type, price, creation) VALUES (:idProduct, :name, :description, :image, :type, :price, :creation)');
                    $addProduct->execute([
                        'idProduct' => $product->id,
                        'name' => $name,
                        'description' => $description,
                        'image' => $file,
                        'type' => 2,
                        'price' => $price,
                        'creation' => $date
                    ]);


                    header('Location: ' . ADDRESS_SITE . 'boutique/ajout-produit/?type=success&message=Le produit a bien été ajouté');
                    exit();

                } else {
                    header('Location: ' . ADDRESS_SITE . 'boutique/ajout-produit/error');
                    exit();
                }

            } else {
                header('Location: ' . ADDRESS_SITE . 'boutique/ajout-produit/error');
                exit();
            }

        } else {
            header('Location: ' . ADDRESS_SITE . 'boutique/ajout-produit/error');
            exit();
        }

    } else {
        header('Location: ' . ADDRESS_SITE . 'boutique/ajout-produit/error');
        exit();
    }
}
