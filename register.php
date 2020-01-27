<?php
/*
	Page: Register
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
$footer = '';
$countrys_html = '';
$file = file_get_contents("resources/json/countrynames.json");
foreach (json_decode($file, true) as $key => $value) {
	$countrys_html .= '<option value="'.$value["country"].'">'.$value["country"].'</option>';
}
if (isset($_POST['username'])) {
	$username = $_POST['username'];
}
if (isset($_POST['agree'])) {
	$agree = $_POST['agree'];
}
if(isset($_POST['submit']))
{
	if (isset($agree))
	{
		if (($_POST['password'] != "") && ($_POST['password2'] != "") && ($_POST['email'] != "") && ($_POST['question_security'] != "") && ($_POST['answer_security'] != "") && ($_POST['mobile_number'] != "") && ($_POST['country'] != ""))
		{
			if(isset($_POST['password']) && $_POST['password'] != "")
			{
				if($_POST['password'] == $_POST['password2'])
				{
					if(strlen($_POST['password']) > 3 && strlen($_POST['password']) < 32)
					{
						if(isset($_POST['username']) && $_POST['username'] != "")
						{
							if(isset($_POST["captcha"])&&$_POST["captcha"]!=""&&$_SESSION["code"]==$_POST["captcha"])
							{
								if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM accounts WHERE Username='$username'")) > "0")
								{
									$footer = '<script>
									(function() {
										swal({
											title: "Warning",
											text: "'.getLang("REGISTER_EXISTS_ACCOUNT")->value.'",
											icon: "warning",
											button: "OK!",
											dangerMode: false,
										});
									})();
									</script>';
								}
								else
								{
									$usenamef = mysqli_real_escape_string($conn, $_POST['username']);
									$passwordf = mysqli_real_escape_string($conn, $_POST['password']);
									$emailf = mysqli_real_escape_string($conn, $_POST['email']);
									$questionsecurityf = mysqli_real_escape_string($conn, $_POST['question_security']);
									$answersecurityf = mysqli_real_escape_string($conn, $_POST['answer_security']);
									$countryf = mysqli_real_escape_string($conn, $_POST['country']);
									$mobilenumberf = mysqli_real_escape_string($conn, $_POST['mobile_number']);
									$sql = "INSERT INTO `accounts` (Username, Password, Email, Question, Answer, Country, MobileNumber) VALUES ('" . $usernamef . "', '". $passwordf . "', '" . $emailf . "', '" . $questionsecurityf . "', '" . $answersecurityf . "', '" . $countryf . "', '" .$mobilenumberf . "')";
									$sql = mysqli_query($conn, $sql);
									if ($sql) {
										$footer = '<script>
										(function() {
											swal({
												title: "Success",
												text: "'.getLang("REGISTER_MSG_SUCESS")->value.'",
												icon: "success",
												button: "OK!",
												dangerMode: false,
											});
										})();
										</script>';
									} else {
										die(mysqli_error($conn));
									}
								}
							}
							else
							{
								$footer = '<script>
								(function() {
									swal({
										title: "Warning",
										text: "'.getLang("REGISTER_MSG_CAPTCH_INVALID")->value.'",
										icon: "warning",
										button: "OK!",
										dangerMode: false,
									});
								})();
								</script>';
							}
						}
						else
						{
							$footer = '<script>
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
					}
					else
					{
						$footer = '<script>
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
				}
				else
				{
					$footer = '<script>
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
			}
			else
			{
				$footer = '<script>
				(function() {
					swal({
						title: "Warning",
						text: "'.getLang("REGISTER_MSG_INVALID_PWD")->value.'",
						icon: "warning",
						button: "OK!",
						dangerMode: false,
					});
				})();
				</script>';
			}
		}
		else
		{
			$footer = '<script>
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
	else
	{
		$footer = '
		<center>
			<div class="alert alert-warning" role="alert">
				'.getLang("REGISTER_MSG_ACCEPTTC")->value.'
			</div>
		</center>';
	}
}
/* */
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
echo $twig->render('register.html', array('items_menu' => $items_menu, 'news' => getNews(), 'lang' => getLangV2(), 'setting' => getSettingsV2(), 'user_data' => $user, 'exist_character' => $exist_character, 'character' => $character, "error_login" => $error_login, "server_status" => get_server_status(), "players_online" => get_online_users(), "registered_accounts" => get_registered_accounts(), "index_ranking" => get_index_ranking(), "countrys_html" => $countrys_html, "register_footer" => $footer));
remove_session_msg();
?>