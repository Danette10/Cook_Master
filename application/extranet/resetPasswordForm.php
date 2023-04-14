<?php
$title = "Cookorama - Reset Password";
include '../../ressources/script/head.php';
$token = isset($_GET['token']) ? htmlspecialchars($_GET['token']) : '';
$email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';

if($token != ''){
    $selectToken = $db->prepare('SELECT * FROM user WHERE token = :token');
    $selectToken->execute(array(
        'token' => $token
    ));

    $tokenExist = $selectToken->rowCount();

    if($tokenExist == 0){
        header('Location: ' . ADDRESS_SITE);
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<?php
include PATH_SCRIPT . 'header.php';

?>
<body>

<main>

    <?php if($token == ''){ ?>

        <form action="<?= ADDRESS_FORM ?>resetPassword.php" class="col-md-6" method="post" style="margin: 0 auto; padding: 15px;">

            <div class="mb-3">
                <label for="name" class="form-label">Email *</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <input type="hidden" name="token" value="<?= $token ?>">
            <input type="submit" class="btn" value="Submit">

        </form>

    <?php } else{ ?>

        <form action="<?= ADDRESS_FORM ?>resetPassword.php" class="col-md-6" method="post" style="margin: 0 auto; padding: 15px;">

            <div class="mb-3">
                <label for="name" class="form-label">Nouveau mot de passe *</label>
                <input type="password" class="form-control" id="passwordReset" name="passwordReset" required>

                <div id="viewPassword" class="form-text">

                    <input type="checkbox" id="showPassword" name="showPassword" onclick="displayPassword('passwordReset')">
                    <label class="form-label">Voir le mot de passe</label>

                </div>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Confirmation du nouveau mot de passe *</label>
                <input type="password" class="form-control" id="passwordConfReset" name="passwordConfReset" required>

                <div id="viewPassword" class="form-text">

                    <input type="checkbox" id="showPassword" name="showPassword" onclick="displayPassword('passwordConfReset')">
                    <label class="form-label">Voir le mot de passe</label>

                </div>
            </div>

            <input type="hidden" name="token" value="<?= $token ?>">
            <input type="hidden" name="email" value="<?= $email ?>">
            <input type="submit" class="btn" value="Submit">

        </form>

    <?php } ?>

</main>




<?php
include PATH_SCRIPT . 'functionsJs.php';
include PATH_SCRIPT . 'footer.php';
?>

</body>
