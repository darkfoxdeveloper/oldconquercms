<?php
/*
	Settings: You can configure all page here
*/

/* AUTO CREATE SESSION IF NO EXISTS */
if (!isset($_SESSION)) {
	session_start();
	$_SESSION["INCLUDE_ROOT_PATH"] = __DIR__."/../";
}

/* OTHER FUNCTIONS FOR THE CORE */
require_once($_SESSION["INCLUDE_ROOT_PATH"]."inc/functions.php");

/* APP CONFIGURATION */
define("DEBUG", false);//Options Availables: true or false (Enabled/Disabled)
define("CACHE", false);//Options Availables: "cache" or false (Enabled/Disabled)
define("APP_PATH_SUFIX", "occms");
if (empty(APP_PATH_SUFIX)) {
	define("APP_PATH", "http://".$_SERVER['SERVER_NAME']."/");
} else {
	define("APP_PATH", "http://".$_SERVER['SERVER_NAME']."/".APP_PATH_SUFIX."/");
}
define("TINYMCE_APIKEY", "xm9cnsqp7sw8dp8mvwn9nd5ygtkpy464q5herpfvvgwkkssa");
define("TEMPLATE", "classic");
define("VERSION", "2.5");//Not change please
if (!DEBUG) {  
    error_reporting(E_ERROR | E_PARSE);
}

/* DATABASE CONFIGURATION */
$existConfig = false;
if (file_exists($_SESSION["INCLUDE_ROOT_PATH"]."inc/config.php")) {
    $database_config = require_once($_SESSION["INCLUDE_ROOT_PATH"]."inc/config.php");
	$existConfig = true;
}

/* CONNECTION TO MYSQL */
require_once($_SESSION["INCLUDE_ROOT_PATH"]."inc/connect.php");


/* CONQUER SERVER STATUS CONFIGURATION - DEFAULT FROM DATABASE */
define('SERVER_NAME', getSettings("SERVER_NAME")->value);
define('SERVER_PORT', getSettings("SERVER_PORT")->value);
define('SERVER_ADDRESS', getSettings("SERVER_ADDRESS")->value);

/* PAGE CONFIGURATION - DEFAULT FROM DATABASE */
$lang = getSettings("LANG")->value;
if (empty($lang)) {
	define("LANG", "en");
} else {
	define("LANG", $lang);
}
define("PAGE_TITLE", getSettings("PAGE_TITLE")->value);
/*define("MENU_TEXT_1", getLang("MENU_TEXT_1")->value);
define("MENU_LINK_1", getSettings("MENU_LINK_1")->value);
define("MENU_TEXT_2", getLang("MENU_TEXT_2")->value);
define("MENU_LINK_2", getSettings("MENU_LINK_2")->value);
define("MENU_TEXT_3", getLang("MENU_TEXT_3")->value);
define("MENU_LINK_3", getSettings("MENU_LINK_3")->value);
define("MENU_TEXT_4", getLang("MENU_TEXT_4")->value);
define("MENU_LINK_4", getSettings("MENU_LINK_4")->value);
define("MENU_TEXT_5", getLang("MENU_TEXT_5")->value);
define("MENU_LINK_5", getSettings("MENU_LINK_5")->value);
define("MENU_TEXT_6", getLang("MENU_TEXT_6")->value);
define("MENU_LINK_6", getSettings("MENU_LINK_6")->value);*/
define("PANEL_LINK_LOGOUT", getSettings("PANEL_LINK_LOGOUT")->value);
define("QUICK_LINK0_TITLE", getLang("QUICK_LINK0_TITLE")->value);
define("QUICK_LINK0_HREF", getSettings("QUICK_LINK0_HREF")->value);
define("QUICK_LINK1_TITLE", getLang("QUICK_LINK1_TITLE")->value);
define("QUICK_LINK1_HREF", getSettings("QUICK_LINK1_HREF")->value);
define("FOOTER_LINK0_TITLE", getLang("FOOTER_LINK0_TITLE")->value);
define("FOOTER_LINK0_HREF", getSettings("FOOTER_LINK0_HREF")->value);
define("FOOTER_LINK1_TITLE", getLang("FOOTER_LINK1_TITLE")->value);
define("FOOTER_LINK1_HREF", getSettings("FOOTER_LINK1_HREF")->value);
define("FOOTER_LINK2_TITLE", getLang("FOOTER_LINK2_TITLE")->value);
define("FOOTER_LINK2_HREF", getSettings("FOOTER_LINK2_HREF")->value);
define("FOOTER_LINK3_TITLE", getLang("FOOTER_LINK3_TITLE")->value);
define("FOOTER_LINK3_HREF", getSettings("FOOTER_LINK3_HREF")->value);
define("FOOTER_COPYRIGHT_HTML", getSettings("FOOTER_COPYRIGHT_HTML")->value);
define("SECTION0_TITLE", getLang("SECTION0_TITLE")->value);
define("SECTION1_TITLE", getLang("SECTION1_TITLE")->value);
define("SECTION2_TITLE", getLang("SECTION2_TITLE")->value);
define("SECTION3_TITLE", getLang("SECTION3_TITLE")->value);
define("SECTION4_TITLE", getLang("SECTION4_TITLE")->value);
define("DOWNLOAD_LINK_CLIENT", getSettings("DOWNLOAD_LINK_CLIENT")->value);
define("DOWNLOAD_LINK_PATCH", getSettings("DOWNLOAD_LINK_PATCH")->value);

/* Extra variables for your templates - You can add here more */
define("SOCIAL_URL_FACEBOOK", "https://www.facebook.com/openconquer/");
define("SOCIAL_URL_TWITTER", "https://twitter.com/DaRkFoxCoder/");
?>