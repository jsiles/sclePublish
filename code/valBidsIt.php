<?php
include_once("../core/admin.php");
admin::initializeClient();
$sub_type = admin::getParam("sub_type");
$sub_uid = admin::getParam("sub_uid");
$sql = "SELECT * FROM mdl_xitem,mdl_clixitem WHERE xit_uid=clx_xit_uid and clx_delete=0 and xit_delete=0 and xit_sub_uid=".$sub_uid." and clx_cli_uid=".admin::getSession("uidClient"); //and sub_deadTime>='".$deadTime."' 
$db->query($sql);
$dataRef="";
//echo $sql;
$i=0;
while($details = $db->next_record())
{
    
    if($sub_type=="COMPRA"){
        $montoRef = admin::getDbValue("select min(bid_mountxfac) from mdl_biditem where bid_xit_uid=".$details['xit_uid']);
        $regBidsWin = admin::getDbValue("select min(bid_mountxfac) from mdl_biditem where bid_xit_uid=".$details["xit_uid"]." and bid_cli_uid=".admin::getSession("uidClient"));
    
    }else{
        $montoRef = admin::getDbValue("select max(bid_mountxfac) from mdl_biditem where bid_xit_uid=".$details['xit_uid']);
        $regBidsWin = admin::getDbValue("select max(bid_mountxfac) from mdl_biditem where bid_xit_uid=".$details["xit_uid"]." and bid_cli_uid=".admin::getSession("uidClient"));
    }
    if($montoRef){
        if(!isset($regBidsWin)) {$winStatus=2;}
        else{
             if($regBidsWin==$montoRef) $winStatus=1;
             else $winStatus=0;
        }
    if($i==0)
        $dataRef.= $details['xit_uid'].";".$montoRef.";".$winStatus;
    else 
        $dataRef.="|".$details['xit_uid'].";".$montoRef.";".$winStatus;
    $i++;
    }
}
echo $dataRef;
?>            
        