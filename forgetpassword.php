<?php
/*
	Page: ForgetPassword
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
/* START FORGET PASSWORD PHP */
$message = '';
if(isset($_POST['submit_forgetpwd']))
{
	$login = $_POST['username'];
	$email = $_POST['email'];
	if(($login == "") or ($email == ""))
	{
		$message = "<script>alert('Please, fill all the fields!');</script>";
	}
	else {
		$loginf = mysqli_real_escape_string($conn, $login);
		$emailf = mysqli_real_escape_string($conn, $email);
		$buscar_inf = mysqli_query($conn, "SELECT * FROM accounts WHERE Username='$loginf' AND Email='$emailf'");
		$mostrar_inf = mysqli_fetch_object($buscar_inf);
		if (mysqli_num_rows($buscar_inf) == 0)
		{
			$message = "<script>alert('Incorrect Information!');</script>";
		}
		else
		{
			$message = "<center>Hello $mostrar_inf->Username, You password is <font color='darkred'>$mostrar_inf->Password</font></center>";
		}
	}
}
/* END FORGET PASSWORD PHP */
//Get menu items
$items_menu = getItemMenu("visible=1 AND parent_id=0", true);
//Load twig
$loader = new Twig_Loader_Filesystem('templates/'.TEMPLATE);
$twig = new Twig_Environment($loader, array(
    'cache' => CACHE,
	'debug' => DEBUG
));
$twig->addExtension(new Twig_Extension_Debug());
echo $twig->render('forgetpassword.html', array('items_menu' => $items_menu, 'news' => getNews(), 'lang' => getLangV2(), 'setting' => getSettingsV2(), 'user_data' => $user, 'exist_character' => $exist_character, 'character' => $character, "error_login" => $error_login, "server_status" => get_server_status(), "players_online" => get_online_users(), "registered_accounts" => get_registered_accounts(), "index_ranking" => get_index_ranking(), "message" => $message));
remove_session_msg();
?>