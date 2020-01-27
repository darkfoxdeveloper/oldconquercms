<?php
/*
	Page: Index
	Owner: darkfoxdeveloper@gmail.com
*/
require_once("inc/settings.php");
require_once("inc/autoload_twig.php");
$exist_character = false;
$character = null;
if(isset($_SESSION["user"])){
	$exist_character = true;
	$character = getEntityFromUsername($_SESSION["user"]["Username"]);
	$character->ClassName = prof($character->Class);
	$character->FaceImageURL = "no_avatar.png";
	if ($character->Face > 0) {
		$character->FaceImageURL = $character->Face . ".jpg";
	}
}
$user = null;
if (isset($_SESSION["user"])) {
	$user = $_SESSION["user"];
}
//Get menu items
$items_menu = getItemMenu("visible=1 AND parent_id=0", true);
//Load twig
$loader = new Twig_Loader_Filesystem('templates/'.TEMPLATE);
$twig = new Twig_Environment($loader, array(
    'cache' => CACHE,
	'debug' => DEBUG
));
$twig->addExtension(new Twig_Extension_Debug());
echo $twig->render('index.html', array('items_menu' => $items_menu, 'news' => getNews(), 'lang' => getLangV2(), 'setting' => getSettingsV2(), 'user_data' => $user, 'exist_character' => $exist_character, 'character' => $character, "error_login" => $error_login, "server_status" => get_server_status(), "players_online" => get_online_users(), "registered_accounts" => get_registered_accounts(), "index_ranking" => get_index_ranking()));
remove_session_msg();
?>