<!DOCTYPE html>
<html lang="fr">

    <?php
    $title = "Cookorama - Modification du profil";
    include 'ressources/script/head.php';
    require_once PATH_SCRIPT . 'header.php';

    global $db;

    if (!isset($idUser)) {
        header('Location: ' . PATH_SITE);
        exit();
    }

    $selectInfo = $db->prepare('SELECT id, lastname, firstname, email, profilePicture, password, birthdate FROM user WHERE id = :id');
    $selectInfo->execute(array(
        'id' => $idUser
    ));

    $infos = $selectInfo->fetch();

    $id = $infos['id'];

    $birthdate = date('Y-m-d', strtotime($infos['birthdate']));
    $profilePicture = ADDRESS_IMG_PROFIL . $infos['profilePicture'];
    $password = $infos['password'];
    $lastname = $infos['lastname'];
    $firstname = $infos['firstname'];
    $email = $infos['email'];

    if($id == $_SESSION['id']) {
        $titlePage = "Modification de votre profil";
    } else {
        $titlePage = "Modification du profil de " . $firstname . " " . $lastname;
    }

    ?>

    <body>

        <main>

            <h2 class="text-center mt-4"><?= $titlePage ?></h2>

            <form action="<?= ADDRESS_SITE ?>profil/update" method="post" class="col-md-6" id="inscriptionForm" enctype="multipart/form-data" style="margin: 0 auto; padding: 15px;">

                <div class="mb-3" id="profilPicture">
                    <?php
                    if($profilePicture != ADDRESS_IMG_PROFIL){ ?>
                    <div style="width: fit-content; position: relative; margin: 0 auto;">
                        <img src="<?= $profilePicture ?>" alt="Photo de profil" width="300" height="300" style="border-radius: 15px;">
                        <i class="fa-solid fa-xmark fa-2xl" style="color: #ff0000; position: absolute; right: 3px; top: 15px; cursor: pointer;" onclick="deleteProfilPicture(<?= $_SESSION['id'] ?>)"  data-bs-toggle="tooltip" data-bs-title="Supprimer votre photo de profil" data-bs-placement="right"></i>
                    </div>
                    <?php } else { ?>
                    <label>Photo de profil</label>
                    <input type="file" class="form-control" id="profilePicture" name="profilePicture">
                    <?php } ?>
                </div>

                <div class="mb-3">
                    <label for="lastname" class="form-label">Nom *</label>
                    <input type="text" class="form-control" id="lastname" name="lastname" value="<?= $lastname ?>" required>
                </div>

                <div class="mb-3">
                    <label for="firstname" class="form-label">Prénom *</label>
                    <input type="text" class="form-control" id="firstname" name="firstname" value="<?= $firstname ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" class="form-control" id="emailInscription" name="email" value="<?= $email ?>" required>
                </div>

                <div class="mb-3">
                    <label for="birthdate" class="form-label">Date de naissance *</label>
                    <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?= $birthdate ?>" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe actuel</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>

                <div class="mb-3">
                    <label for="newPassword" class="form-label">Nouveau mot de passe</label>
                    <input type="password" class="form-control" id="newPassword" name="newPassword">

                    <div class="form-text">Laissez vide si vous ne souhaitez pas le modifier</div>

                </div>

                <div class="mb-3">
                    <label for="confirmNewPassword" class="form-label">Confirmer le nouveau mot de passe</label>
                    <input type="password" class="form-control" id="confirmNewPassword" name="confirmNewPassword">

                    <div class="form-text">Laissez vide si vous ne souhaitez pas le modifier</div>

                </div>

                <div class="g-recaptcha mb-4" data-sitekey="<?= $_ENV['CAPTCHA_SITE_KEY'] ?>" data-callback="recaptchaCallback" data-expired-callback="recaptchaExpired"></div>

                <div id="div-submit"></div>

            </form>

        </main>

        <script>

            $('form').submit(function (e) {

                if(confirm("Voulez-vous vraiment soumettre le formulaire ?")) {

                    if(!isValidEmail($('#emailInscription').val())) {

                        alert("L'adresse email n'est pas valide");

                        return false;

                    }

                } else {

                    return false

                }

            });

        </script>

    </body>

</html>
