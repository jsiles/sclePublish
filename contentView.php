<?php 
include_once("core/admin.php");
//echo 1; 
define("SYS_LANG","es");
$uidClient = admin::getSession("uidClient");
$con_uid=0;

$Category=admin::getDBvalue("select col_title from mdl_contents_languages where col_uid=2");
$CategoryUrl=admin::getDBvalue("select col_url from mdl_contents_languages where col_uid=2");

$urlARRAY = admin::urlArray();
//print_r($urlARRAY);//die;

if (!isset($langSession)) $langUrl=admin::getDBvalue("select lan_code FROM sys_language where lan_code='".admin::toSql($urlARRAY[$urlPositionTitle],'String')."'");
else $langUrl=$langSession;
if ($langUrl)
{
		$lang=$langUrl;  
		admin::setSession("LANG",$langUrl);
		$urlLangAux=$lang.'/';
}
else
{
		$lang='es';
		admin::setSession("LANG",$lang);
		$urlLangAux='';
}

if ($lang=='es')
{

$urlTitle = admin::toSql($urlARRAY[$urlPositionTitle],'String');
$urlSubTitle = (!isset($urlARRAY[$urlPositionSubtitle]))?null:admin::toSql($urlARRAY[$urlPositionSubtitle],'String');
$urlSubTitle2 = (!isset($urlARRAY[$urlPositionSubtitle2]))?null:admin::toSql($urlARRAY[$urlPositionSubtitle2],'String');
$urlSubTitle3 = (!isset($urlARRAY[$urlPositionSubtitle2]))?null:admin::toSql($urlARRAY[$urlPositionSubtitle3],'String');
}
else
{
$urlTitle = admin::toSql($urlARRAY[$urlPositionSubtitle],'String');
$urlSubTitle = admin::toSql($urlARRAY[$urlPositionSubtitle2],'String');
$urlSubTitle2 = admin::toSql($urlARRAY[$urlPositionSubtitle3],'String');
}	

// se envia la busqueda por url , se le agrega a la busqueda con un igual la variable buscada la siguiente seccion separa el con_uid y la var de busqueda
$urlSearch=admin::getDBvalue("select col_url from mdl_contents_languages where col_status='ACTIVE' and col_con_uid=21");
//echo $urlSearch."search";die;
$countWord=strlen($urlSearch)+1;
$urlSearch2=substr($urlTitle,0,$countWord);
if ($urlSearch.'='==$urlSearch2) {$varSearch=str_replace($urlSearch.'=',"",$urlTitle);  $urlTitle=$urlSearch;} 
//////////////////// ***************************fin busqueda variable************************************/////////////////////////////////////
//print_r($urlTitle);
$urlTitle = admin::strip($urlTitle);
if ($urlTitle!='')
    {
  	if ($urlTitle!='') 
		{ 
		$sql = "SELECT TOP 1 con_uid, col_con_uid, col_title
				FROM mdl_contents 
				left join mdl_contents_languages on (con_uid=col_con_uid) 
				WHERE con_delete<>1 and 
					  col_status='ACTIVE' and 
					  col_language='".$lang."' and 
					  col_url='".admin::toSql($urlTitle,"Text")."' 
				";
		$db->query($sql);

               
		$registro = $db->next_record();
		$uid = $registro["col_con_uid"];
		$con_uid_img = $registro["con_uid"];
		$titlecontentroot = $registro["col_title"];
                $sql1 = "SELECT count(*)
				FROM mdl_contents 
				left join mdl_contents_languages on (con_uid=col_con_uid) 
				WHERE con_delete<>1 and 
					  col_status='ACTIVE' and 
					  col_language='".$lang."' and 
					  col_url='".admin::toSql($urlTitle,"Text")."' 
				";
		$numpress = $db->numrows($sql1);		
//echo $uid;die;
                if(is_numeric($urlTitle)) $uid=0;
if(!isset($uid)) $uid=66;
		}
		//else $uid=66;
	  //echo $uid."####";die;
	  switch($uid){
		
		case 0:
			$urlTitle=''; 
			$page = "index.php";
			$con_uid=$uid; 
			break; 
		case 1: 
			$urlTitle=''; 
			$page = "categoriasList.php";
			$con_uid=$uid; 
			break;
		case 2:
                       //echo "categorias".$urlSubTitle.$urlSubTitle2;die;
				$con_uid=$uid;
				$page= "subastasList.php";
				if ($urlSubTitle2) 
					{
                                           // echo "@@@" . $urlSubTitle2;die;
						if (is_numeric($urlSubTitle2))
						{
						$page_list = $urlSubTitle2;						
						}
						else
						{
						$sql = "SELECT TOP 1 pro_uid FROM mdl_product WHERE pro_url='".$urlSubTitle2."' ";
						$numpress = $db->numrows($sql);
                                                
                                                $db->query($sql);
						//echo $sql;die;
						$registro = $db->next_record();
						$uid = $registro["pro_uid"];
						$parent = 3;
						$con_uid=$uid;
						$page= "subastasList.php";
						}
					$_GET['_pagi_pg']=$page_list;
					}
				elseif ($urlSubTitle) 
					{
					$page= "categoriasList.php";
					$con_uid=$uid;
					}
                                else {	{
					$page= "categoriasList.php";
					$con_uid=$uid;
					}
                                }        
				$con_uid=$uid;
				break;
		case 3: 
			$urlTitle=''; 
			$page = "registro.php";
			$con_uid=$uid; 
			break;	
		case 4: 
			//echo $urlTitle."##".$urlSubTitle."#1#".$urlSubTitle2."#2#".$urlSubTitle3."#3#";
			$urlTitle=''; 
			if($urlSubTitle2)
			$page = "divisasList.php";
			else
			$page = "categoriasList.php";
			$con_uid=$uid; 
			break;		
		default: 
                    header("HTTP/1.0 403 Forbidden");exit;
				/*$con_uid=$uid;
				$page="session.php";
				if ($urlSubTitle) 
					{
						if (is_numeric($urlSubTitle))
						{
						$page_list = $urlSubTitle;						
						}
						else
						{
						$sql = "SELECT TOP 1 new_uid FROM mdl_news_languages,mdl_news 
						WHERE nel_new_uid=new_uid and nel_language='".$lang."' AND 
						nel_status='ACTIVE' AND new_delete=0 and nel_url='".$urlSubTitle."' ";
						$numpress = $db->numrows($sql);
						
                                                $db->query($sql) ;
						$registro = $db->next_record();
						$uid = $registro["new_uid"];
							if (!$uid){
							$urlTitle=''; 
							$page = "index.php";
							$con_uid=1; 
							}
							else
							{	
							$parent = 3;
							$con_uid=$uid;
							$page= "code/interior.php";
							}
						}
					$_GET['_pagi_pg']=$page_list;
					}
				$con_uid=$uid;*/
				break;
	}
        //echo $uid." ".$page;die;
 	include_once($page);        
	}	
else
	{
	header("HTTP/1.0 403 Forbidden");exit;
	}
?>