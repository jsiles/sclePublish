<?php
include_once("../core/admin.php");
$pack=admin::getParam("pack");
$exist=admin::getDBvalue("SELECT mcc_uid FROM mdl_client_category where mcc_uid='".$pack."'");

$registro=admin::getDBvalue("select col_url from mdl_contents left join mdl_contents_languages on (con_uid=col_con_uid) where con_delete<>1 and col_status='ACTIVE' and con_uid=22 order by con_position limit 1");

$paquetes=admin::getDBvalue("select col_url from mdl_contents left join mdl_contents_languages on (con_uid=col_con_uid) where con_delete<>1 and col_status='ACTIVE' and con_uid=23 order by con_position limit 1");

$suscripcion=admin::getDBvalue("select col_url from mdl_contents left join mdl_contents_languages on (con_uid=col_con_uid) where con_delete<>1 and col_status='ACTIVE' and con_uid=24 order by con_position limit 1");

if($exist) {DBSession::Set("pack",$pack); header('Location: '.$domain.'/'.$registro.'/'.$suscripcion.'/');}
else header('Location: '.$domain.'/'.$registro.'/'.$paquetes.'/');
?>