<?php
include_once("../core/admin.php");
admin::initializeClient();
$_keycode=admin::getParam("_keycode");
$uidClient = admin::getSession("uidClient");
$datetime = date("Y-m-d H:i:s");
$db->query("insert into mdl_terminos (ter_sub_uid,ter_cli_uid,ter_datetime) values($_keycode, $uidClient, '$datetime')");
//echo "insert into mdl_termnios values(null, $_keycode, $uidClient, '$datetime')";die;
?>