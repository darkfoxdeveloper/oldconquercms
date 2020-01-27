<?php
require_once("../inc/settings.php");
require_once("../inc/functions.php");
$lang = getLang($_POST["key"]);
echo json_encode($lang);
?>