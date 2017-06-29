<?php
include_once("../core/admin.php");
admin::initializeClient();
$deadTime = admin::getParam("deadTime");
$year = substr($deadTime,0,4);
$month = substr($deadTime,5,2);
$day = substr($deadTime,8,2);
$hora = substr($deadTime,11,2);
$minuto = substr($deadTime,14,2);
$segundo = substr($deadTime,17,2);
$timeSubasta = admin::getParam("timeSubasta");
$deadTime1 = date("Y-m-d H:i:s", mktime($hora,$minuto+$timeSubasta
,$segundo, $month, $day, $year));
$sub_uid = admin::getParam("sub_uid");
$db->query("select * from mdl_subasta where sub_uid=$sub_uid");
$subastaDList = $db->next_record();
$pro_uid=admin::getDbValue("SELECT pro_uid FROM mdl_product where pro_sub_uid=$sub_uid");
$catUid= $subastaDList["sub_pca_uid"]; //admin::getDbValue("SELECT sub_pca_uid FROM mdl_subasta where sub_uid=$sub_uid");

$unidadMejora=$subastaDList["sub_mount_unidad"];//admin::getDbValue("SELECT sub_mount_unidad FROM mdl_subasta where sub_uid=$sub_uid");

$mountBase=$subastaDList["sub_mount_base"];//admin::getDbValue("SELECT sub_mount_base FROM mdl_subasta where sub_uid=$sub_uid");

$bidsCompra=$subastaDList["sub_type"];//admin::getDbValue("SELECT sub_type FROM mdl_subasta where sub_uid=$sub_uid");
$numberRounds =$subastaDList["sub_wheels"];
$wheel = admin::getParam("wheel");
//echo $wheel. " - ".$numberRounds;
if($wheel<=$numberRounds)
{
    $sql = "update mdl_round set rou_flag0=1 where  rou_datetime<GETDATE()  and rou_sub_uid=$sub_uid";
    $db->query($sql);
    $sql = "update mdl_round set rou_flag0=0 where  rou_datetime<GETDATE()  and rou_sub_uid=$sub_uid";
    $db->query($sql);  
    echo "reload";
}  else {
 echo 1;   
}


//echo "numero ruedas $numberRounds";


/*
 * 
 $maxBidRound=0;
 
$maxBidRound=admin::getDbValue("select max(bid_flag0) from mdl_biditem where bid_pro_uid=$pro_uid");
if(!isset($maxBidRound)) $maxBidRound=0;
$oMaxBidRound=$maxBidRound;
$valWheelFlag= admin::getDbValue("select count(*) from mdl_biditem where bid_sub_uid=$sub_uid and bid_flag0=0");
if($valWheelFlag>0)
{											
										if($bidsCompra=='COMPRA') 
										$valBids=admin::getDbValue("SELECT min(bid_mountxfac) FROM mdl_biditem where bid_pro_uid=$pro_uid and bid_cli_uid!=0");
										else
										$valBids=admin::getDbValue("SELECT max(bid_mountxfac) FROM mdl_biditem where bid_pro_uid=$pro_uid and bid_cli_uid!=0");
	
		
										if(!$valBids) 
										{
												//						$valBids=$mountBase;
												$sql="update mdl_subasta set sub_finish=1 where sub_uid=$sub_uid";
		//echo 1;
												$db->query($sql);
												echo 1;
											}
											else
											{
												$maxBidRound=$maxBidRound+1;
												$maxBidRound2=$maxBidRound;
												if($maxBidRound<=$numberRounds)
												{
												$db->query("update sys_item set ite_flag=1 where ite_sub_uid=$sub_uid and ite_wheel=$maxBidRound");
												$nRound=$maxBidRound+1;
												$db->query("insert into sys_item values (null, $sub_uid, $nRound, 0)");
												
												$sql="update mdl_biditem set bid_flag0=$maxBidRound where bid_pro_uid=$pro_uid and bid_flag0=0";
												$db->query($sql);
												$sSQL="select * from mdl_biditem where bid_pro_uid=$pro_uid and bid_cli_uid!=0 and bid_flag0=$maxBidRound order by bid_uid desc ";
												//echo $sSQL;
												$db->query($sSQL);
												while($bidsVal = $db->next_record())
												{
													$sql="insert into mdl_bid values(null, $sub_uid, $pro_uid, ".$bidsVal["bid_cli_uid"].", ".$bidsVal["bid_mountxfac"].", ".$bidsVal["bid_mountxfac"].", '".$deadTime."',$catUid)";
													//echo $sql;
													$db2->query($sql);
													//$db2->query("insert into sys_log values (null,'".admin::toSql($sql.":maxbidR:".$maxBidRound.":maxbidR2:".$maxBidRound2.":cant:".$cantBids,"Text")."')");
	
												}
												if($maxBidRound==$numberRounds)
													{
													$sql="update mdl_subasta set sub_finish=1 where sub_uid=$sub_uid";
													$db->query($sql);
													echo 1;
														//$db2->query("insert into sys_log values (null,'".admin::toSql($sql.":maxbidRE1:".$maxBidRound.":maxbidRE12:".$maxBidRound2.":cant:".$cantBids,"Text")."')");
													}
													else{
														$sql="update mdl_subasta set sub_deadtime='".$deadTime1."' where sub_uid=$sub_uid";
														$db->query($sql);
														echo 3;
														//echo "select count(*) from mdl_biditem where bid_sub_uid=$sub_uid and bid_flag0=$maxBidRound#";
														//echo "cantidad:".$cantBids."#R1#".$maxBidRound."#R2#".$maxBidRound2; 
														//$db2->query("insert into sys_log values (null,'".admin::toSql($sql.":maxbidR:".$maxBidRound.":maxbidR2:".$maxBidRound2.":cant:".$cantBids,"Text")."')");
														
														}
												}else
												{
													$sql="update mdl_subasta set sub_finish=1 where sub_uid=$sub_uid";
													$db->query($sql);
													echo 1;
														//$db2->query("insert into sys_log values (null,'".admin::toSql($sql.":maxbidRE:".$maxBidRound.":maxbidRE2:".$maxBidRound2.":cant:".$cantBids,"Text")."')");
	
													
												}
												
											}
	//$sql="insert into mdl_bid values(null, $sub_uid, $pro_uid, 0, $valBids, '".$deadTime."',$catUid)";
	
//	$cantBids=admin::getDbValue("select count(*) from mdl_biditem where bid_sub_uid=$sub_uid and bid_flag0=$maxBidRound");
	//$valBids -= $unidadMejora;
	
//	if($cantBids>1)
//	{
		
//		}
/*	else
	{
		$sql="update mdl_subasta set sub_finish=1 where sub_uid=$sub_uid";
		$db->query($sql);
	
		echo 1;
		$db2->query("insert into sys_log values (null,'".admin::toSql($sql.":maxbidR:".$maxBidRound.":maxbidR2:".$maxBidRound2.":cant:".$cantBids,"Text")."')");
	//	echo "select count(*) from mdl_biditem where bid_sub_uid=$sub_uid and bid_flag0=$maxBidRound#";
	//	echo "cantidad:".$cantBids."#R1#".$maxBidRound."#R2#".$maxBidRound2; 
		
	}

}// FIN IF(valWheelFlag)
elseif ($maxBidRound==$numberRounds)
{
	echo 3;
	}
	else{
		echo 4;
		}
//echo $sql;
*/
?>