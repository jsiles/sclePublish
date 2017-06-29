<?php
include_once("../core/admin.php");
admin::initializeClient();
$monto_ofertado=admin::getParam("ofert");
//echo $monto_ofertado."#";
$sub_uid=admin::getParam("uid");
$pro_uid=admin::getDBvalue("SELECT pro_uid FROM mdl_product where pro_sub_uid='".$sub_uid."'");
$bidsCompra=admin::getDBvalue("SELECT sub_type FROM mdl_subasta where sub_uid='".$sub_uid."'");
if($bidsCompra=='COMPRA')
$valBids=admin::getDBvalue("SELECT MAX(bid_mountxfac) FROM mdl_bid where bid_pro_uid='".$pro_uid."'");
else
$valBids=admin::getDBvalue("SELECT MIN(bid_mountxfac) FROM mdl_bid where bid_pro_uid='".$pro_uid."'");

$mBase=admin::getDBvalue("SELECT sub_mount_base FROM mdl_subasta where sub_uid='".$sub_uid."'");
$unidad=admin::getDBvalue("SELECT sub_mount_unidad FROM mdl_subasta where sub_uid='".$sub_uid."'");
$catUid=admin::getDBvalue("SELECT sub_pca_uid FROM mdl_subasta where sub_uid='".$sub_uid."'");
$sub_tiempo=admin::getDBvalue("SELECT sub_tiempo FROM mdl_subasta where sub_uid='".$sub_uid."'");

$factor = admin::getDbValue("select inc_ajuste from mdl_incoterm where inc_delete=0 and inc_cli_uid=".admin::getSession("uidClient")." and inc_sub_uid=".$sub_uid);
if(!$factor) $factor=0;
$orig_monto_ofertado =$monto_ofertado;
if($bidsCompra=='COMPRA')
$monto_ofertado = $monto_ofertado - ($monto_ofertado*$factor/100);
else
$monto_ofertado = $monto_ofertado + ($monto_ofertado*$factor/100);

$deadTime = admin::getDBvalue("SELECT sub_deadtime FROM mdl_subasta where sub_uid='".$sub_uid."'");
$newDeadTime = date("Y-m-d H:i:s", mktime(date("H"),date("i")+$sub_tiempo,date("s"),date("m"),date("d"),date("Y")));
//echo $valBids;
?>
<script language="javascript" type="text/javascript">
function bidsLoad()
{
	domain=document.getElementById('domain').value;	
	uid=document.getElementById('uid').value;
	ofert=document.getElementById('ct_value').value;
	factor = <?=$factor?>;
	var subastaDayNow = new Date();
	
	$.ajax({
		   type: "POST",
		   url: domain+"/code/bidsExecute2.php",
		   data: "uid="+uid+"&ofert="+ofert+"&factor="+factor,
		   success: function(html){
			// location.reload();
			 $.facebox.close();	
				var message = $("#message").html();
				if(!message ) message ='';
				$("#subastaP").hide();
				$("#unidadmejora").hide();
				
				$('#defaultCountdown1').countdown('change',{until: 0});
				$("#defaultCountdown1").html('Conluida');
				 jQuery.facebox('<form name="formBids" class="formLabel">La subasta fue concluida, '+ message+' gracias por participar!!<br><br><a href="Cerrar" onclick="$.facebox.close();return false;" class="addcart">Cerrar</a></p></form><br>');
			}
		 });
	//$.facebox.close();		
return false;
}
</script>

<?php
if(!$valBids) {
	$mayVal=$mBase; 
    $valBids=$mBase;
	}
	
if($bidsCompra=='COMPRA') $mayVal=$valBids+$unidad;
else $mayVal=$valBids-$unidad;
//echo $mayVal."#".$valBids."#".$bidsCompra."#".$monto_ofertado;

if($bidsCompra=='COMPRA')
{
	if(!$monto_ofertado) echo '<form name="formBids" class="formLabel">Introduzca una mejor oferta al monto m&iacute;nimo:'.$mayVal.'<br><br><a href="Cerrar" onclick="$.facebox.close();return false;">Cancelar</a></form>';
	else {
		echo '<form name="formBids" class="formLabel">Su oferta es: '.$orig_monto_ofertado.' - el factor de ajuste asciende a: '.$monto_ofertado.', oferta realizada en fecha y a horas:'.date('d-m-Y H:i:s').'.<br><br> Por favor confirmar los datos de la misma. <br><br><p><a href="#" onclick="return bidsLoad();" class="addcart">Confirmar</a> o <a href="Cerrar" onclick="$.facebox.close();return false;">Cancelar</a></p></form><br>';
	}
}else
{

	if(!$monto_ofertado) echo '<form name="formBids" class="formLabel">Introduzca una mejor oferta al monto mínimo:'.$mayVal.'<br><br><a href="Cerrar" onclick="$.facebox.close();return false;">Cancelar</a></form>';
	else {
		echo '<form name="formBids" class="formLabel">Su oferta es: '.$monto_ofertado.', realizado en fecha y horas:'.date('d-m-Y H:i:s').'.<br><br> Por favor confirmar los datos de la misma. <br><br><p><a href="#" onclick="return bidsLoad();" class="addcart">Confirmar</a> o <a href="Cerrar" onclick="$.facebox.close();return false;">Cancelar</a></p></form><br>';
	}
}
?>