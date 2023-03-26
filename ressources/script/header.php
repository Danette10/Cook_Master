<?php include "init.php"; ?>
<header>

    <nav class="navbar navbar-expand-lg">

        <div class="container-fluid">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">

                <span class="navbar-toggler-icon"></span>

            </button>

            <li class="nav-item">

                <a href="<?= PATH_SITE ?>"><img src="<?= PATH_IMG ?>logo.png" alt="Cook Master Logo" width="100" class="nav-link"></a>

            </li>

            <div class="collapse navbar-collapse" id="navbarToggler">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li>

                        <a href="<?= PATH_SITE ?>" class="nav-link">Home</a>

                    </li>

                </ul>

                <div class="connexionInscriptionLinks">

                    <li>

                        <!-- Button trigger modal -->
                        <button type="button" class="connexionLink btn" data-bs-toggle="modal" data-bs-target="#connexionModal">
                            Connexion
                        </button>

                        <!-- Starting modal connexion -->
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

                        <a href="<?= PATH_SITE ?>inscription" class="nav-link">Inscription</a>

                    </button>

                </div>

            </div>

        </div>

    </nav>

</header>