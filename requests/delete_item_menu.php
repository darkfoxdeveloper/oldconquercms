<?php
require_once("../inc/settings.php");
require_once($_SESSION["INCLUDE_ROOT_PATH"]."inc/connect.php");
$response["status"] = "ERROR";
if (checkPermissionOCCMSPanel()) {
    $sql = "DELETE FROM openconquercms_menu WHERE id='".mysqli_real_escape_string($conn, $_POST["item_menu_id"])."'";
    $q = mysqli_query($conn, $sql);
    if ($q) {
        $response["status"] = "SUCCESS";
    } else {
        $response["status"] = "MYSQL_ERROR";
        $response["message"] = mysqli_error($conn);
    }
} else {
    $response["message"] = "You don't have permissions to do this.";
}
echo json_encode($response);
?>