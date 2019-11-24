<?php
include_once("core/admin.php");
admin::initializeClient();
//$sql = "SELECT * FROM mdl_product, mdl_subasta, mdl_pro_category WHERE sub_uid=pro_sub_uid and sub_pca_uid=pca_uid and sub_delete=0 and pro_url='".$urlSubTitle2."'";
//$db->query($sql);
//$details = $db->next_record();

if ($content_details["col_metatitle"]) $seo = ucfirst(strtolower($content_details["col_metatitle"])).' &gt; ';
else $seo=' ';

if(file_exists(PATH_ROOT.'/img/subasta/img_'.$details["pro_image"]))
{
	$image1 = PATH_ROOT.'/img/subasta/img_'.$details["pro_image"];
	list($width, $height) = getimagesize($image1);
}
	if ($width<132) $maxAncho=132-$width;
	else $maxAncho=0;
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<?php include_once 'meta.php'; ?>
<link href="<?=$domain?>/css/style.css" rel="stylesheet" type="text/css" media="all" />
<link rel="shortcut icon" href="<?=$domain?>/lib/favicon.ico" />
<script type="text/javascript">var SERVER='<?=$domain?>'; </script>
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
		 else location.reload(); //comentar este punto para no refrescar la pantalla
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
<style type="text/css">
@import "<?=$domain?>/css/jquery.countdown.css";
/*#defaultCountdown { width: 240px; height: 45px; }*/
</style>
<style type="text/css">@import "<?=$domain?>/css/layout.css";</style>
<script type="text/javascript">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
        loading_image : '<?=$domain?>/loading.gif',
        close_image   : '<?=$domain?>/closelabel.gif'
      }) 
    })
  </script>
</head>
<body class="details">
<div id="wrapper">
	<div id="wrapper-bgbtm">
		<?php include("code/header.php");?>
        <?php include("code/menu_header.php");?>
        <div id="page" class="container">
            <?php include("code/subastaJaponesaTpl.php"); ?>
			<?php include("code/column.php");?>
		</div>
	</div>
</div>
<?php include("code/footer.php");?>
</body>
</html>