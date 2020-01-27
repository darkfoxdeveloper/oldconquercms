<?php
/*
	Page: Panel
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
$section = "main";
$msg_alert = null;
if (isset($_SESSION["user"])) {
	$user = $_SESSION["user"];
}
if (isset($_GET['section'])) {
	$section = $_GET['section'];
}
if(isset($_POST['submit_changemail'])) {
	$usernamef = mysqli_real_escape_string($conn, $_POST["username"]);
	$passwordf = mysqli_real_escape_string($conn, $_POST["password"]);
	$emailf = mysqli_real_escape_string($conn, $_POST["mail"]);
	$ac = mysqli_fetch_array(mysqli_query($conn, sprintf("SELECT * FROM accounts WHERE Username='$usernamef' AND Password='$passwordf'")));
	if(!$_POST["username"] && $msg_alert == null) {
		$msg_alert = '
		<script>
		(function() {
			swal({
				title: "Warning",
				text: "'.getLang("REGISTER_MSG_EMPTY_ACCOUNT")->value.'",
				icon: "warning",
				button: "OK!",
				dangerMode: false,
			});
		})();
		</script>';
	}
	if(!$_POST["password"] && $msg_alert == null) {
		$msg_alert = '
		<script>
		(function() {
			swal({
				title: "Warning",
				text: "'.getLang("REGISTER_MSG_INV_LENGTH_PWD")->value.'",
				icon: "warning",
				button: "OK!",
				dangerMode: false,
			});
		})();
		</script>';
	}
	if(!$_POST["mail"] || !$_POST["mail"]) {
		if ($msg_alert == null) {
			$msg_alert = '
			<script>
			(function() {
				swal({
					title: "Warning",
					text: "'.getLang("REGISTER_MSG_EMPTY_FIELDS")->value.'",
					icon: "warning",
					button: "OK!",
					dangerMode: false,
				});
			})();
			</script>';
		}
	}
	if(!preg_match("/^[0-9a-z]{4,12}$/",$_POST["username"]) && $msg_alert == null) {
		$msg_alert = '
		<script>
		(function() {
			swal({
				title: "Warning",
				text: "'.getLang("MSG_INVALID_USERNAME")->value.'",
				icon: "warning",
				button: "OK!",
				dangerMode: false,
			});
		})();
		</script>';
	}
	if($_POST["mail"]!=$_POST["mail2"] && $msg_alert == null) {
		$msg_alert = '
		<script>
		(function() {
			swal({
				title: "Warning",
				text: "'.getLang("MSG_NOT_MATCH_EMAILS")->value.'",
				icon: "warning",
				button: "OK!",
				dangerMode: false,
			});
		})();
		</script>';
	}
	if ($ac)
	{
		if ($msg_alert == null) {
			mysqli_query($conn, "UPDATE accounts SET Email='$emailf' WHERE Username='$usernamef'");
			$msg_alert = '
			<script>
			(function() {
				swal({
					title: "Success",
					text: "'.getLang("CHANGE_EMAIL_MSG_SUCCESS")->value.'",
					icon: "success",
					button: "OK!",
					dangerMode: false,
				});
			})();
			</script>';
		}
	} else if ($msg_alert == null) {
		$msg_alert = '
		<script>
		(function() {
			swal({
				title: "Warning",
				text: "'.getLang("MSG_INVALID_USER_OR_PW")->value.'",
				icon: "warning",
				button: "OK!",
				dangerMode: false,
			});
		})();
		</script>';
	}
}
if(isset($_POST['submit_changepwd'])) {
	$usernamef = mysqli_real_escape_string($conn, $_POST["username"]);
	$passwordf = mysqli_real_escape_string($conn, $_POST["password"]);
	$oldpasswordf = mysqli_real_escape_string($conn, $_POST["oldpassword"]);
	$ac = mysqli_fetch_array(mysqli_query($conn, sprintf("SELECT * FROM accounts WHERE Username='$usernamef' AND Password='$oldpasswordf'")));
	if(!$_POST["username"] && $msg_alert == null) {
		$msg_alert = '
		<script>
		(function() {
			swal({
				title: "Warning",
				text: "'.getLang("REGISTER_MSG_EMPTY_ACCOUNT")->value.'",
				icon: "warning",
				button: "OK!",
				dangerMode: false,
			});
		})();
		</script>';
	}
	if(!$_POST["oldpassword"] || !$_POST["password"] || !$_POST["password2"]) {
		if ($msg_alert == null) {
			$msg_alert = '
			<script>
			(function() {
				swal({
					title: "Warning",
					text: "'.getLang("REGISTER_MSG_EMPTY_FIELDS")->value.'",
					icon: "warning",
					button: "OK!",
					dangerMode: false,
				});
			})();
			</script>';
		}
	}
	if(!preg_match("/^[0-9a-z]{4,14}$/",$_POST["username"]) && $msg_alert == null) {
		$msg_alert = '
		<script>
		(function() {
			swal({
				title: "Warning",
				text: "'.getLang("MSG_INVALID_USERNAME")->value.'",
				icon: "warning",
				button: "OK!",
				dangerMode: false,
			});
		})();
		</script>';
		//print("<table border='1' bordercolor='#FFBF00' align='center'><tr class='false'><td align='center'><center>Username Field Only accept letters \"a\" to \"z\" and numbers with 4 to 12 of length</td></tr></table>");
	}
	if(!preg_match("/^[0-9a-z]{4,14}$/",$_POST["password"]) && $msg_alert == null) {
		$msg_alert = '
		<script>
		(function() {
			swal({
				title: "Warning",
				text: "'.getLang("REGISTER_MSG_INV_LENGTH_PWD")->value.'",
				icon: "warning",
				button: "OK!",
				dangerMode: false,
			});
		})();
		</script>';
	}
	if($_POST["password"]!=$_POST["password2"] && $msg_alert == null) {
		$msg_alert = '
		<script>
		(function() {
			swal({
				title: "Warning",
				text: "'.getLang("REGISTER_MSG_NOT_MATCH_PWD")->value.'",
				icon: "warning",
				button: "OK!",
				dangerMode: false,
			});
		})();
		</script>';
	}
	if ($ac) {
		if ($msg_alert == null) {
			$q = mysqli_query($conn, "UPDATE accounts SET Password='$passwordf' WHERE Username='$usernamef'");
			if ($q) {
				$msg_alert = '
				<script>
				(function() {
					swal({
						title: "Success",
						text: "'.getLang("CHANGE_PWD_MSG_SUCCESS")->value.'",
						icon: "success",
						button: "OK!",
						dangerMode: false,
					});
				})();
				</script>';
			}
		}
	} else if ($msg_alert == null) {
		$msg_alert = '
		<script>
		(function() {
			swal({
				title: "Warning",
				text: "'.getLang("MSG_INVALID_USER_OR_PW")->value.'",
				icon: "warning",
				button: "OK!",
				dangerMode: false,
			});
		})();
		</script>';
	}
}
if(isset($_POST['submit_recoverstoragepwd'])) {
	$usernamef = mysqli_real_escape_string($conn, $_POST["username"]);
	$passwordf = mysqli_real_escape_string($conn, $_POST["password"]);
	$ac = mysqli_fetch_array(mysqli_query($conn, sprintf("SELECT * FROM accounts WHERE Username='$usernamef' AND Password='$passwordf'")));
	if(!$_POST["username"] && $msg_alert == null) {
		$msg_alert = '
		<script>
		(function() {
			swal({
				title: "Warning",
				text: "'.getLang("REGISTER_MSG_EMPTY_ACCOUNT")->value.'",
				icon: "warning",
				button: "OK!",
				dangerMode: false,
			});
		})();
		</script>';
	}
	if(!$_POST["password"] && $msg_alert == null) {
		$msg_alert = '
		<script>
		(function() {
			swal({
				title: "Warning",
				text: "'.getLang("REGISTER_MSG_INV_LENGTH_PWD")->value.'",
				icon: "warning",
				button: "OK!",
				dangerMode: false,
			});
		})();
		</script>';
	}
	if ($ac)
	{
		if ($msg_alert == null) {
			$entityid = mysqli_real_escape_string($ac["EntityID"]);
			$buscar_inf = mysqli_query($conn, "SELECT * FROM entities WHERE UID='$entityid'");
			$mostrar_inf = mysqli_fetch_object($buscar_inf);
			if ($ac)
			{
				$storage_pwd_info = '';
				$storage_pwd = $mostrar_inf->WarehousePW;
				if ($storage_pwd == 0) {
					$storage_pwd_info = 'Not have password';
				} else {
					$storage_pwd_info = $storage_pwd;
				}
				$msg_alert = '
				<script>
				(function() {
					swal({
						title: "Success",
						text: "'.getLang("REGISTER_PASSWORD_LABEL")->value.' is: \''.$storage_pwd_info.'\'",
						icon: "success",
						button: "OK!",
						dangerMode: false,
					});
				})();
				</script>';
			}
		}
	} else {
		if ($msg_alert == null) {
			$msg_alert = '
			<script>
			(function() {
				swal({
					title: "Warning",
					text: "'.getLang("MSG_INVALID_USER_OR_PW")->value.'",
					icon: "warning",
					button: "OK!",
					dangerMode: false,
				});
			})();
			</script>';
		}
	}
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
echo $twig->render('panel.html', array('items_menu' => $items_menu, 'news' => getNews(), 'lang' => getLangV2(), 'setting' => getSettingsV2(), 'user_data' => $user, 'exist_character' => $exist_character, 'character' => $character, "error_login" => $error_login, "server_status" => get_server_status(), "players_online" => get_online_users(), "registered_accounts" => get_registered_accounts(), "index_ranking" => get_index_ranking(), "section_panel" => $section, "msg_alert" => $msg_alert));
remove_session_msg();
?>