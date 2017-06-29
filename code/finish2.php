<?php
include_once("../core/admin.php");
admin::initializeClient();
$sub_uid = admin::getParam("sub_uid");
$sql = "update mdl_subasta set sub_finish=3 WHERE and sub_uid=".$sub_uid;
$db->query($sql);
?>            
        