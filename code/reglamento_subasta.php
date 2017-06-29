<?php
include_once("../core/admin.php");
admin::initializeClient();

$_keycode = SymmetricCrypt::Decrypt(admin::getParam("_keycode"));
$uidClient = admin::getSession("uidClient");
//echo $token."<br>".$_keycode."<br>".$uidClient."<br>";
$valTerminos = admin::getDbValue("select count(*) from mdl_terminos where ter_sub_uid=$_keycode and ter_cli_uid=$uidClient");
//echo $valTerminos;
if ($valTerminos==0)
{	
?>
<script language="javascript" type="text/javascript">
function iagree()
{
	var n = $("input:checked").length;
	if(n>0)
	{
		$.ajax({
		   type: "POST",
		   url: "<?=$domain?>/code/iagree.php",
		   data: "_keycode=<?=$_keycode?>&",
		   success: function(msg){
			 		$("#continue").fadeIn("slow");
		   }
		 });
	}
	else $("#continue").hide();
}
</script>
<h2> REGLAMENTO DE LA COMPRA ELECTR&Oacute;NICA</h1><br />
<table>
<tr>
    <td height="35px" align="left">
Para poder ingresar a la compra debe estar de acuerdo con el siguiente reglamento:  <br>  
        </td>
</tr>
<tr>
<td>
<?php
					$sql = "SELECT * FROM mdl_product, mdl_subasta, mdl_pro_category WHERE sub_uid=pro_sub_uid and sub_pca_uid=pca_uid and sub_delete=0 and sub_uid=$_keycode ";
					$db->query($sql);
					$details = $db->next_record();
					$extension = admin::getExtension($details["pro_document"]);
					$imgextension = admin::getExtensionImage($extension);
					if ((strlen($imgextension)>0)&&(strlen($details["pro_document"])>0)) { ?>
                    <p>Reglamento espec&iacute;fico del proceso <?=$details["pro_name"]?>:
				  <a href="<?=PATH_DOMAIN?>/docs/subasta/<?=$details["pro_document"]?>" target="_blank"><img border="0" src="<?=PATH_DOMAIN?>/<?=$imgextension?>" width="16" height="16"/><!-- <?=$details["pro_document"]?>--></a></p><?php } ?><br><br>
</td>
</tr>
<tr>
    <td align="left">
        <form name="formBids" class="formLabel">
        <p><input  type="checkbox" style="border:0" name="aceptar" id="aceptar" onclick="iagree();">Estoy de acuerdo   
		<br><br></p><p><a href="" id="continue" style="display:none;" class="addcart">Continuar</a> <!--o <a href="Cerrar" onclick="$.facebox.close();return false;">rechazar</a>--></p></form>
        
         </td>
</tr>
</table>
<?php
}else
{
?>
<script type="text/javascript">
top.location.href = '<?=$token?>'; 
</script>
<?php
	}
?>