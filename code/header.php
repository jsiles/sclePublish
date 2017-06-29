<?php
$uidClient = admin::getSession("uidClient");
if($uidClient){
$name = admin::getDbValue("select cli_socialreason from mdl_client where cli_uid=".$uidClient);
$foto = admin::getDbValue("select cli_logo from mdl_client where cli_uid=".$uidClient);
admin::updateSubasta();
}

$imgs = admin::getDbValue("select top 1 ban_file from mdl_banners_contents, mdl_banners where mbc_delete=0 and mbc_status='ACTIVE' and mbc_ban_uid=ban_uid order by mbc_position ,mbc_ban_uid");
$ban_name = admin::getDbValue("select top 1 ban_title from mdl_banners_contents, mdl_banners where mbc_delete=0 and mbc_status='ACTIVE' and mbc_ban_uid=ban_uid order by mbc_position ,mbc_ban_uid");

?>
<script language="javascript" type="text/javascript">
    var today = new Date('<?=date("d M Y G:i:s")?>');
function startTime() {
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    m = checkTime(m);
    s = checkTime(s);
    document.getElementById('clockSys').innerHTML =
    h + ":" + m + ":" + s;
    today.setSeconds(today.getSeconds()+1);
    var t = setTimeout(startTime, 1000);
}
function checkTime(i) {
    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}
</script>
<div id="top-header" class="container">
    <div id="top-banner">
                            <div id="logo" style="float:left">
				<h1><a href="<?=$domain?>" >SISTEMA ELECTR&Oacute;NICO DE ADQUISICIONES Y REGISTRO DE PROVEEDORES</a></h1>
				<p></p>
			</div>
                            
					<?php if($uidClient){?>
                <div id="colB" style="float:right">
                    <p><a href="<?=$domain?>/logout.php"></a></p>
                    <a href="<?=$domain."/registro/".$uidClient."/"?>" alt="Cambiar Contrase&ntilde;a" title="Cambiar Contrase&ntilde;a"><img width="30" src="<?=$domain."/lib/pencil.png"?>" alt="Cambiar Contrase&ntilde;a" title="Cambiar Contrase&ntilde;a" /></a>
                    <a href="<?=$domain."/registro/".$uidClient."/"?>" alt="Cambiar Contrase&ntilde;a" title="Cambiar Contrase&ntilde;a"><br />Bienvenido<br /> <?=$name?></a>
                    
                                   </div>
                <?php } ?>
    <div id="colA" style="float:center">
                    <?php if($imgs){?>
					<img src="<?=$domain?>/img/banner/img_<?=$imgs?>?<?=time()?>" alt="<?=$ban_name?>" title="<?=$ban_name?>" border="0"/>
                    <?php }?>
				</div>
				
			
		
</div>
</div>		