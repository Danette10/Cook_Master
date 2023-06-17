<header>
	<nav class="navbar navbar-expand-lg navbar-light bg-light" id="nav-custom">
        <div class="container-fluid">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">

                <span class="navbar-toggler-icon"></span>

            </button>

            <li class="nav-item">

                <a href="<?= ADDRESS_SITE ?>"><img src="<?= ADDRESS_IMG ?>logo.png" alt="Cookorama Logo" width="300" class="nav-link"></a>

            </li>

            <div class="collapse navbar-collapse ms-3" id="navbarToggler" style="margin-right: 60px;">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="font-size: 22px; position: relative; top: -3px;">

                    <li>

                        <a href="<?= ADDRESS_SITE ?>" class="nav-link lang-home"></a>

                    </li>

                    <li>

                        <a href="<?= ADDRESS_SITE ?>recettes" class="nav-link lang-recipe"></a>

                    </li>

                    <li>

                        <a href="<?= ADDRESS_SITE ?>cours" class="nav-link lang-course"></a>

                    </li>

                    <li>

                        <a href="<?= ADDRESS_SITE ?>évènements" class="nav-link lang-event"></a>

                    </li>

                    <li>

                        <a href="<?= ADDRESS_SITE ?>boutique" class="nav-link lang-shop"></a>

                    </li>

                    <?php
                    if(isset($_SESSION['role']) && $_SESSION['role'] == 5){?>
                    <li>

                        <a href="<?= ADDRESS_SITE ?>admin/dashboard" class="nav-link lang-dashboard"></a>

                    </li>
                    <?php } ?>

                    <?php
                    if(isset($_SESSION['role']) && ($_SESSION['role'] == 2 || $_SESSION['role'] == 3 || $_SESSION['role'] == 4)){?>
                    <li>

                        <a href="<?= ADDRESS_SITE ?>extranet/messages" class="nav-link lang-chat"></a>

                    </li>
                    <?php } ?>
                </ul>

                <div class="connexionInscriptionLinks">

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="languageSelecter" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            FR
                        </a>
                        <ul class="dropdown-menu mt-3" aria-labelledby="navbarDropdown">
                            <li>
                                <div class="dropdown-item" onclick="changeLang('fr')">FR</div>
                            </li>
                            <li>
                                <div class="dropdown-item" onclick="changeLang('en')">EN</div>
                            </li>
                        </ul>
                    </li>

                    <div class="me-3">

                        <img style="width:24px" src="<?= ADDRESS_IMG ?>moon.png" id="icon">

                    </div>

                    <?php
                    if(!isset($_SESSION['id'])){
                        $attr = 'data-bs-toggle="tooltip" data-bs-title="Vous devez être connecté pour accéder à votre panier" data-bs-placement="bottom"';
                        $style = 'style="cursor: not-allowed"';
                    }else{
                        $attr = '';
                        $style = '';
                    }
                    ?>

                    <a href="<?= ADDRESS_SITE ?>panier" class="nav-link" <?= $attr ?> <?= $style ?> id="cartLink">
                        <div class="me-3" id="cartElement">
                            <img src="<?= ADDRESS_IMG ?>shopping-cart.png" alt="Panier" width="30" height="30" id="cartIcon">
                            <span id="nbElemCartSpan"></span>
                        </div>
                    </a>

                    <?php
                    if(isset($_SESSION['role']) && $_SESSION['role'] != 5){?>

                    <button class="pricingLink btn">

                        <a href="<?= ADDRESS_SITE ?>subscribe" class="nav-link lang-subscribe"></a>

                    </button>

                    <?php } ?>

                    <?php if (!isset($_SESSION['role']) || $_SESSION['role'] == 0) { ?>

                    <li>

                        <button type="button" class="connexionLink btn lang-login" data-bs-toggle="modal" data-bs-target="#connexionModal">
                        </button>

                    </li>

                    <button class="inscriptionLink btn">

                        <a href="<?= ADDRESS_SITE ?>inscription" class="nav-link lang-registration"></a>

                    </button>

                    <?php } ?>

                </div>

            </div>

            <?php if (isset($_SESSION['email'])){

                global $db;

                $selectProfilePicture = $db->prepare('SELECT profilePicture FROM users WHERE idUser = :idUser');
                $selectProfilePicture->execute(array(
                    'idUser' => $_SESSION['id']
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
    <script>

       let icon = document.getElementById("icon");
       let cartIcon = document.getElementById("cartIcon");

       icon.onclick = function(){

           document.body.classList.toggle("dark-theme");

           if(document.body.classList.contains("dark-theme")){

               icon.src = '<?= ADDRESS_IMG ?>' + "sun.png";
               cartIcon.src = '<?= ADDRESS_IMG ?>' + "shopping-cart-white.png";
               sessionStorage.setItem("theme", "dark");

           }else{

               icon.src = '<?= ADDRESS_IMG ?>' + "moon.png";
               cartIcon.src = '<?= ADDRESS_IMG ?>' + "shopping-cart.png";
               sessionStorage.setItem("theme", "light");

           }

       }

         if(sessionStorage.getItem("theme") === "dark"){

             document.body.classList.add("dark-theme");
             icon.src = '<?= ADDRESS_IMG ?>' + "sun.png";
             cartIcon.src = '<?= ADDRESS_IMG ?>' + "shopping-cart-white.png";

         }else{

            document.body.classList.remove("dark-theme");
            icon.src = '<?= ADDRESS_IMG ?>' + "moon.png";
            cartIcon.src = '<?= ADDRESS_IMG ?>' + "shopping-cart.png";

         }
  </script>

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