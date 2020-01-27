<?php
$existTableCMS = false;
/* Connect */
if ($existConfig) {
	$error_connect = null;
	$error_login =  0;
	$conn = mysqli_connect($database_config["HOST"], $database_config["USER"], $database_config["PASSWORD"], $database_config["NAME"]);
	if (mysqli_connect_errno())
	{
		$error_connect = "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	if(isset($_POST['login']))
	{
		$user=$_POST['user'];
		$pass=$_POST['pass'];
		$result1 = "SELECT * FROM `accounts` WHERE Username='$user' and Password='$pass'";
		$query = mysqli_query($conn, $result1);
		$sql2 = mysqli_fetch_assoc($query);
		$count = mysqli_num_rows($query);
		if ($count==1){
			$_SESSION["user"]=array("Username"=>$sql2['Username'], "EntityID" => $sql2['EntityID'], "ShowSuccessLogin" => true);
		}
		else{
			$error_login = 1;
		}
	}
	mysqli_query($conn, "SET NAMES ".$database_config["CHARSET"]);
	mysqli_query($conn, "SET CHARACTER_SET ".$database_config["CHARSET"]);
	/* CHECK IF EXIST EXTRA TABLES FOR THIS PAGE :D */
	if ($resCheckTableCMS = mysqli_query($conn, "SHOW TABLES LIKE 'openconquercms_news'")) {
	    if(mysqli_num_rows($resCheckTableCMS) == 1) {
	        $existTableCMS = true;
	    }
	}
}
/*
	CHECK CONFIG AND TABLES
*/
if (!$existConfig) {
    if (isset($_POST['create-config-file'])) {
        $myfile = fopen("inc/config.php", "w") or die("Error creating a config file!");
        $txt = "
        <?php
        return [
            'HOST' => '".$_POST['HOST']."',
            'NAME' => '".$_POST['NAME']."',
            'USER' => '".$_POST['USER']."',
            'PASSWORD' => '".$_POST['PASSWORD']."',
            'CHARSET' => 'utf8mb4'
        ];
        ?>
        ";
        fwrite($myfile, $txt);
        fclose($myfile);
        header("Location: index.php");
    }
    $configureHTML = generateSuperBox('OpenConquerCMS - Database Configuration', '
        <div><h3 class="text-center text-primary">You need input database mysql configuration</h3></div>
        <div class="mx-auto col-6">
            <form method="POST">
                <div class="form-group">
                    <input input="text" class="form-control" name="HOST" placeholder="Host (Default: localhost)" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="NAME" placeholder="Name of database" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="USER" placeholder="User of database (Default: root)" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="PASSWORD" placeholder="Password of database">
                </div>
                <div class="form-group text-right">
                    <input type="hidden" name="create-config-file" value="true">
                    <button type="submit" class="btn btn-primary">Create Config File and continue</button>
                </div>
            </form>
        </div>
    ', '', true);
}
if (isset($error_connect)) {
    $configureHTML = generateSuperBox('OpenConquerCMS - Error in Configuration', '
        <div><h3 class="text-center text-danger">Any error in configuration. Cannot connect to Database</h3></div>
        <div>
            <p class="text-info"> \'Open the inc/settings.php\' file and change this parameters:</p>
            <ul>
                <li>DB_NAME</li>
                <li>DB_USER</li>
                <li>DB_PASSWORD</li>
                <li>DB_HOST</li>
            </ul>
        </div>
    ', '', true);
}
if (!$existTableCMS && $existConfig) {
	$randPwd = substr(md5(microtime()),rand(0,26),6);
	$sql_news = 'CREATE TABLE `openconquercms_news` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) DEFAULT NULL,
    `description` longtext,
    `user` int(11) DEFAULT NULL,
    `publish_date` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET='.$database_config["CHARSET"].';';
	$sql_users = 'CREATE TABLE `openconquercms_users` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`username` varchar(100) DEFAULT NULL,
		`password` varchar(65) DEFAULT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET='.$database_config["CHARSET"].';';
	$sql_settings = 'CREATE TABLE `openconquercms_settings` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`key` varchar(100) DEFAULT NULL,
		`value` longtext DEFAULT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET='.$database_config["CHARSET"].';';
	$sql_lang = 'CREATE TABLE `openconquercms_lang` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`key` varchar(100) DEFAULT NULL,
		`value` longtext DEFAULT NULL,
		`lang` varchar(100) DEFAULT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET='.$database_config["CHARSET"].';';
	$sql_buy = 'CREATE TABLE `openconquercms_buy` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`quantity` decimal(10,2) DEFAULT 0,
		`price` decimal(10,2) DEFAULT 0,
		`type` varchar(50) DEFAULT "cps",
		`description` varchar(150) DEFAULT NULL,
		`image` varchar(255) DEFAULT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET='.$database_config["CHARSET"].';';
	$sql_user_default = 'INSERT INTO openconquercms_users(username, password) VALUES("admin", "'.md5($randPwd).'")';
	$sql_new_default = 'INSERT INTO openconquercms_news(name, description, user) VALUES ("Version 2.5", "
	<h3>OpenConquerCMS 2.5 Has been released. This is a stable release!</h3>
	<p>Added: Menu Manager (Support Lang System)</p>
	<p>Removed: All settings of URL for links of menu</p>
	", "1")';
	$sql_settings_default = 'INSERT INTO `openconquercms_settings` VALUES 
	("1", "LANG", "en"), ("2", "SERVER_NAME", "OpenConquerCMS"),("3", "SERVER_PORT", "5816"),("4", "SERVER_ADDRESS", "127.0.0.1"),
	("", "PAGE_TITLE", "OpenConquer - El mejor servidor de Conquista 2.0"),("", "PANEL_LINK_LOGOUT", "logout.php"),("", "QUICK_LINK0_HREF", "downloads.php"),
	("", "QUICK_LINK1_HREF", "http://www.openconquer.online"),("", "FOOTER_LINK0_HREF", "#rules"),("", "FOOTER_LINK1_HREF", "#support"),("", "FOOTER_LINK2_HREF", "#security"),("", "FOOTER_LINK3_HREF", "http://www.openconquer.online"),
	("", "FOOTER_COPYRIGHT_HTML", "<div>Copyright by <a href=\"http://www.openconquer.online\">OpenConquerCMS</a>.</div><div>Base Design by <a href=\"https://www.facebook.com/JohanSwaqq\">Johan Valdez</a>. Re-design by <a href=\"https://www.facebook.com/darkhc201\">DaRkFox</a>. 2013-2017.</div><div>*This server is not affliated with TQ Digital/NetDragon.</div>"),
	("", "DOWNLOAD_LINK_CLIENT", "ftp://92.52.125.222/sp_zf/Conquista_v8623.exe"),("", "DOWNLOAD_LINK_PATCH", "https://mega.co.nz/#!IJR3UZKC!f0kJtaN8QizSgAbkQtEaCo5GUdXl2TOUheimuZWKZ1U")
	;';
	$sql_settings_key = 'ALTER TABLE `openconquercms_settings` ADD UNIQUE KEY `key` (`key`)';
	$sql_lang_default = 'INSERT INTO `openconquercms_lang` VALUES
	("1", "MENU_TEXT_1", "Home", "en"), ("2", "MENU_TEXT_2", "Register", "en"),("3", "MENU_TEXT_3", "Downloads", "en"),
	("4", "MENU_TEXT_4", "Buy", "en"),("5", "MENU_TEXT_5", "Ranking", "en"),("6", "MENU_TEXT_6", "OCCMS Panel", "en"),("7", "QUICK_LINK0_TITLE", "DOWNLOADS", "en"),
	("8", "QUICK_LINK1_TITLE", "COMMUNITY", "en"),("9", "FOOTER_LINK0_TITLE", "Rules", "en"),("10", "FOOTER_LINK1_TITLE", "Support", "en"),("11", "FOOTER_LINK2_TITLE", "Security", "en"),
	("12", "FOOTER_LINK3_TITLE", "OpenConquer", "en"),("13", "SECTION0_TITLE", "Register", "en"),("14", "SECTION1_TITLE", "Downloads", "en"),
	("15", "SECTION2_TITLE", "Buy", "en"),("16", "SECTION3_TITLE", "Ranking", "en"),("17", "SECTION4_TITLE", "User Panel", "en"),("18", "REGISTER_ACCOUNT_LABEL", "Account ID:", "en"),
	("19", "REGISTER_PASSWORD_LABEL", "Password:", "en"),("20", "REGISTER_PASSWORD2_LABEL", "Repeat Password:", "en"),("21", "REGISTER_EMAIL_LABEL", "Email:", "en"),
	("22", "REGISTER_SEC_QUESTION_LABEL", "Security Question:", "en"),("23", "REGISTER_SEC_ANSWER_LABEL", "Security Answer:", "en"),("24", "REGISTER_SEC_NUMBER_LABEL", "Security Nº:", "en"),
	("25", "REGISTER_COUNTRY_LABEL", "Country:", "en"),("26", "REGISTER_SUBMIT_LABEL", "Register", "en"),("27", "REGISTER_ACCEPTTC_LABEL", "Accept T&C", "en"),
	("28", "REGISTER_MSG_ACCEPTTC", "Need accept the T&C for register!", "en"),("29", "REGISTER_MSG_EMPTY_FIELDS", "Please, fill all fields!", "en"),
	("30", "REGISTER_MSG_INVALID_PWD", "Please, write any valid password.", "en"),("31", "REGISTER_MSG_NOT_MATCH_PWD", "The password don\"t match.", "en"),
	("32", "REGISTER_MSG_INV_LENGTH_PWD", "Your password must need have between 3 and 32 characters.", "en"),("33", "REGISTER_MSG_EMPTY_ACCOUNT", "Please write Account ID", "en"),
	("34", "REGISTER_MSG_EXISTS_ACCOUNT", "Account already exist.", "en"),("35", "REGISTER_MSG_SUCESS", "Account registered succesfully.", "en"),
	("36", "REGISTER_MSG_CAPTCH_INVALID", "Captcha incorrect!", "en"),("37", "DOWNLOADS_CLIENT_TEXT", "Download the client", "en"),
	("38", "DOWNLOADS_PATCH_TEXT", "Download the patch for the client", "en"),("39", "LOGIN_ACCOUNT_LABEL", "Account ID", "en"), ("40", "LOGIN_PASSWORD_LABEL", "Password", "en"),
	("41", "LOGIN_FORGETPASSWORD_LABEL", "Forget Password?", "en"),("42", "GO_USERPANEL_LABEL", "UserPanel", "en"),("43", "GO_LOGOUT_LABEL", "Logout", "en"),
	("44", "STATUS_LABEL", "Status:", "en"),("45", "SERVER_STATUS_LABEL", "SERVER STATUS", "en"),("46", "TOP_5_INDEX_RANKING_LABEL", "TOP 5 PLAYERS", "en"),
	("47", "INDEX_FULL_RANKING_TEXT", "Show Full Ranking", "en"),';
	/* Spanish Lang Support */
	$sql_lang_default .= '
	("48", "MENU_TEXT_1", "Inicio", "es"), ("49", "MENU_TEXT_2", "Registro", "es"),("50", "MENU_TEXT_3", "Descargas", "es"),
	("51", "MENU_TEXT_4", "Comprar", "es"),("52", "MENU_TEXT_5", "Ranking", "es"),("53", "MENU_TEXT_6", "OCCMS Panel", "es"),("54", "QUICK_LINK0_TITLE", "DESCARGAS", "es"),
	("55", "QUICK_LINK1_TITLE", "COMUNIDAD", "es"),("56", "FOOTER_LINK0_TITLE", "Reglas", "es"),("57", "FOOTER_LINK1_TITLE", "Soporte", "es"),("58", "FOOTER_LINK2_TITLE", "Seguridad", "es"),
	("59", "FOOTER_LINK3_TITLE", "OpenConquer", "es"),("60", "SECTION0_TITLE", "Registro", "es"),("61", "SECTION1_TITLE", "Descargas", "es"),
	("62", "SECTION2_TITLE", "Comprar", "es"),("63", "SECTION3_TITLE", "Ranking", "es"),("64", "SECTION4_TITLE", "Panel de usuario", "es"),("65", "REGISTER_ACCOUNT_LABEL", "Cuenta ID:", "es"),
	("66", "REGISTER_PASSWORD_LABEL", "Contraseña:", "es"),("67", "REGISTER_PASSWORD2_LABEL", "Repite Contraseña:", "es"),("68", "REGISTER_EMAIL_LABEL", "Correo:", "es"),
	("69", "REGISTER_SEC_QUESTION_LABEL", "Pregunta de seguridad:", "es"),("70", "REGISTER_SEC_ANSWER_LABEL", "Respuesta de seguridad:", "es"),("71", "REGISTER_SEC_NUMBER_LABEL", "Nº Seguridad:", "es"),
	("72", "REGISTER_COUNTRY_LABEL", "Pais:", "es"),("73", "REGISTER_SUBMIT_LABEL", "Registro", "es"),("74", "REGISTER_ACCEPTTC_LABEL", "Aceptar T&C", "es"),
	("75", "REGISTER_MSG_ACCEPTTC", "Necesitas aceptar los T&C para registrarte!", "es"),("76", "REGISTER_MSG_EMPTY_FIELDS", "Por favor rellena los campos vacios!", "es"),
	("77", "REGISTER_MSG_INVALID_PWD", "Por favor introduzca una contraseña valida.", "es"),("78", "REGISTER_MSG_NOT_MATCH_PWD", "Las contraseñas no coincide.", "es"),
	("79", "REGISTER_MSG_INV_LENGTH_PWD", "La contraseña tiene que tener de 3 a 32 caracteres.", "es"),("80", "REGISTER_MSG_EMPTY_ACCOUNT", "Por favor introduzca la Cuenta ID", "es"),
	("81", "REGISTER_MSG_EXISTS_ACCOUNT", "Ya exite la Cuenta.", "es"),("82", "REGISTER_MSG_SUCESS", "Cuenta registrada con exito.", "es"),
	("83", "REGISTER_MSG_CAPTCH_INVALID", "Captcha incorrecto!", "es"),("84", "DOWNLOADS_CLIENT_TEXT", "Descarga el cliente", "es"),
	("85", "DOWNLOADS_PATCH_TEXT", "Descarga el parche para el cliente", "es"),("86", "LOGIN_ACCOUNT_LABEL", "Cuenta ID", "es"), ("87", "LOGIN_PASSWORD_LABEL", "Contraseña", "es"),
	("88", "LOGIN_FORGETPASSWORD_LABEL", "Olvidaste la contraseña?", "es"),("89", "GO_USERPANEL_LABEL", "Panel de Usuario", "es"),("90", "GO_LOGOUT_LABEL", "Cerrar sessión", "es"),
	("91", "STATUS_LABEL", "Estado:", "es"),("92", "SERVER_STATUS_LABEL", "Estado del servidor", "es"),("93", "TOP_5_INDEX_RANKING_LABEL", "TOP 5 JUGADORES", "es"),
	("94", "INDEX_FULL_RANKING_TEXT", "Ver Ranking Completo", "es"),("95", "MSG_INVALID_USERNAME", "Username Field Only accept letters \'a\' to \'z\' and numbers with 4 to 12 of length", "en"),
	("96", "MSG_INVALID_USERNAME", "El campo usuario solo acepta letras de la \'a\' a la \'z\' y numeros de 4 a 12 digitos", "es"),
	("97", "MSG_NOT_MATCH_EMAILS", "Emails fields not match", "en"), ("98", "MSG_NOT_MATCH_EMAILS", "Los campos de correo no son iguales", "es"),
	("99", "CHANGE_EMAIL_MSG_SUCCESS", "Email changed succesfully", "en"), ("100", "CHANGE_EMAIL_MSG_SUCCESS", "El correo ha cambiado con exito.", "es"),
	("101", "MSG_INVALID_USER_OR_PW", "Username or Password are wrong.", "en"),("102", "MSG_INVALID_USER_OR_PW", "El usuario y/o contraseña esta incorrecto.", "es"),
	("103", "CHANGE_PWD_MSG_SUCCESS", "Password are changed succssfully.", "en"),("104", "CHANGE_PWD_MSG_SUCCESS", "La contraseña ha sido cambiada con exito.", "es")
	;';
	/* Menu Manager */
	$sql_menu_manager = '
	CREATE TABLE `openconquercms_menu` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`name` varchar(255) DEFAULT NULL,
		`link` varchar(255) DEFAULT NULL,
		`sort_order` int(11) DEFAULT NULL,
		`parent_id` int(11) DEFAULT "0",
		`visible` int(11) DEFAULT "1",
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET='.$database_config["CHARSET"].';';
	$sql_menu_manager_default = '
	INSERT INTO `openconquercms_menu` VALUES ("1", "MENU_TEXT_1", "index.php", "1", "0", "1"),("2", "MENU_TEXT_2", "register.php", "2", "0", "1"),("3", "MENU_TEXT_3", "downloads.php", "3", "0", "1"),
	("4", "MENU_TEXT_4", "buy.php", "4", "0", "1"),("5", "MENU_TEXT_5", "ranking.php", "5", "0", "1"),("6", "MENU_TEXT_6", "openconquercms_panel.php", "6", "0", "1");
	';
	$sql_any_fail = false;
	$sql_setup = array($sql_news, $sql_new_default, $sql_users, $sql_user_default, $sql_settings, $sql_settings_default, $sql_settings_key, $sql_lang, $sql_lang_default, $sql_buy, $sql_menu_manager, $sql_menu_manager_default);
	foreach ($sql_setup as $key => $sql) {
		$q = mysqli_query($conn, $sql);
		if (!$q) {
			$sql_any_fail = mysqli_error($conn);
		}
	}
	if (!$sql_any_fail) {
		$configureHTML = generateSuperBox('OpenConquerCMS - Your account is created', '
		<div><h3 class="text-center text-primary">Your unique account for OpenConquerCMS Panel:</h3></div>
		<div>
			<p class="text-info">Very Important Data:</p>
			<div class="col-8 col-sm-3 col-md-2 mx-auto">
				<ul>
					<li>Username: admin</li>
					<li>Password: '.$randPwd.'</li>
				</ul>
			</div>
			<h3>You now have a new three tables \'openconquercms_news\', \'openconquercms_users\' and \'openconquercms_settings\' for use OpenConquerCMS. Not delete this tables for correctly working of CMS.</h3>
			<a href="'.APP_PATH.'"><font color="darkred">Go to index page for continue...</font></a>
		</div>
		', '', true);
	}
	if (DEBUG) {
		echo mysqli_error($conn);
	}
}
if (isset($configureHTML)) {
	setcookie('showSetupFinish', $configureHTML, time() + (86400 * 30));
	if (!isset($_GET["inSetup"])) {
		header("Location: setup.php?inSetup=true");
	}
}
?>