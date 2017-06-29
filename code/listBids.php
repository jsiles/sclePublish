<?php
include_once("../core/admin.php");
admin::initializeClient();
$_keycode=base64_decode(SymmetricCrypt::decrypt(admin::getParam("_keycode")));
$sSQL="select * from mdl_bid where bid_sub_uid=$_keycode order by bid_uid desc";
$db->query($sSQL);
?>
<h2>LISTA DE OFERTAS</h2>
<table width="100%">
<tr>
    <td height="35px" align="left">
CODIGO  
        </td>
        <td height="35px" align="right">
OFERTA 
        </td>
        <td height="35px" align="center">
FECHA Y HORA  
        </td>
</tr>
<?php
while($detail = $db->next_record())
{
?>
<tr>
    <td height="35px" align="left">
<?=substr($detail["bid_uid"],-1,1)."*****"?></td>
        <td height="35px" align="right">
<?=$detail["bid_mount"]?> 
        </td>
        <td height="35px" align="center">
<?=admin::changeFormatDate($detail["bid_date"],7)?>  
        </td>
</tr>
<?php
}
?>
<tr>
    <td align="left" colspan="3">
        <form name="formBids" class="formLabel">
		<a href="Cerrar" class="addcart" onclick="$.facebox.close();return false;">Cerrar</a></p></form>
        
         </td>
</tr>
</table>