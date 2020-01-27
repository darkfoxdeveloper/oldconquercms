<?php
/*
	Page: OpenConquerCMS Panel
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
$lang = getLangV2();
if (isset($_SESSION["user"])) {
	$user = $_SESSION["user"];
}
/* START FUNCTIONS FOR PAGE PHP */
$section = null;
$message = null;
$occms_user = null;
if (isset($_GET['section'])) {
	$section = $_GET['section'];
}
if (isset($_GET['logout'])) {
	session_destroy();
}
if (isset($_POST["occms_login"])) {
	//Check login in database and create session for access
	if (loginOCCMS(mysqli_real_escape_string($conn, $_POST["username"]),mysqli_real_escape_string($conn, $_POST["password"]))) {
		$_SESSION["occms_user"] = array(
			"username" => mysqli_real_escape_string($conn, $_POST["username"]),
			"password" => mysqli_real_escape_string($conn, $_POST["password"])
		);
	} else {
		$message = '<p><font color="darkred">Invalid Username or Password</font></p>';
	}
}
if (isset($_POST["occms_edit_new"])) {
	if(editNew(mysqli_real_escape_string($conn, $_POST["occms_edit_new"]),mysqli_real_escape_string($conn, $_POST["name"]),mysqli_real_escape_string($conn, $_POST["description"]))) {
		$message = '<p><font color="darkgreen">New updated successfully!</font></p>';
	}
}
if (isset($_POST["occms_create_new"])) {
	if(createNew(mysqli_real_escape_string($conn, $_POST["name"]),mysqli_real_escape_string($conn, $_POST["description"]),mysqli_real_escape_string($conn, $_POST["user"]))) {
		$message = '<p><font color="darkgreen">New created successfully!</font></p>';
	}
}
if (isset($_POST["occms_edit_setting"])) {
	if(editSetting(mysqli_real_escape_string($conn, $_POST["occms_edit_setting"]),mysqli_real_escape_string($conn, $_POST["value"]))) {
		$message = '<p><font color="darkgreen">Setting updated successfully!</font></p>';
	}
}
if (isset($_POST["occms_create_setting"])) {
	if(createSetting(mysqli_real_escape_string($conn, $_POST["key"]),mysqli_real_escape_string($conn, $_POST["value"]))) {
		$message = '<p><font color="darkgreen">Setting created successfully!</font></p>';
	}
}
if (isset($_POST["occms_edit_lang"])) {
	if(editLang(mysqli_real_escape_string($conn, $_POST["occms_edit_lang"]),mysqli_real_escape_string($conn, $_POST["value"]),mysqli_real_escape_string($conn, $_POST["lang"]))) {
		$message = '<p><font color="darkgreen">Setting updated successfully!</font></p>';
	}
}
if (isset($_POST["occms_create_lang"])) {
	if(createLang(mysqli_real_escape_string($conn, $_POST["key"]),mysqli_real_escape_string($conn, $_POST["value"]),mysqli_real_escape_string($conn, $_POST["lang"]))) {
		$message = '<p><font color="darkgreen">Lang created successfully!</font></p>';
	}
}
if (isset($_POST["occms_edit_item_menu"])) {
	if (isset($lang[$_POST["name"]])) {
		if(editItemMenu(mysqli_real_escape_string($conn, $_POST["occms_edit_item_menu"]),mysqli_real_escape_string($conn, $_POST["name"]),mysqli_real_escape_string($conn, $_POST["link"]),mysqli_real_escape_string($conn, $_POST["sort-order"]),mysqli_real_escape_string($conn, $_POST["parent-item"]),mysqli_real_escape_string($conn, $_POST["visible"]))) {
			$message = '<p><font color="darkgreen">Item Menu updated successfully!</font></p>';
		}
	} else {
		$message = '<p><font color="darkred">The name of Item Menu not exist in Lang System. Create first!</font></p>';
	}
}
if (isset($_POST["occms_create_item_menu"])) {
	if (isset($lang[$_POST["name"]])) {
		if(createItemMenu(mysqli_real_escape_string($conn, $_POST["name"]),mysqli_real_escape_string($conn, $_POST["link"]),mysqli_real_escape_string($conn, $_POST["sort-order"]),mysqli_real_escape_string($conn, $_POST["parent-item"]),mysqli_real_escape_string($conn, $_POST["visible"]))) {
			$message = '<p><font color="darkgreen">Item Menu created successfully!</font></p>';
		}
	} else {
		$message = '<p><font color="darkred">The name of Item Menu not exist in Lang System. Create first!</font></p>';
	}
}
if (isset($_SESSION["occms_user"])) {
	$occms_user = $_SESSION["occms_user"];
}
$OCCMSPanel = getOCCMSPanel($section);
/* END FUNCTIONS FOR PAGE PHP */
//Get menu items
$items_menu = getItemMenu("visible=1 AND parent_id=0", true);
//Load twig
$loader = new Twig_Loader_Filesystem('templates/'.TEMPLATE);
$twig = new Twig_Environment($loader, array(
    'cache' => CACHE,
	'debug' => DEBUG
));
$twig->addExtension(new Twig_Extension_Debug());
echo $twig->render('openconquercms_panel.html', array('items_menu' => $items_menu, 'news' => getNews(), 'lang' => $lang, 'setting' => getSettingsV2(), 'user_data' => $user, 'exist_character' => $exist_character, 'character' => $character, "error_login" => $error_login, "server_status" => get_server_status(), "players_online" => get_online_users(), "registered_accounts" => get_registered_accounts(), "index_ranking" => get_index_ranking(), "OCCMSPanel" => $OCCMSPanel, "message" => $message, "occms_user" => $occms_user));
remove_session_msg();
?>
<!-- Support for LangSystem -->
<script>
$('[data-toggle="popover"]').popover();
$("form[name='create-item-menu-cms-panel'] #name, form[name='edit-item-menu-cms-panel'] #name").on("keyup click", function(e) {
	var input = $(this);
	var inputVal = $(this).val();
	var request = $.ajax({
		url: "requests/get_lang.php",
		method: "POST",
		data: { key : inputVal },
		dataType: "json"
	});
	request.done(function(json) {
		$(input).attr("data-content", json.value);
		if(typeof json.key != "undefined") {
			$(input).attr("title", "This name is variable. The Value is:");
			$(input).attr("data-toggle", "popover");
			$(input).popover('show');
		} else {
			$(input).popover('hide');
		}
	});
});
$("form[name='create-item-menu-cms-panel'] #parent-item, form[name='edit-item-menu-cms-panel'] #parent-item").on("change", function(e) {
	var input = $(this);
	var inputVal = $(this).find(":selected").text();
	var request = $.ajax({
		url: "requests/get_lang.php",
		method: "POST",
		data: { key : inputVal },
		dataType: "json"
	});
	request.done(function(json) {
		$(input).attr("data-content", json.value);
		if(typeof json.key != "undefined") {
			$(input).attr("title", "This name is variable. The Value is:");
			$(input).attr("data-toggle", "popover");
			$(input).popover('show');
		} else {
			$(input).popover('hide');
		}
	});
});
</script>