<?php
include_once("core/admin.php");
admin::initializeClient();
$sql = "SELECT * FROM mdl_product, mdl_subasta, mdl_pro_category WHERE sub_uid=pro_sub_uid and sub_pca_uid=pca_uid and sub_delete=0 and pro_url='".$urlSubTitle2."'";
$db->query($sql);
$details = $db->next_record();

if ($content_details["col_metatitle"]) $seo = ucfirst(strtolower($content_details["col_metatitle"])).' &gt; ';
else $seo=' ';

$image1 = PATH_ROOT.'/img/subasta/img_'.$details["pro_image"];
list($width, $height) = getimagesize($image1);
if ($width<132) $maxAncho=132-$width;
else $maxAncho=0;

$timetobe=admin::time_diff($details["sub_hour_end"],date('Y-m-d H:i:s'));
$timeSubasta = $details["sub_tiempo"];
if ($timetobe>0){
$daystobe=intval($timetobe/86400);
$timetobe=$timetobe-($daystobe*86400);
$hourstobe=intval($timetobe/3600);
$timetobe=$timetobe-($hourstobe*3600);
$minutetobe=intval($timetobe/60);
$timetobe=$timetobe-($minutetobe*60);
$faltante =$daystobe.'d '.$hourstobe.'h '.$minutetobe.'m '.$timetobe.'s ';
$timeInicio = 1;
}
else
{
	 $faltante='Concluida';
$daystobe=0;
$hourstobe=0;
$minutetobe=0;
$timetobe=0;
$timeInicio = 0;

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="<?=$domain?>/css/style.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="<?=$domain?>/js/jquery.js"></script>
<script type="text/javascript" src="<?=$domain?>/js/admin.js"></script>
<link href="<?=$domain?>/css/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="<?=$domain?>/js/facebox.js" type="text/javascript"></script>
<script src="<?=$domain?>/js/jquery.fcbkcomplete.js" type="text/javascript" charset="utf-8"></script>
<script src="<?=$domain?>/js/jquery.countdown.js" type="text/javascript" charset="utf-8"></script>
<script src="<?=$domain?>/js/jquery.countdown-es.js" type="text/javascript" charset="utf-8"></script>

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
	}else{
	?>
	var austDay = new Date();
	austDay = new Date(austDay.getFullYear() ,austDay.getMonth() ,austDay.getDate()+<?=$daystobe?>, austDay.getHours()+<?=$hourstobe?>, austDay.getMinutes()+<?=$minutetobe?>,austDay.getSeconds()+<?=$timetobe?>);
	$('#defaultCountdown').countdown({until: austDay,format: 'HMS'});
	subastaOn();
	<?php 
	}
	?>
});
function subastaOn()
{
	$("#tiempoSubasta").show();
	$("#subastaP").fadeIn('slow');	
	var subastaDay = new Date();
	subastaDay = new Date(subastaDay.getFullYear() ,subastaDay.getMonth() ,subastaDay.getDate(), subastaDay.getHours(), subastaDay.getMinutes()+<?=$timeSubasta?>,subastaDay.getSeconds());
	$('#defaultCountdown1').countdown({until: subastaDay,format: 'MS',onExpiry: subastaOff});
}
function subastaOff()
{
	$("#subastaP").hide();	
}
</script>
<style type="text/css">
@import "<?=$domain?>/css/jquery.countdown.css";
/*#defaultCountdown { width: 240px; height: 45px; }*/
</style>
<style type="text/css">@import "<?=$domain?>/css/layout.css";</style>
<script type="text/javascript">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
        loading_image : 'loading.gif',
        close_image   : 'closelabel.gif'
      }) 
    })
  </script>
</head>
<body class="details">
<div id="wrapper">
	<div id="wrapper-bgbtm">
		<? include("code/header.php");?>
		<div id="page" class="container">
			<div id="content">
				<div id="box7" class="box-style">
                  
					<div class="title">
						<h2><span><?=$details["pro_name"]?></span></h2>
					</div>
					<div class="item-box">
                    <p style="margin-left:<?=$maxAncho?>px;"><img src="<?=$domain.'/img/subasta/img_'.$details["pro_image"]?>" class="alignleft" alt="<?=utf8_encode($details["pro_name"])?>" title="<?=utf8_encode($details["pro_name"])?>"/></p>
                    	<!--<h2><?=$details["pro_name"]?></h2>-->
						<!--<p><?=utf8_encode($details["sub_description"])?></p>-->
						<div class="details">
                                <?php
//echo $timeSubasta;
								
								   $bidsCompra=admin::getDBvalue("SELECT a.sub_type FROM mdl_subasta a, mdl_bid b where b.bid_pro_uid='".$details["pro_uid"]."' and b.bid_sub_uid=a.sub_uid");
									if($bidsCompra=='COMPRA') 
									$valBids=admin::getDBvalue("SELECT min(bid_mountxfac) FROM mdl_bid where bid_pro_uid='".$details["pro_uid"]."'");
									else
									$valBids=admin::getDBvalue("SELECT max(bid_mountxfac) FROM mdl_bid where bid_pro_uid='".$details["pro_uid"]."'");
									if(!$valBids) 
								    {
										$centavos=substr($details["sub_mount_base"],-3);
										$montoGlobal=str_replace($centavos,'',$details["sub_mount_base"]);
										}
									else
									{
										$centavos=substr($valBids,-3);
										$montoGlobal=str_replace($centavos,'',$valBids);
										}
									$centavos=str_replace('.','',$centavos);
									
								?>
									<p class="left">Precio: $us <?=$montoGlobal?>.<sup><?=$centavos?></sup></p>
                                    <!--<p class="right"><a href="#" class="addcart"><?=$faltante?></a>
                                    </p>-->
                                    <div class="clear"></div>
                                    <p class="left" id="tiempoRestante" style="display:">Tiempo de inicio:&nbsp; 
 <div id="defaultCountdown" style="width:250px;margin-top:9px;margin-left:4px;height:25px;font-size:12px;font-weight:bold;color:#dd2d26;"></div>
 </p>

<p class="left" id="tiempoSubasta" style="display:none">Tiempo de subasta:&nbsp;
 <div id="defaultCountdown1" style="width:500px;margin-top:9px;margin-left:4px;height:25px;font-size:12px;font-weight:bold;color:#dd2d26;"></div>
 </p>

										<form name="frmContact" id="formA" action="" method="post">
		<p id="subastaP" style="display:none;">
			<label class="bold">Oferta:</label>
			<input name="ct_value" id="ct_value" type="text" size="15" onkeyup="valOfert();" class="inputB"/> <a href="<?=$domain?>/code/bids.php?uid=<?=$details["sub_uid"]?>" id="planCuentas" rel="facebox" class="addcart">Ofertar</a>
        (Ingrese <?php 
		if($bidsCompra=='COMPRA')
		{
		if($details["sub_mount_base"]<=$valBids) echo '$'.number_format(round(($details["sub_mount_base"]-$details["sub_mount_unidad"]),2),2).' o menos)'; 
		else echo '$'.($valBids-$details["sub_mount_unidad"]).' o menos)'; 
		
			}
		else
		{
		if($details["sub_mount_base"]>=$valBids) echo '$'.number_format(round(($details["sub_mount_base"]+$details["sub_mount_unidad"]),2),2).' o m&aacute;s)'; 
		else echo '$'.($valBids+$details["sub_mount_unidad"]).' o m&aacute;s)'; 
			}
		?></p>
      
									  <p><label class="bold">Unidad de Mejora:</label> $us <?=$details["sub_mount_unidad"]?></p>
                                    
           <input type="hidden" name="hOk" id="hOk" value="" />
            <input type="hidden" name="domain" id="domain" value="<?=$domain?>" />
            <input type="hidden" name="uid" id="uid" value="<?=$details["sub_uid"]?>" />
</form>
                    </div>
                    
                    
                    								</div>
					
                    
                    <div class="content">
						<?=utf8_encode($details["pro_description"])?></p>
					</div>
				</div>
			</div>
			<? include("code/column.php");?>
		</div>
	</div>
</div>
<? include("code/footer.php");?>
</body>
</html>
