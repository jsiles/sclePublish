<?php
$uidClient = admin::getSession("uidClient");
if($uidClient){
$sql = "select cli_socialreason
      ,cli_interno
      ,cli_user
      ,cli_mainemail
      ,cli_legalname
      ,cli_legallastname
	  ,cli_pass_change
      ,cli_mainemail from mdl_client where cli_uid=$uidClient";
$db->query($sql);

$client = $db->next_record();

if($client["cli_pass_change"]==0) $msgP='Debe cambiar la contrase&ntilde;a';
else  $msgP='';
}
?>
<div id="content">
				<div id="box6" class="box-style">
					<div class="title">
                    <h2><span>Cambio de contrase&ntilde;a</span></h2>
					</div>
					<div class="content">
						<p><?=$msgP?></p>
						<form name="formLabel" id="formLabel" class="formLabel" autocomplete="off" method="post">
                        <p>
                            <label>Nueva Contrase&ntilde;a:</label><input name="pass" id="pass" type="password" value="" /> 
                        </p>
                        
                        <p>
                            <label>Re-escriba la nueva contrase&ntilde;a:</label><input name="pass2" id="pass2" type="password" value="" /> 
                        </p>
                        
                        <div class="clear"></div>
                          <input name="idUser" id="idUser" type="hidden" value="<?=$uidClient?>">
                        <a href="" onclick="altas('<?=$domain?>');return false;" class="addcart">Continuar</a>
						<div class="clear"></div><label id="message" class="red"></label>
						</form>
                    </div>
				</div>
			</div>