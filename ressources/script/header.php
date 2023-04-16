<header>

    <nav class="navbar navbar-expand-lg">

        <div class="container-fluid">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">

                <span class="navbar-toggler-icon"></span>

            </button>

            <li class="nav-item">

                <a href="<?= ADDRESS_SITE ?>"><img src="<?= ADDRESS_IMG ?>logo.png" alt="Cookorama Logo" width="300" class="nav-link"></a>

            </li>

            <div class="collapse navbar-collapse ms-3" id="navbarToggler" style="margin-right: 50px;">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="font-size: 22px; position: relative; top: -3px;">

                    <li>

                        <a href="<?= ADDRESS_SITE ?>" class="nav-link">Accueil</a>

                    </li>

                    <li>

                        <a href="<?= ADDRESS_SITE ?>recettes" class="nav-link">Recettes</a>

                    </li>

                    <li>

                        <a href="<?= ADDRESS_SITE ?>leçons" class="nav-link">Leçons</a>

                    </li>

                </ul>

                <div class="connexionInscriptionLinks">

                    <button class="pricingLink btn">

                        <a href="<?= ADDRESS_SITE ?>subscribe" class="nav-link">S'abonner</a>

                    </button>

                    <?php if (!isset($_SESSION['role']) || $_SESSION['role'] == 0) { ?>

                    <li>

                        <button type="button" class="connexionLink btn" data-bs-toggle="modal" data-bs-target="#connexionModal">
                            Connexion
                        </button>

                    </li>

                    <button class="inscriptionLink btn">

                        <a href="<?= ADDRESS_SITE ?>inscription" class="nav-link">Inscription</a>

                    </button>

                    <?php } ?>

                </div>

            </div>

            <?php if (isset($_SESSION['email'])){

                global $db;

                $selectProfilePicture = $db->prepare('SELECT profilePicture FROM user WHERE id = :id');
                $selectProfilePicture->execute(array(
                    'id' => $_SESSION['id']
                ));

                $profilePicture = $selectProfilePicture->fetch();
                $profilePicture = $profilePicture['profilePicture'];
                ?>

            <div class="dropdown" style="position: relative; right: 80px;">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">

                    <?php if ($profilePicture != ''){ ?>
                    <img src="<?= ADDRESS_IMG_PROFIL . $profilePicture ?>" alt="Profile picture" width="60" height="60" class="rounded-circle">
                    <?php } else { ?>
                    <img src="<?= ADDRESS_DEFAULT_PROFIL ?>" alt="Profile picture" width="50" height="50" class="rounded-circle">
                    <?php } ?>
                </button>

                <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="<?= ADDRESS_SITE ?>profil">Profil</a></li>

                    <hr class="dropdown-divider">

                    <li><a class="dropdown-item" href="<?= ADDRESS_SCRIPT ?>logout.php">Décconnexion</a></li>

                </ul>
            </div>

            <?php } ?>


        </div>

    </nav>

</header>

<div class="modal fade" id="connexionModal" tabindex="-1" aria-labelledby="connexionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="connexionModalLabel">Connexion</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php include PATH_APPLICATION_EXTRANET . 'connexionForm.php'; ?>
            </div>
        </div>
    </div>
</div>