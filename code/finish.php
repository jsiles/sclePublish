<?php
include_once("../core/admin.php");
admin::initializeClient();
$deadTime = admin::getParam("deadTime");
$sub_uid = admin::getParam("sub_uid");
$sql = "update mdl_subasta set sub_finish=3 WHERE sub_finish=1 and sub_delete=0 and sub_deadTime='".$deadTime."' and sub_uid=".$sub_uid;
if($db->query($sql)) echo 'Concluida';
else 'error';
?>