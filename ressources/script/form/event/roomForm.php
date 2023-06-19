<?php
include PATH_SCRIPT . "functions.php";

global $db;

$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : null;
$capacity = isset($_POST['capacity']) ? htmlspecialchars(intval($_POST['capacity'])) : null;
$image = $_FILES['image'] ?? null;
$description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : null;
$address = isset($_POST['address']) ? htmlspecialchars($_POST['address']) : null;
$city = isset($_POST['city']) ? htmlspecialchars($_POST['city']) : null;
$zip_code = isset($_POST['zip_code']) ? htmlspecialchars($_POST['zip_code']) : null;
$products = $_POST['products'] ?? null;
$quantity = $_POST['quantity'] ?? null;

$errors = [];

if (empty($name)) {
    $errors['name'] = "Veuillez renseigner le nom de la salle";
}

if (empty($capacity)) {
    $errors['capacity'] = "Veuillez renseigner la capacité de la salle";
}

if ($capacity < 1) {
    $errors['capacity'] = "La capacité de la salle doit être supérieure à 0";
}

if (empty($image)) {
    $errors['image'] = "Veuillez renseigner l'image de la salle";
}

if (empty($description)) {
    $errors['description'] = "Veuillez renseigner la description de la salle";
}

if (empty($address)) {
    $errors['address'] = "Veuillez renseigner l'adresse de la salle";
}

if (empty($city)) {
    $errors['city'] = "Veuillez renseigner la ville de la salle";
}

if (empty($zip_code)) {
    $errors['zip_code'] = "Veuillez renseigner le code postal de la salle";
}

if (empty($products)) {
    $errors['product'] = "Veuillez renseigner le produit";
}

if (empty($quantity)) {
    $errors['quantity'] = "Veuillez renseigner la quantité";
}


foreach ($products as $key => $value) {
    $selectProduct = $db->prepare("SELECT * FROM products WHERE id = :id");
    $selectProduct->execute([
        'id' => $value
    ]);

    $product = $selectProduct->fetch(PDO::FETCH_ASSOC);

}

if(empty($errors)) {

    // Ajout de l'adresse
    $addAddress = $db->prepare("INSERT INTO place (address, city, postalCode) VALUES (:address, :city, :postalCode)");
    $addAddress->execute([
        'address' => $address,
        'city' => $city,
        'postalCode' => $zip_code
    ]);

    // Récupération de l'id de l'adresse
    $idAddress = $db->lastInsertId();

    // Enregistrement de l'image
    if(isset($image) && $image['error'] == 0) {

        if($image['size'] <= 5000000) {

            $imageExtension = pathinfo($image['name'], PATHINFO_EXTENSION);

            $allowedExtensions = ['jpg', 'jpeg', 'png'];

            if(in_array($imageExtension, $allowedExtensions)) {

                $file = uploadPicture('roomImage', $image);

                $date = date('Y-m-d H:i:s');

                $addRoom = $db->prepare('INSERT INTO rooms (name, capacity, image, description, idPlace, creation) VALUES (:name, :capacity, :image, :description, :idPlace, :creation)');
                $addRoom->execute([
                    'name' => $name,
                    'capacity' => $capacity,
                    'image' => $file,
                    'description' => $description,
                    'idPlace' => $idAddress,
                    'creation' => $date
                ]);

                $idRoom = $db->lastInsertId();

                foreach ($products as $key => $value) {
                    $addProduct = $db->prepare("INSERT INTO rooms_equipment (idRoom, idProduct, quantity) VALUES (:idRoom, :idProduct, :quantity)");
                    $addProduct->execute([
                        'idRoom' => $idRoom,
                        'idProduct' => $value,
                        'quantity' => $quantity[$key]
                    ]);

                    $updateProduct = $db->prepare("UPDATE products SET quantity = quantity - :quantity WHERE id = :id");
                    $updateProduct->execute([
                        'quantity' => $quantity[$key],
                        'id' => $value
                    ]);

                }

                header('Location: ' . ADDRESS_SITE . '/évènements/déclarer-une-salle/?type=success&message=La salle a bien été ajoutée');
                exit();
            } else {
                $errors['image'] = "Le format de l'image n'est pas valide";

                header('Location: ' . ADDRESS_SITE . '/évènements/déclarer-une-salle/?type=error&message=Le format de l\'image n\'est pas valide');
                exit();
            }
        } else {
            $errors['image'] = "L'image est trop volumineuse";

            header('Location: ' . ADDRESS_SITE . '/évènements/déclarer-une-salle/?type=error&message=L\'image est trop volumineuse');
            exit();
        }
    } else {
        $errors['image'] = "Une erreur est survenue lors de l'envoi de l'image";

        header('Location: ' . ADDRESS_SITE . '/évènements/déclarer-une-salle/?type=error&message=Une erreur est survenue lors de l\'envoi de l\'image');
        exit();
    }
} else {
    header('Location: ' . ADDRESS_SITE . '/évènements/déclarer-une-salle/?type=error&message=Une erreur est survenue lors de l\'envoi du formulaire');
    exit();
}