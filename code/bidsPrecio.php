<?php
include_once("../core/admin.php");
admin::initializeClient();
$monto_ofertado=admin::getParam("ofert");
//echo $monto_ofertado."#";//die;
$sub_uid=admin::getParam("sub_uid");
$xit_uid=admin::getParam("uid");
$pro_uid=admin::getDBvalue("SELECT pro_uid FROM mdl_product where pro_sub_uid='".$sub_uid."'");
$bidsCompra=admin::getDBvalue("SELECT sub_type FROM mdl_subasta where sub_uid='".$sub_uid."'");
if($bidsCompra=='COMPRA')
$valBids=admin::getDBvalue("SELECT MIN(bid_mountxfac) FROM mdl_biditem where bid_sub_uid='".$sub_uid."' and bid_xit_uid=$xit_uid");
else
$valBids=admin::getDBvalue("SELECT MAX(bid_mountxfac) FROM mdl_biditem where bid_sub_uid='".$sub_uid."' and bid_xit_uid=$xit_uid");

$mBase=admin::getDBvalue("SELECT xit_price FROM mdl_xitem where xit_sub_uid='".$sub_uid."' and xit_uid=$xit_uid");
$unidad=admin::getDBvalue("SELECT xit_unity FROM mdl_xitem where xit_sub_uid='".$sub_uid."' and xit_uid=$xit_uid");
$sub_tiempo=admin::getDBvalue("SELECT sub_tiempo FROM mdl_subasta where sub_uid='".$sub_uid."'");

$factor = admin::getDbValue("select inc_ajuste from mdl_incoterm where inc_delete=0 and inc_cli_uid=".admin::getSession("uidClient")." and inc_sub_uid=".$sub_uid);
if(!$factor) $factor=0;
$orig_monto_ofertado =$monto_ofertado;
if($bidsCompra=='COMPRA')
$monto_ofertado = $monto_ofertado + ($monto_ofertado*$factor/100);
else
$monto_ofertado = $monto_ofertado - ($monto_ofertado*$factor/100);
?>
<script language="javascript" type="text/javascript">
function bidsLoad()
{
	domain=document.getElementById('domain_'+<?=$xit_uid?>).value;	
	uid=document.getElementById('uid_'+<?=$xit_uid?>).value;
	ofert=document.getElementById('ct_value_'+<?=$xit_uid?>).value;
	factor = <?=$factor?>;
	sub_uid = <?=$sub_uid?>;
	round = document.getElementById('round_'+<?=$xit_uid?>).value;
	cli_uid = document.getElementById('cli_uid_'+<?=$xit_uid?>).value;
	var subastaDayNow = new Date();
        
        var file_data = $('#docTec')[0].files[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        $.ajax({
                url:  domain+"/code/upload.php?sub_uid="+sub_uid+"&cli_uid="+cli_uid+"&uid="+uid, // point to server-side PHP script 
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',
                success: function(php_script_response){
                   // alert(php_script_response); // display response from the PHP script, if any
                }        
        });
	$.ajax({
		   type: "POST",
		   url: domain+"/code/bidsExecutePrecio.php",
		   data: "round="+round+"&cli_uid="+cli_uid+"&sub_uid="+sub_uid+"&uid="+uid+"&ofert="+<?=$orig_monto_ofertado?>+"&factor="+factor,
		   success: function(html){
			// location.reload();
                        jQuery.facebox(html);
                        document.getElementById('ct_value_'+<?=$xit_uid?>).value='';
			 setTimeout(function () {
                            $.facebox.close();},2000);
                        
                        setTimeout(function () {location.reload();},2200);
                        
			}
		 });        
	//$.facebox.close();		
return false;
}

function validate_fileupload(fileName)
{
    //var allowed_extensions = new Array("jpg","png","gif");
    var allowed_extensions = new Array("pdf");
    var file_extension = fileName.split('.').pop(); // split function will split the filename by dot(.), and pop function will pop the last element from the array which will give you the extension as well. If there will be no extension then it will return the filename.

    for(var i = 0; i <= allowed_extensions.length; i++)
    {
        if(allowed_extensions[i]==file_extension)
        {
            return true; // valid file extension
        }
    }

    return false;
}
</script>

<?php
if(!$valBids) {
	$mayVal=$mBase; 
    $valBids=$mBase;
	}
	
if($bidsCompra=='COMPRA') $mayVal=$valBids-$unidad;
else $mayVal=$valBids+$unidad;
//echo $mayVal."#".$valBids."#".$bidsCompra."#".$monto_ofertado;

if($bidsCompra=='COMPRA')
{
		echo '<form name="formBids" enctype="multipart/form-data"  class="formLabel">Su oferta es: '.$orig_monto_ofertado.' + el factor de ajuste asciende a: '.$monto_ofertado.', oferta realizada en fecha y a horas:'.date('d-m-Y H:i:s').'.<br><br>'
                        . ' <br><br>'
                        . 'Adjuntar Documento Oferta: '
                        . '<br /><br /><br /><br /><input name="docTec" onchange="validate_fileupload(this.value);" id="docTec" type="file" /><br /><br /><br /><br />'
                        . '<p><a href="#" onclick="return bidsLoad();" class="addcart">Confirmar</a> o <a href="Cerrar" onclick="$.facebox.close();return false;">Cancelar</a></p></form><br>'
                        . 'Por favor confirmar los datos de la oferta. <br><br>';
       //}
}else
{
		echo '<form name="formBids" class="formLabel">Su oferta es: '.$orig_monto_ofertado.', oferta realizada en fecha y a horas:'.date('d-m-Y H:i:s').'.<br><br>'
                        . '<br><br>'
                        . 'Adjuntar Documento Oferta: '
                        . '<br /><br /><br /><br /><input name="docTec" onchange="validate_fileupload(this.value);" id="docTec" type="file" /><br /><br /><br /><br />'
                        . '<p><a href="#" onclick="return bidsLoad();" class="addcart">Confirmar</a> o <a href="Cerrar" onclick="$.facebox.close();return false;">Cancelar</a></p></form><br>'
                        . 'Por favor confirmar los datos de la oferta. <br><br>';
	
       // }
  
}
?>