<header>

    <nav class="navbar navbar-expand-lg">

        <div class="container-fluid">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">

                <span class="navbar-toggler-icon"></span>

            </button>

            <li class="nav-item">

                <a href="<?= ADDRESS_SITE ?>"><img src="<?= ADDRESS_IMG ?>logo.png" alt="Cook Master Logo" width="100" class="nav-link"></a>

            </li>

            <div class="collapse navbar-collapse" id="navbarToggler">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li>

                        <a href="<?= ADDRESS_SITE ?>" class="nav-link">Home</a>

                    </li>

                </ul>

                <div class="connexionInscriptionLinks">

                    <?php if (!isset($_SESSION['role']) || ($_SESSION['role'] == 1 || $_SESSION['role'] == 0)) { ?>

                    <button class="pricingLink btn">

                        <a href="<?= ADDRESS_SITE ?>pricing" class="nav-link">Pricing</a>

                    </button>

                    <?php } ?>

                    <?php if (!isset($_SESSION['role']) || $_SESSION['role'] == 0) { ?>

                    <li>

                        <button type="button" class="connexionLink btn" data-bs-toggle="modal" data-bs-target="#connexionModal">
                            Connexion
                        </button>

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

                    </li>

                    <button class="inscriptionLink btn">

                        <a href="<?= ADDRESS_SITE ?>inscription" class="nav-link">Inscription</a>

                    </button>

                    <?php } ?>

                </div>

            </div>

            <?php if (isset($_SESSION['email'])){

                if($_SESSION['profilePicture'] != ''){
                    $profilePicture = $_SESSION['profilePicture'];
                }else{
                    $profilePicture = 'defaultProfilePicture.png';
                }
                ?>

            <div class="dropdown" style="margin-right: 80px;">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="<?= ADDRESS_IMG ?>profilePicture/<?= $profilePicture ?>" alt="Profile picture" width="50" height="50" style="margin-left: 10px;" class="rounded-circle">
                </button>

                <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="<?= ADDRESS_SITE ?>profil">Profil</a></li>

                    <hr class="dropdown-divider">

                    <li><a class="dropdown-item" href="<?= ADDRESS_SCRIPT ?>logout.php">Logout</a></li>

                </ul>
            </div>

            <?php } ?>


        </div>

    </nav>

</header>