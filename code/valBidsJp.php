<?php
include_once("../core/admin.php");
admin::initializeClient();
$deadTime = admin::getParam("deadTime");
$sub_uid = admin::getParam("sub_uid");
$sql = "SELECT * FROM mdl_product, mdl_subasta, mdl_pro_category WHERE sub_uid=pro_sub_uid and sub_pca_uid=pca_uid and sub_delete=0 and sub_deadTime>'".$deadTime."' and sub_uid=".$sub_uid;
$db->query($sql);
if($details = $db->next_record())
{
$moneda = admin::getDbValue("select cur_description from mdl_currency where cur_uid='".$details["sub_moneda"]."'");
	
$timetobe=admin::time_diff($details["sub_hour_end"],date('Y-m-d H:i:s'));
$timedead=admin::time_diff($details["sub_deadtime"],date(
'Y-m-d H:i:s'));
$finish=$details["sub_finish"];
$timeSubasta = $details["sub_tiempo"];
//$quantityDates = ceil(((($details["sub_mountdead"]-$details["sub_mount_base"])/($details["sub_mount_unidad"]))+1));
$yearTD = substr($details["sub_hour_end"],0,4);
$monthTD = substr($details["sub_hour_end"],5,2);
$dayTD = substr($details["sub_hour_end"],8,2);
$hourTD = substr($details["sub_hour_end"],11,2);
$minuteTD = substr($details["sub_hour_end"],14,2);
$secondTD = substr($details["sub_hour_end"],17,2);
$sw=false;				
$fechahora=date('Y-m-d H:i:s');
$l=0;
/*echo "hour end:". $details["sub_hour_end"]."<br>";
echo "hora-minuto-segundo:".$hourTD."-".$minuteTD."-".$secondTD."<br>";
echo "noow:".date("Y/m/d H:i:s");
echo "<br>";
echo "timedead:".$timedead."<br>"."sub_deadtime:".$details["sub_deadtime"]."<br>".$sw;*/

if (($timetobe>0)&&($finish==0)){
$daystobe=intval($timetobe/86400);
$timetobe=$timetobe-($daystobe*86400);
$hourstobe=intval($timetobe/3600);
$timetobe=$timetobe-($hourstobe*3600);
$minutetobe=intval($timetobe/60);
$timetobe=$timetobe-($minutetobe*60);
$faltante =$daystobe.'d '.$hourstobe.'h '.$minutetobe.'m '.$timetobe.'s ';
$timeInicio = 1;
$m=1;
}
elseif(($timedead>0)&&($finish==0))
{
$faltante='Iniciada';
//echo $faltante;
$daysdead=intval($timedead/86400);
$timedead=$timedead-($daysdead*86400);
$hoursdead=intval($timedead/3600);
$timedead=$timedead-($hoursdead*3600);
$minutedead=intval($timedead/60);
$timedead=$timedead-($minutedead*60);
$timeInicio = 2;
}else {
	$faltante='Concluida';
	$daystobe=0;
	$hourstobe=0;
	$minutetobe=0;
	$timetobe=0;
	$timeInicio = 3;
	}
$regBidsWin = admin::getDbValue("select max(bid_uid) from mdl_bid where bid_sub_uid = ".$details["sub_uid"]." and bid_cli_uid=".admin::getSession("uidClient"));
									if(isset($regBidsWin))
									{
									$regBidsWinMax = admin::getDbValue("select max(bid_uid) from mdl_bid where bid_sub_uid = ".$details["sub_uid"]);
									if($regBidsWin==$regBidsWinMax) $winMessage="Su oferta gan&oacute;";
									else
									$winMessage="Su oferta perdi&oacute;";
									}	
																		
?>
<script type="text/javascript">
$(function () {
	<?php
	if($timeInicio==1)
	{
		
	?>
	var austDay = new Date();
	austDay = new Date(austDay.getFullYear() ,austDay.getMonth() ,austDay.getDate()+<?=$daystobe?>, austDay.getHours()+<?=$hourstobe?>, austDay.getMinutes()+<?=$minutetobe?>,austDay.getSeconds()+<?=$timetobe?>);
	$('#defaultCountdown').countdown({until: austDay,format: 'HMS',onExpiry: subastaOn});
	<?php
	}elseif($timeInicio==2){
	?>
	bids();
	$("#tiempoRestante").html('Fecha cierre de la subasta:');
	$('#defaultCountdown').html('<?=admin::changeFormatDate($details["sub_deadtime"],7)?>');
	$("#tiempoSubasta").show();
	$("#subastaP").fadeIn('slow');	
	var subastaDay = new Date();
	subastaDay = new Date(subastaDay.getFullYear() ,subastaDay.getMonth() ,subastaDay.getDate()+<?=$daysdead?>, subastaDay.getHours()+<?=$hoursdead?>, subastaDay.getMinutes()+<?=$minutedead?>,subastaDay.getSeconds()+<?=$timedead?>);
	$('#defaultCountdown1').countdown({until: subastaDay,format: 'MS',onExpiry: subastaReload});
	<?php 
	}else{
	?>
	$("#tiempoRestante").html('Fecha de la subasta:');
	$('#defaultCountdown').html('<?=admin::changeFormatDate($details["sub_deadtime"],7)?>');
	$("#tiempoSubasta").show();
	subastaOff();
	<?php
	}
	?>
});
function bids()
{
	$.ajax({
	   type: "POST",
	   url: "<?=$domain?>/code/valBidsJp.php",
	   data: "deadTime="+'<?=$details["sub_deadtime"]?>'+"&sub_uid="+<?=$details["sub_uid"]?>,
	   success: function(valBids){
		 if(valBids==1) setTimeout(function(){bids();},1000);
		 else $("#subastaDetail").html(valBids); 
	   }
	 });
	
	}
function subastaOn()
{
	bids();
	$("#tiempoSubasta").show();
	$("#subastaP").fadeIn('slow');	
	$("#tiempoRestante").html('Fecha de la subasta:');
	$('#defaultCountdown').html('<?=admin::changeFormatDate($details["sub_deadtime"],7)?>');

	var subastaDay = new Date();
	subastaDay = new Date(subastaDay.getFullYear() ,subastaDay.getMonth() ,subastaDay.getDate(), subastaDay.getHours(), subastaDay.getMinutes()+<?=$timeSubasta?>,subastaDay.getSeconds());
	$('#defaultCountdown1').countdown({until: subastaDay,format: 'MS',onExpiry: subastaReload});

}
function subastaReload()
{
	$.ajax({
	   type: "POST",
	   url: "<?=$domain?>/code/valBidsJaponesa.php",
	   data: "deadTime="+'<?=$details["sub_deadtime"]?>'+"&sub_uid="+<?=$details["sub_uid"]?>+"&timeSubasta="+'<?=$timeSubasta?>',
	   success: function(valBids){
		 if(valBids==1) subastaOff();
	   }
	 });
}
function subastaOff()
{
	var message = $("#message").html();
	if(!message ) message ='';
	$("#subastaP").hide();
	$("#unidadmejora").hide();
	$.ajax({
	   type: "POST",
	   url: "<?=$domain?>/code/finish.php",
	   data: "deadTime="+'<?=$details["sub_deadtime"]?>'+"&sub_uid="+<?=$details["sub_uid"]?>,
	      success: function(finish){
		 $("#defaultCountdown1").html(finish);
		 <?php
		 if($details["sub_finish"]==0)
		 {
		 ?>
		  jQuery.facebox('<form name="formBids" class="formLabel">La subasta fue concluida, '+ message+' gracias por participar!!<br><br><a href="Cerrar" onclick="$.facebox.close();return false;" class="addcart">Cerrar</a></p></form><br>');
		  <?php
		 }
		  ?>
	   }
	 });
}
</script>

<script type="text/javascript">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
        loading_image : '<?=$domain?>/loading.gif',
        close_image   : '<?=$domain?>/closelabel.gif'
      }) 
    })
  </script>
<div id="subastaDetail" class="details">
                                <?php
								    $bidsCompra=admin::getDBvalue("SELECT sub_type FROM mdl_subasta where sub_uid=".$details["sub_uid"]);
									if($bidsCompra=='COMPRA') 
									$valBids=admin::getDBvalue("SELECT min(bid_mountxfac) FROM mdl_bid where bid_pro_uid='".$details["pro_uid"]."'");
									else
									$valBids=admin::getDBvalue("SELECT max(bid_mountxfac) FROM mdl_bid where bid_pro_uid='".$details["pro_uid"]."'");
									
									$factor = admin::getDbValue("select inc_ajuste from mdl_incoterm where inc_delete=0 and inc_cli_uid=".admin::getSession("uidClient")." and inc_sub_uid=".$details["sub_uid"]);
									//$regBids = admin::getDbValue("select count(*) from mdl_bid where bid_sub_uid = ".$details["sub_uid"]);
									
									if(!$valBids) 
								    {
										$centavos=substr($details["sub_mount_base"],-3);
										$montoGlobal=str_replace($centavos,'',$details["sub_mount_base"]);
										$valBids=$details["sub_mount_base"];
										}
									else
									{
										$centavos=substr($valBids,-3);
										$montoGlobal=str_replace($centavos,'',$valBids);
										}
								
									$centavos=str_replace('.','',$centavos);
									
									
								?>
									<p class="left">Precio: <?=$moneda?> <?=$montoGlobal?>.<sup><?=$centavos?></sup></p>
                                    <div class="clear"></div>
                                    <?php
                                    if($factor)
									{
									?>
                                     <p class="left"> Factor de ajuste:<?=$factor?>%
                                    <div class="clear"></div>
                                    <?php
									}
                                /*    if($regBids>0)
									{
									?><p class="left">
                                    Listado de pujas:<a href="<?=$domain?>/code/listBids.php?_keycode=<?=SymmetricCrypt::encrypt(base64_encode($details["sub_uid"]))?>" rel="facebox"><label style="color:#00F"><?=$regBids?></label> puja(s)</a>
                                    <div class="clear"></div>
                                   <?php
								   if(isset($regBidsWin))
								   {
									if(($regBidsWin==$regBidsWinMax)&&($details["sub_finish"]==0))
									{   
								   ?>
                                   		<p class="left" style="color:#00F">
                                    Su oferta est&aacute; ganando</p><p style="display:none" class="rigth" id="message">
                                    felicidades su oferta ganan&oacute;</p>
                                    <div class="clear"></div>
                                    <?php
									}elseif(($regBidsWin!=$regBidsWinMax)&&($details["sub_finish"]==0))
									{
									?>
                                   		<p class="left" style="color:#00F">
                                    Su oferta est&aacute; perdiendo</p><p style="display:none" class="rigth" id="message">
                                    lo sentimos su oferta perdio</p>
                                    <div class="clear"></div>
                                    <?php
										
										}else
										{

									?>
                                   		<p class="left">
                                    <?=$winMessage?></p>
                                    <div class="clear"></div>
                                    <?php
											
											}
								   }
								   
								   }*/
                                    ?><p class="left" id="tiempoRestante" style="display:">Tiempo de inicio:&nbsp; 
 <div id="defaultCountdown" class="defCountDown"></div>
 </p>

<p class="left" id="tiempoSubasta" style="display:none">Tiempo de subasta:&nbsp;
 <div id="defaultCountdown1" class="defCountDown"></div>
 </p>

										<form name="frmContact" id="formA" action="" method="post">
		<p id="subastaP" style="display:none;">
			<label class="bold">Oferta:</label>
			<input name="ct_value" id="ct_value" type="hidden" size="15" class="inputB" value="<?=$valBids?>"/> <a href="<?=$domain?>/code/bidsJp.php?uid=<?=$details["sub_uid"]?>&ofert=<?=$valBids?>"  id="planCuentas" rel="facebox" class="addcart">Aceptar</a>
        </p>
      
									  <p id="unidadmejora"><label class="bold">Unidad de Mejora:</label> <?=$moneda?> <?=$details["sub_mount_unidad"]?></p>
                                    
           <input type="hidden" name="hOk" id="hOk" value="" />
            <input type="hidden" name="domain" id="domain" value="<?=$domain?>" />
            <input type="hidden" name="uid" id="uid" value="<?=$details["sub_uid"]?>" />
</form>
                    </div>
<?php
}else echo 1;
?>            
        