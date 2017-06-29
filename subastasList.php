<?php
include_once("core/admin.php");
admin::initializeClient();
$sql = "SELECT * FROM mdl_product, mdl_subasta, mdl_pro_category WHERE sub_uid=pro_sub_uid and sub_pca_uid=pca_uid and sub_delete=0 and sub_finish in(1,2) and pro_url='".$urlSubTitle2."'";
//echo $sql;
$db->query($sql);
$details = $db->next_record();
$moneda = admin::getDbValue("select cur_description from mdl_currency where cur_uid='".$details["sub_moneda"]."'");

//echo $details["sub_modalidad"];die;
if($details){
switch($details["sub_modalidad"])
{
	case "TIEMPO": 
					include_once("subastaxtiempo.php");
					break;
	case "HOLANDESA":
					include_once("subastaHolandesa.php");
					break;
	case "JAPONESA":
					include_once("subastaJaponesa.php");
					break;		
	case "ITEM":
					include_once("subastaxItem.php");
					break;		
        case "PRECIO":
					include_once("subastaxPrecio.php");
					break;		                            
	default:
			echo "Modalidad de subasta no soportada";
                        break;
}
}else{
    header("Location:". PATH_DOMAIN);   
}
//echo $details["sub_modalidad"];
?>
