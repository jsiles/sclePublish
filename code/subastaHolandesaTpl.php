		<div id="content">
				<div id="box7" class="box-style">
                  
					<div class="title">
						<h2><span><?=$details["pro_quantity"]." ".utf8_encode($details["pro_unidad"])." ".utf8_encode($details["pro_name"])?></span></h2>
					</div>
					<div class="item-box">
                    <p style="margin-left:<?=$maxAncho?>px;">
                    <?php
					if(file_exists(PATH_ROOT.'/img/subasta/img_'.$details["pro_image"]))
					{
					?>
                    <img src="<?=$domain.'/img/subasta/img_'.$details["pro_image"]?>" class="alignleft" alt="<?=utf8_encode($details["pro_name"])?>" title="<?=utf8_encode($details["pro_name"])?>"/>
                    <?php
					}
					?>
                    </p>
                    	<div id="subastaDetail" class="details">
                                <?php
//echo $timeSubasta;
								   $bidsCompra=admin::getDBvalue("SELECT sub_type FROM mdl_subasta where sub_uid=".$details["sub_uid"]);
									if($bidsCompra=='COMPRA') 
									$valBids=admin::getDBvalue("SELECT max(bid_mountxfac) FROM mdl_bid where bid_pro_uid='".$details["pro_uid"]."' and bid_cli_uid!=0");
									else
									$valBids=admin::getDBvalue("SELECT min(bid_mountxfac) FROM mdl_bid where bid_pro_uid='".$details["pro_uid"]."' and bid_cli_uid!=0");
									
									$factor = admin::getDbValue("select inc_ajuste from mdl_incoterm where inc_delete=0 and inc_cli_uid=".admin::getSession("uidClient")." and inc_sub_uid=".$details["sub_uid"]);
									//$regBids = admin::getDbValue("select count(*) from mdl_bid where bid_sub_uid = ".$details["sub_uid"]);
									
									if(!$valBids) 
								    {
										$centavos='00';//substr($mountBase[$m],-3);
										$montoGlobal=$mountBase[$m];
										$valBids=$mountBase[$m];
										}
									else
									{
										$centavos='00';//substr($valBids,-3);
										$montoGlobal=$valBids;
										}
								
									//$centavos=str_replace('.','',$centavos);
									
								?>
									<p class="left">Precio: <?=$moneda?> 
					       <?=$montoGlobal?>.<sup><?=$centavos?></sup></p> <div class="clear"></div>
                                   <?php
                                   if($factor)
								   {
								   ?>
                                    <p class="left"> Factor de ajuste:<?=$factor?>%
                                    <div class="clear"></div>
                                    <?php
								    }
								/*	if($regBids>0)
									{
									?><p class="left">
                                    Listado de pujas:<a href="<?=$domain?>/code/listBids.php?_keycode=<?=SymmetricCrypt::encrypt(base64_encode($details["sub_uid"]))?>" rel="facebox"><label style="color:#00F"><?=$regBids?></label> puja(s)</a>
                                    <div class="clear"></div>
                                   <?php
								   if(isset($regBidsWin))
								   {
									if(($regBidsWin==$regBidsWinMax)&&($details["sub_finish"]==0))
									{   
								   ?>
                                   	<p class="left" style="color:#00F">
                                    Su oferta est&aacute; ganando</p>
                                    <p style="display:none" class="rigth" id="message">
                                    felicidades su oferta ganan&oacute;</p>
                                    <div class="clear"></div>
                                    <?php
									}elseif(($regBidsWin!=$regBidsWinMax)&&($details["sub_finish"]==0))
									{
									?>
                                   	<p class="left" style="color:#00F">
                                    Su oferta est&aacute; perdiendo</p>
                                    <p style="display:none" class="rigth" id="message">
                                    lo sentimos su oferta perdi&oacute;</p>
                                    <div class="clear"></div>
                                    <?php
										
										}else
										{

									?>
                                   		<p class="left">
                                    <?=$winMessage?></p>
                                    <div class="clear"></div>
                                    <?php
											}
								   }
								   
								   }*/
                                    ?><p class="left" id="tiempoRestante" style="display:">Tiempo de inicio:&nbsp; 
 <div id="defaultCountdown" class="defCountDown"></div>
 </p>

<p class="left" id="tiempoSubasta" style="display:none">Tiempo de subasta:&nbsp;
 <div id="defaultCountdown1" class="defCountDown"></div>
 </p>

										<form name="frmContact" id="formA" action="" method="post">
		<p id="subastaP" style="display:none;">
			<label class="bold">Oferta:</label>
			<input name="ct_value" id="ct_value" type="hidden" size="15" class="inputB" value="<?=$mountBase[$m]?>"/> <a href="<?=$domain?>/code/bids2.php?uid=<?=$details["sub_uid"]?>&ofert=<?=$mountBase[$m]?>" id="planCuentas" rel="facebox" class="addcart">Aceptar</a>
        </p>
      
									  <p id="unidadmejora"><label class="bold">Unidad de Mejora:</label> <?=$moneda?> <?=$details["sub_mount_unidad"]?></p>
                                    
           <input type="hidden" name="hOk" id="hOk" value="" />
            <input type="hidden" name="domain" id="domain" value="<?=$domain?>" />
            <input type="hidden" name="uid" id="uid" value="<?=$details["sub_uid"]?>" />
</form>
                    </div>
                    
                    
                    								</div>
					
                    
                    <div class="content">
                    <?php
					$extension = admin::getExtension($details["pro_document"]);
					$imgextension = admin::getExtensionImage($extension);
					?>
                    <?php
					 if ((strlen($imgextension)>0)&&(strlen($details["pro_document"])>0)) { ?>
                    <p>Reglamento espec&iacute;fico de la subasta:
				  <a href="<?=$domain?>/docs/subasta/<?=$details["pro_document"]?>" target="_blank"><img border="0" src="<?=$domain."/".$imgextension?>" width="16" height="16"/><!-- <?=$details["pro_document"]?>--></a></p><?php } ?>	
						<p><?=utf8_encode($details["pro_description"])?></p>
					</div>
				</div>
			</div>
	