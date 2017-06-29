<?php
include_once("../core/admin.php");
admin::initializeClient();
$monto_ofertado=admin::getParam("ofert");
$sub_uid=admin::getParam("uid");
$pro_uid=admin::getDBvalue("SELECT pro_uid FROM mdl_product where pro_sub_uid='".$sub_uid."'");
$bidsCompra=admin::getDBvalue("SELECT sub_type FROM mdl_subasta where sub_uid='".$sub_uid."'");
if($bidsCompra=='COMPRA')
$valBids=admin::getDBvalue("SELECT MIN(bid_mountxfac) FROM mdl_bid where bid_pro_uid='".$pro_uid."'");
else
$valBids=admin::getDBvalue("SELECT MAX(bid_mountxfac) FROM mdl_bid where bid_pro_uid='".$pro_uid."'");
$mBase=admin::getDBvalue("SELECT sub_mount_base FROM mdl_subasta where sub_uid='".$sub_uid."'");
$unidad=admin::getDBvalue("SELECT sub_mount_unidad FROM mdl_subasta where sub_uid='".$sub_uid."'");
$catUid=admin::getDBvalue("SELECT sub_pca_uid FROM mdl_subasta where sub_uid='".$sub_uid."'");
$sub_tiempo=admin::getDBvalue("SELECT sub_tiempo FROM mdl_subasta where sub_uid='".$sub_uid."'");
$factor = admin::getParam("factor");
$cli_uid = admin::getSession("uidClient");
$newDeadTime = date("Y-m-d H:i:s", mktime(date("H"),date("i")+$sub_tiempo,date("s"),date("m"),date("d"),date("Y")));

$orig_monto_ofertado =$monto_ofertado;
if(!$factor) $factor=0;
if($bidsCompra=='COMPRA')
$monto_ofertado = $monto_ofertado + ($monto_ofertado*$factor/100);
else
$monto_ofertado = $monto_ofertado - ($monto_ofertado*$factor/100);

//echo $valBids;
if(!$valBids) {
	$mayVal=$mBase; 
    $valBids=$mBase;
//echo $valBids."#".$bidsCompra;
}
if($bidsCompra=='COMPRA') $mayVal=$valBids-$unidad;
else $mayVal=$valBids+$unidad;
if($bidsCompra=='COMPRA')
{
	if(!$monto_ofertado) echo 'Introduzca una mejor oferta al monto minimo:'.$mayVal;
	elseif(round($monto_ofertado,2)>round($mayVal,2)) echo 'Su oferta ya fue superada, introduzca una mejor oferta al monto minimo:'.$mayVal;
	else {
		$maxUid=admin::getDBvalue("SELECT max(bid_uid) FROM mdl_bid");
		$maxUid++;
		$sql = "insert into mdl_bid( bid_uid, bid_sub_uid, bid_pro_uid, bid_cli_uid, bid_mount, bid_mountxfac, bid_date, bid_pca_uid)
						values	($maxUid,$sub_uid, $pro_uid,$cli_uid ,$orig_monto_ofertado, $monto_ofertado,GETDATE(),$catUid)";
		$db->query($sql);
		$sql = "update mdl_subasta set sub_deadtime='".$newDeadTime."' where sub_uid=".$sub_uid;
		$db->query($sql);
		
		echo 'Se acepto su oferta:'.$monto_ofertado.' '.date('d-m-Y H:i:s');	
	}
}else
{

	if(!$monto_ofertado) echo 'Introduzca una mejor oferta al monto minimo:'.$mayVal;
	elseif(round($monto_ofertado,2)<round($mayVal,2)) echo 'Su oferta ya fue superada, introduzca una mejor oferta al monto minimo:'.$mayVal;
	else {
                $maxUid=admin::getDBvalue("SELECT max(bid_uid) FROM mdl_bid");
		$maxUid++;
		
		$sql = "insert into mdl_bid( bid_uid, bid_sub_uid, bid_pro_uid, bid_cli_uid, bid_mount, bid_mountxfac, bid_date, bid_pca_uid)
						values	($maxUid,$sub_uid, $pro_uid,$cli_uid,$orig_monto_ofertado,$monto_ofertado,GETDATE(),$catUid)";
		$db->query($sql);
                //echo $sql;
                $sql = "update mdl_subasta set sub_deadtime='".$newDeadTime."' where sub_uid=".$sub_uid;
		$db->query($sql);
		
		echo 'Se acepto su oferta:'.$monto_ofertado.' '.date('d-m-Y H:i:s');	
	}
}
?>