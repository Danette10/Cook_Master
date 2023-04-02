<?php

/*
 * TODO: Link Local
 */

/*
 *TODO: PATH
 */

define('PATH_SITE', $_SERVER['DOCUMENT_ROOT'] . '/Cook_Master/');
define('PATH_RESSOURCES', PATH_SITE . 'ressources/');
define('PATH_CSS', PATH_RESSOURCES . 'css/');
define('PATH_JS', PATH_RESSOURCES . 'js/');
define('PATH_IMG', PATH_RESSOURCES . 'images/');
define('PATH_PRICING_ICON', PATH_IMG . 'pricingIcon/');
define('PATH_SCRIPT', PATH_RESSOURCES . 'script/');

define('PATH_APPLICATION', PATH_SITE . 'application/');
define('PATH_APPLICATION_EXTRANET', PATH_APPLICATION . 'extranet/');
define('PATH_FORM', PATH_SCRIPT . 'form/');
define('PATH_VALIDATE_INSCRIPTION', PATH_FORM . 'validateInscription.php');

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

define('ADDRESS_SCRIPT', ADDRESS_RESSOURCES . 'script/');
define('ADDRESS_APPLICATION', ADDRESS_SITE . 'application/');
define('ADDRESS_APPLICATION_EXTRANET', ADDRESS_APPLICATION . 'extranet/');
define('ADDRESS_FORM', ADDRESS_SCRIPT . 'form/');
define('ADDRESS_VALIDATE_INSCRIPTION', ADDRESS_FORM . 'validateInscription.php');
define('ADDRESS_RESET_PASSWORD', ADDRESS_SITE . 'resetPassword');