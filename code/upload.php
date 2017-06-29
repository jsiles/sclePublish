<?php
include_once("../core/admin.php");
admin::initializeClient();
$sub_uid=admin::getParam("sub_uid");
$cli_uid=admin::getParam("cli_uid");
$uid=admin::getParam("uid");

 if ( 0 < $_FILES['file']['error'] ) {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    }
    else {
        $extensionFile = strtolower(admin::getExtension($_FILES['file']['name']));
        move_uploaded_file($_FILES['file']['tmp_name'], PATH_ROOT.'/docs/subasta/ET_'.$sub_uid.'_'.$cli_uid.'_'.$uid.".".$extensionFile);
        copy( PATH_ROOT.'/docs/subasta/ET_'.$sub_uid.'_'.$cli_uid.'_'.$uid.".".$extensionFile,  PATH_PUBLIC.'/docs/subasta/ET_'.$sub_uid.'_'.$cli_uid.'_'.$uid.".".$extensionFile);
        admin::getDbValue("update mdl_biditem set bid_doc='".'ET_'.$sub_uid.'_'.$cli_uid.'_'.$uid.".".$extensionFile."' where bid_sub_uid=$sub_uid and bid_cli_uid=$cli_uid and bid_xit_uid=$uid");
    }
?>