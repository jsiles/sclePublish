<?php
include_once("../core/admin.php");
admin::initializeClient();
$pass = admin::getParam("pass");
$pass2 = admin::getParam("pass2");
$idUser = admin::getParam("idUser");

if ($pass==$pass2){
    $pass = md5($pass);
	$passDB = admin::getDbValue("select cli_password from mdl_client where cli_uid=".$idUser);
	if ($pass!=$passDB){
		$sSQL = "update mdl_client set "
			. " cli_password = '".$pass."'"
			. ",cli_pass_change = 1"
			. " where cli_uid=$idUser";
	
		$db->query($sSQL);
		$msg="Actualizaci&oacute;n realizada correctamente.";
	}
	else $msg="Debe cambiar la contrase&ntilde;a.";
}
else $msg="Las contrase&ntilde;as no son iguales.";
echo $msg;
?>