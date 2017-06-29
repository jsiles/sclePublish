<?php
include_once("../core/admin.php");
admin::initializeClient();
$deadTime = admin::getParam("deadTime");
$year = substr($deadTime,0,4);
$month = substr($deadTime,5,2);
$day = substr($deadTime,8,2);
$hora = substr($deadTime,11,2);
$minuto = substr($deadTime,14,2);
$segundo = substr($deadTime,17,2);
$timeSubasta = admin::getParam("timeSubasta");
$deadTime1 = date("Y-m-d H:i:s", mktime($hora,$minuto+$timeSubasta
,$segundo, $month, $day, $year));
$sub_uid = admin::getParam("sub_uid");
$db->query("select * from mdl_subasta where sub_uid=$sub_uid");
$subastaDList = $db->next_record();
$pro_uid=admin::getDbValue("SELECT pro_uid FROM mdl_product where pro_sub_uid=$sub_uid");
$catUid= $subastaDList["sub_pca_uid"]; //admin::getDbValue("SELECT sub_pca_uid FROM mdl_subasta where sub_uid=$sub_uid");

$unidadMejora=$subastaDList["sub_mount_unidad"];//admin::getDbValue("SELECT sub_mount_unidad FROM mdl_subasta where sub_uid=$sub_uid");

$mountBase=$subastaDList["sub_mount_base"];//admin::getDbValue("SELECT sub_mount_base FROM mdl_subasta where sub_uid=$sub_uid");

$bidsCompra=$subastaDList["sub_type"];//admin::getDbValue("SELECT sub_type FROM mdl_subasta where sub_uid=$sub_uid");
$numberRounds =$subastaDList["sub_wheels"];
$wheel = admin::getParam("wheel");

if($wheel<=$numberRounds)
{
    $sql = "update mdl_round set rou_flag0=1 where  rou_datetime<GETDATE()  and rou_sub_uid=$sub_uid";
    $db->query($sql);
    $sql = "update mdl_round set rou_flag0=0 where  rou_datetime<GETDATE()  and rou_sub_uid=$sub_uid";
    $db->query($sql);  
    echo "reload";
}  else {
 echo 1;   
}

?>