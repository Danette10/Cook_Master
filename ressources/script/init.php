<?php

/*
 * TODO: Link Local
 */

define('PATH_SITE', 'http://'.$_SERVER['HTTP_HOST'] . '/Cook_Master/');
define('PATH_RESSOURCES', PATH_SITE . 'ressources/');
define('PATH_CSS', PATH_RESSOURCES . 'css/');
define('PATH_JS', PATH_RESSOURCES . 'js/');
define('PATH_IMG', PATH_RESSOURCES . 'images/');
define('PATH_SCRIPT', PATH_RESSOURCES . 'script/');

define('PATH_APPLICATION', PATH_SITE . 'application/');
define('PATH_APPLICATION_EXTRANET', PATH_APPLICATION . 'extranet/');
define('PATH_VALIDATE_INSCRIPTION', PATH_SCRIPT . 'form/validateInscription.php');

define('PATH_FORM', PATH_SCRIPT . 'form/');

define('MAIL', 'cookmasterpa.2023@gmail.com');