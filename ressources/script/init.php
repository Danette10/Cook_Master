<?php

/*
 * TODO: Link Local
 */

/**
 * VARIABLES
 */

define('MASTER_YEARLY', 'prod_NeXRwi2aT28FXA');
define('MASTER_MONTHLY', 'prod_NeXRHRc6d5UjRq');
define('STARTER_YEARLY', 'prod_NeXQV8mjycAaDE');
define('STARTER_MONTHLY', 'prod_NeXPCayzYQqXgS');

/*
 *TODO: PATH
 */

define('PATH_SITE', $_SERVER['DOCUMENT_ROOT'] . '/Cook_Master/');
define('PATH_LOG', PATH_SITE . 'log/');
define('PATH_RESSOURCES', PATH_SITE . 'ressources/');
define('PATH_CSS', PATH_RESSOURCES . 'css/');
define('PATH_JS', PATH_RESSOURCES . 'js/');
define('PATH_IMG', PATH_RESSOURCES . 'images/');
define('PATH_PRICING_ICON', PATH_IMG . 'pricingIcon/');
define('PATH_SCRIPT', PATH_RESSOURCES . 'script/');
define('PATH_SCRIPT_PROFIL', PATH_SCRIPT . 'profil/');
define('PATH_APPLICATION', PATH_SITE . 'application/');
define('PATH_APPLICATION_EXTRANET', PATH_APPLICATION . 'extranet/');
define('PATH_FORM', PATH_SCRIPT . 'form/');
define('PATH_VALIDATE_INSCRIPTION', PATH_FORM . 'validateInscription.php');
define('PATH_RESET_PASSWORD_FORM', PATH_APPLICATION_EXTRANET . 'resetPasswordForm.php');
define('PATH_FILES', PATH_RESSOURCES . 'files/');
define('PATH_PAIEMENT_FORM', PATH_APPLICATION_EXTRANET . 'paiement/');
define('PATH_PAIEMENT_SCRIPT', PATH_SCRIPT . 'paiement/');
define('PATH_INVOICES', PATH_FILES . 'invoices/');

define('MAIL', 'cookmasterpa.2023@gmail.com');

require_once PATH_SITE . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(PATH_SITE);
$dotenv->load();


/*
 * TODO ADDRESS
 */

define('ADDRESS_SITE', 'http://' . $_SERVER['HTTP_HOST'] . '/Cook_Master/');

define('ADDRESS_RESSOURCES', ADDRESS_SITE . 'ressources/');
define('ADDRESS_CSS', ADDRESS_RESSOURCES . 'css/');
define('ADDRESS_JS', ADDRESS_RESSOURCES . 'js/');
define('ADDRESS_IMG', ADDRESS_RESSOURCES . 'images/');
define('ADDRESS_PRICING_ICON', ADDRESS_IMG . 'pricingIcon/');
define('ADDRESS_IMG_PROFIL', ADDRESS_IMG . 'profilePicture/');
define('ADDRESS_IMG_RECIPES', ADDRESS_IMG . 'recipeImage/');
define('ADDRESS_DEFAULT_PROFIL', ADDRESS_IMG . 'defaultPicture.svg');

define('ADDRESS_SCRIPT', ADDRESS_RESSOURCES . 'script/');
define('ADDRESS_APPLICATION', ADDRESS_SITE . 'application/');
define('ADDRESS_APPLICATION_EXTRANET', ADDRESS_APPLICATION . 'extranet/');
define('ADDRESS_FORM', ADDRESS_SCRIPT . 'form/');
define('ADDRESS_VALIDATE_INSCRIPTION', ADDRESS_FORM . 'validateInscription.php');
define('ADDRESS_RESET_PASSWORD', ADDRESS_SITE . 'resetPassword.php');
define('ADDRESS_PAIEMENT_FORM', ADDRESS_APPLICATION_EXTRANET . 'paiement/');
define('ADDRESS_FILES', ADDRESS_RESSOURCES . 'files/');
define('ADDRESS_INVOICES', ADDRESS_FILES . 'invoices/');


require_once(PATH_SCRIPT . 'connectDB.php');

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 3600)) {

    session_unset();
    session_destroy();

    header('Location: ' . ADDRESS_SITE . '?message=Vous avez été déconnecté pour inactivité&type=warning');
    exit();

}else{

    $_SESSION['LAST_ACTIVITY'] = time();

}