<?php
include_once("core/admin.php");

//templates a usar******************************************************************************************
$tpl = new TemplatePower (PATH_TEMPLATE."/paquetes.tpl");
$tpl->assignInclude( "header",PATH_TEMPLATE."header.tpl");	
$tpl->assignInclude( "userLogin",PATH_TEMPLATE."userLogin.tpl");
$tpl->assignInclude( "footer",PATH_TEMPLATE."footer.tpl");
$tpl->assignInclude( "menu",PATH_TEMPLATE."menu.tpl");
$tpl->assignInclude( "column",PATH_TEMPLATE."column3.tpl");
	
$tpl -> prepare (); 
//php para templates****************************************************************************************	
include_once("code/header.php");
include_once("code/userLogin.php");
include_once("code/menu.php");
include_once("code/footer.php");
include_once("code/column3.php");

// seo de la pagina ****************************************************************************************
$sql = "select * from mdl_contents left join mdl_contents_languages on (con_uid=col_con_uid) where 	con_delete<>1 and col_status='ACTIVE' and col_language='es' and con_uid=22 limit 1";
$db->query($sql) ;
$content_details = $db->next_record();

if ($content_details["col_metatitle"]) $seo=$content_details["col_metatitle"]. ' > ';
$seo.=admin::SEO($uid).admin::labelsFront('title');
$tpl->assignGlobal("domain",$domain);
$tpl->assignGlobal("metadescription",$content_details["col_metadescription"]);
$tpl->assignGlobal("metakeyword",$content_details["col_metakeyword"]);
$tpl->assignGlobal("metatitle",$seo);

// combo de paquetes*****************************************************************************************
$sql2="SELECT mcc_uid, mcc_permit FROM mdl_client_category";	
$db2->query($sql2);
while($row = $db2->next_record())
{
	$tpl->newBlock("paquete");
	$tpl->assign("mcc_uid",$row["mcc_uid"]);
	$tpl->assign("mcc_permit",$row["mcc_permit"]);
	$tpl->gotoBlock( "_ROOT" );
}

// labels del formulario de clientes anf**********************************************************************
$tpl->assignGlobal("name",admin::labelsFront('login2'));
$tpl->assignGlobal("firstname",admin::labelsFront('name'));
$tpl->assignGlobal("lastname",admin::labelsFront('lastname'));
$tpl->assignGlobal("password",admin::labelsFront('pass'));
$tpl->assignGlobal("email",admin::labelsFront('email'));
$tpl->assignGlobal("phone",admin::labelsFront('phone'));
$tpl->assignGlobal("fax",admin::labelsFront('fax'));
$tpl->assignGlobal("cellular",admin::labelsFront('movil'));
$tpl->assignGlobal("address",admin::labelsFront('address'));
$tpl->assignGlobal("photo",admin::labelsFront('photo'));
$tpl->assignGlobal("send",admin::labelsFront('send'));
$tpl->assignGlobal("register",admin::labelsFront('register'));

// para las rutas de las url de las noticias******************************************************************
$parentB=admin::getDBvalue("select col_url from mdl_contents left join mdl_contents_languages on (con_uid=col_con_uid) where con_delete<>1 and col_status='ACTIVE' and con_uid=12 order by con_position limit 1");
$tpl->assign("parentB",$parentB);

$parentO=admin::getDBvalue("select col_url from mdl_contents left join mdl_contents_languages on (con_uid=col_con_uid) where con_delete<>1 and col_status='ACTIVE' and con_uid=13 order by con_position limit 1");
$tpl->assign("parentO",$parentO);

$parentF=admin::getDBvalue("select col_url from mdl_contents left join mdl_contents_languages on (con_uid=col_con_uid) where con_delete<>1 and col_status='ACTIVE' and con_uid=19 order by con_position limit 1");
$tpl->assign("parentF",$parentF);

$childB0=admin::getDBvalue("select col_url from mdl_contents left join mdl_contents_languages on (con_uid=col_con_uid) where con_delete<>1 and col_status='ACTIVE' and con_uid=14 order by con_position limit 1");
$tpl->assign("childB0",$childB0);

$childB1=admin::getDBvalue("select col_url from mdl_contents left join mdl_contents_languages on (con_uid=col_con_uid) where con_delete<>1 and col_status='ACTIVE' and con_uid=15 order by con_position limit 1");
$tpl->assign("childB1",$childB1);

// datos del banner top *******************************************************************************
$countBanner=admin::getDBvalue("select count(ban_title) from mdl_banners, mdl_banners_contents where mbc_ban_uid=ban_uid and mbc_con_uid='".$con_uid."' and mbc_delete=0 and mbc_place=0 and mbc_status='ACTIVE' order by mbc_position asc");
if($countBanner>0){
	$tpl->assign("noBanner",'');
	$sql = "select ban_content,ban_url,ban_title from mdl_banners, mdl_banners_contents where mbc_ban_uid=ban_uid and mbc_con_uid='".$con_uid."' and mbc_delete=0 and mbc_place=0 and mbc_status='ACTIVE' order by mbc_position asc";
	$db->query($sql) ;
	$cate = $db->next_record();
	
	if($cate["ban_url"]){
		$tpl->newBlock("bannerTopHc");
		$tpl->assign("bannerUrl",$cate["ban_url"]);
		$tpl->assign("bannerTitle",$cate["ban_title"]);
		$tpl->assign("bannerContent",$cate["ban_content"]);
	}
	else {
		$tpl->newBlock("bannerTopHs");
		$tpl->assign("bannerTitle",$cate["ban_title"]);
		$tpl->assign("bannerContent",$cate["ban_content"]);
	}
$tpl->gotoBlock( "_ROOT" );
}
else $tpl->assign("noBanner",'none');
$tpl -> printToScreen ();
?>