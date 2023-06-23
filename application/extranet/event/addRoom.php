<?php
if(!isset($_SESSION['id']) || $_SESSION['role'] != 4 && $_SESSION['role'] != 5){
    header('Location: /');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<?php
$title = "Cookorama - Déclarer une salle";
include 'ressources/script/head.php';
require_once PATH_SCRIPT . 'header.php';

global $db;

$selectProducts = $db->prepare('SELECT id, name, quantity FROM products WHERE type = 2 AND quantity > 0');
$selectProducts->execute();
$products = $selectProducts->fetchAll();

?>

<body>

<main>

    <?php include PATH_SCRIPT . 'messages.php'; ?>

    <div class="text-center mt-4">
        <h1>Déclarer une salle</h1>
    </div>

    <form action="<?= ADDRESS_SITE ?>évènements/déclarer-une-salle/check" method="POST" enctype="multipart/form-data">
        <div class="container mt-4 d-flex flex-column align-items-center">
            <div class="col-6">
                <label for="name" style="font-weight: bold;">Nom de la salle <span style="color: red;">*</span></label>
                <input type="text" class="form-control shadow" placeholder="Nom de la salle" name="name" required>
            </div>
            <div class="col-6 mt-3">
                <label for="capacity" style="font-weight: bold;">Capacité de la salle <span style="color: red;">*</span></label>
                <input type="number" class="form-control shadow" placeholder="Capacité de la salle" name="capacity" required>
            </div>
            <div class="col-6 mt-3">
                <label for="image" style="font-weight: bold;">Image de la salle <span style="color: red;">*</span></label>
                <input type="file" class="form-control shadow" name="image" required>
            </div>
            <div class="col-6 mt-3">
                <label for="description" style="font-weight: bold;">Description de la salle <span style="color: red;">*</span></label>
                <textarea class="form-control shadow" placeholder="Description de la salle" name="description" required></textarea>
            </div>
            <div class="col-6 mt-3">
                <label for="address" style="font-weight: bold;">Adresse de la salle <span style="color: red;">*</span></label>
                <input type="text" class="form-control shadow" id="adresse" placeholder="Adresse de la salle" name="address" required>
            </div>
            <div class="col-6 mt-3">
                <label for="city" style="font-weight: bold;">Ville de la salle <span style="color: red;">*</span></label>
                <input type="text" class="form-control shadow" id="city" placeholder="Ville de la salle" name="city" readonly required>
            </div>
            <div class="col-6 mt-3">
                <label for="zip_code" style="font-weight: bold;">Code postal de la salle <span style="color: red;">*</span></label>
                <input type="text" class="form-control shadow" id="postal_code" placeholder="Code postal de la salle" name="zip_code" readonly required>
            </div>
            <div class="col-6 mt-3">
                <label for="products" style="font-weight: bold;">Ajouter des produits à la salle <span style="color: red;">*</span></label>
                <div class="selectProduct">
                    <?php
                    if(count($products) > 0):
                        foreach ($products as $product):
                    ?>
                        <div class="d-flex align-items-center justify-content-between col-md-4 mb-2" id="product_<?= $product['id'] ?>">

                            <div>
                                <input type="checkbox" name="products[]" value="<?= $product['id'] ?>" onchange="chooseProduct(this.value)">
                                <?= $product['name'] ?> (<?= $product['quantity'] ?>)
                            </div>

                        </div>
                    <?php
                        endforeach;
                    ?>
                </div>
                    <?php
                    else:
                    ?>

                    <p class="text-danger">Aucun produit disponible</p>
                    <?php
                    endif;
                    ?>
            </div>
            <div class="col-6 mt-3">
                <button type="submit" class="btn btn-success shadow" name="submit">Déclarer la salle</button>
            </div>
        </div>
    </form>

</main>

<script>

    $(document).ready(function() {
        autoCompleteAddress();
    });

    function chooseProduct(id) {
        let product = $('#product_' + id);
        let checkbox = product.find('input[type="checkbox"]');
        if(checkbox.is(':checked')) {
            product.append('<input type="number" name="quantity[]" placeholder="Quantité" class="form-control shadow" style="width: 100px; display: inline-block;" required>');
        } else {
            product.find('input[type="number"]').remove();
        }

    }

    $('form').submit(function() {
        // Vérifier qu'au moins un produit est sélectionné
        let products = $('input[name="products[]"]');
        let checked = false;
        for(let i = 0; i < products.length; i++) {
            if(products[i].checked) {
                checked = true;
                break;
            }
        }
        if(!checked) {
            alert('Veuillez sélectionner au moins un produit');
            return false;
        }

        // Vérifier que la quantité est renseignée pour chaque produit
        let quantities = $('input[name="quantity[]"]');
        for(let i = 0; i < quantities.length; i++) {
            if(quantities[i].value === '') {
                alert('Veuillez renseigner la quantité pour chaque produit');
                return false;
            }
        }

        return true;
    });


</script>

</body>
