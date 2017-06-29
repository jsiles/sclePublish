<div id="content">
				<div id="box7" class="box-style">
                	
					<!-- info de productos -->
                  	<?php
                                        $cli_uid=  admin::getSession("uidClient");
                                           
					$sSQL ="select xit_uid, xit_sub_uid, xit_product, xit_description, xit_image, xit_price, xit_unity from mdl_xitem, mdl_clixitem where clx_xit_uid=xit_uid and xit_sub_uid= ".$details["sub_uid"]." and xit_delete=0 and clx_delete=0 and clx_cli_uid=".admin::getSession("uidClient");
					$db2->query($sSQL);
					//echo $sSQL;die;
					while($xitem=$db2->next_record())
					{
					?> 
                    <div class="title">
						<h2><span><?=utf8_encode($xitem["xit_product"])?></span></h2>
					</div>
                    <div class="item-box">
                    <p style="margin-left:<?=$maxAncho?>px;">
                    <?php
					if(file_exists(PATH_ROOT.'/img/subasta/img_'.$xitem["xit_image"]))
					{
					?>
                    <img src="<?=$domain.'/img/subasta/img_'.$xitem["xit_image"]?>" class="alignleft" alt="<?=utf8_encode($xitem["xit_product"])?>" title="<?=utf8_encode($xitem["xit_product"])?>"/>
                    <?php
					}
					?>
                    </p>
                    	<div id="subastaDetail" class="details">
                                <?php
//echo $timeSubasta;
								        $bidsCompra=admin::getDBvalue("SELECT sub_type FROM mdl_subasta where sub_uid=".$xitem["xit_sub_uid"]);
									if($bidsCompra=='COMPRA') 
                                                                        {
									$valBids=admin::getDBvalue("SELECT min(bid_mountxfac) FROM mdl_biditem where bid_xit_uid='".$xitem["xit_uid"]."'");
									$valBidsCli=admin::getDBvalue("SELECT min(bid_mountxfac) FROM mdl_biditem where bid_xit_uid='".$xitem["xit_uid"]."' and bid_cli_uid=$cli_uid");
                                                                        }
									else{
									$valBids=admin::getDBvalue("SELECT max(bid_mountxfac) FROM mdl_biditem where bid_xit_uid='".$xitem["xit_uid"]."'");
									$valBidsCli=admin::getDBvalue("SELECT max(bid_mountxfac) FROM mdl_biditem where bid_xit_uid='".$xitem["xit_uid"]."' and bid_cli_uid=$cli_uid");
                                                                        }								
									$factor = admin::getDbValue("select inc_ajuste from mdl_incoterm where inc_delete=0 and inc_cli_uid=".admin::getSession("uidClient")." and inc_sub_uid=".$xitem["xit_sub_uid"]);
                                                                        
									if(!$valBids) 
								    {
										$montoGlobal=$xitem["xit_price"];
										$valBids=$xitem["xit_price"];
                                                                                }
									else
									{
										$montoGlobal=$valBids;
                                                                                }
								
									
									
								?>
                            <p class="left">Precio: <?=$moneda?>&nbsp;</p>
                            <div id="p_<?=$xitem["xit_uid"]?>" class="left"><?=  admin::numberFormat($montoGlobal)?></div> <div class="clear"></div>
                                   <?php
                                   if($factor>0)
								   {
								   ?>
                                    <p class="left"> Factor de ajuste:<?=$factor?>%</p>
 
                                    <div class="clear"></div>
                                    <?php
								    }
                                                                    
                                    				   ?>
                                   <p class="left" style="color:#00F" id="mensajeWin_<?=$xitem["xit_uid"]?>">
                                    </p>
                                    <br />
                                    <div class="clear"></div>
                                    <p class="left ronda">Ronda:&nbsp;<?=$wheel?>
                                    </p>
                                    <br>
                                    <br>
                                    <p class="left tiempoRestante" style="display:">Tiempo de inicio:&nbsp; 
 </p>
 <div id="defaultCountdown_<?=$xitem["xit_uid"]?>" class="defCountDown" ></div>
 

 <p id="tiempoSubasta_<?=$xitem["xit_uid"]?>" class="left tiempoSubasta" style="display:none">
    Tiempo para la compra:&nbsp;</p>
 <div id="defaultCountdown_<?=$xitem["xit_uid"]?>" class="defCountDown1 subastandose" style="display:none"></div>
 
 <p class="left mensaje" style="display:none;"><?=$mensaje?></p>
	<form name="frmContact" id="formA" action="" method="post">
	<div class="subastaP" style="display:none;">
	<label class="bold">Oferta:</label>
	<input name="ct_value_<?=$xitem["xit_uid"]?>" id="ct_value_<?=$xitem["xit_uid"]?>" type="text" size="15" class="inputB" value="" onKeyUp="valOfertIt(<?=$xitem["xit_uid"]?>);"/>
        
        <a href="<?=$domain?>/code/bidsIt.php?sub_uid=<?=$xitem["xit_sub_uid"]?>&ofert=<?=$valBids?>&uid=<?=$xitem["xit_uid"]?>" id="planCuentas_<?= $xitem["xit_uid"] ?>" rel="facebox" class="addcart">Aceptar</a> (Ingrese <?php 
		if($bidsCompra=='COMPRA')
		{
		//if($xitem["xit_price"]<=$valBids) echo $moneda.' '.number_format(round(($xitem["xit_price"]-$xitem["xit_unity"]),2),2).' o menos)'; 
		//else
                    echo ' un monto menor al precio referencial)'; 
		
			}
		else
		{
		//if($xitem["xit_price"]>=$valBids) echo $moneda.' '.number_format(round(($xitem["xit_price"]+$xitem["xit_unity"]),2),2).' o m&aacute;s)'; 
		//else 
                echo ' un monto mayor al precio referencial)'; 
			}
		?>
        </div>
      
            <p class="unidadmejora"><label class="bold">Unidad de Mejora:</label> <?=$moneda?> <?=admin::numberFormat($xitem["xit_unity"])?></p>
                                
           <input type="hidden" name="hOk_<?=$xitem["xit_uid"]?>" id="hOk_<?=$xitem["xit_uid"]?>" value="" />
           <input type="hidden" name="cli_uid_<?=$xitem["xit_uid"]?>" id="cli_uid_<?=$xitem["xit_uid"]?>" value="<?=$cli_uid?>" />
            <input type="hidden" name="domain_<?=$xitem["xit_uid"]?>" id="domain_<?=$xitem["xit_uid"]?>" value="<?=$domain?>" />
            <input type="hidden" name="uid_<?=$xitem["xit_uid"]?>" id="uid_<?=$xitem["xit_uid"]?>" value="<?=$xitem["xit_uid"]?>" />
            <input type="hidden" name="sub_uid_<?=$xitem["xit_uid"]?>" id="sub_uid_<?=$xitem["xit_uid"]?>" value="<?=$xitem["xit_sub_uid"]?>" />
            <input type="hidden" name="round_<?=$xitem["xit_uid"]?>" id="round_<?=$xitem["xit_uid"]?>" value="<?=$wheel?>" />
</form>
                    </div>
                    
                    
                    								</div>
                     <div class="content">
                     <?=$xitem["xit_description"]?><br /><br />
                     </div>
                                                    
					<?php
					}
					?>
                    <!-- info de productos -->
                    <div class="content">
                    <?php
					$extension = admin::getExtension($details["pro_document"]);
					$imgextension = admin::getExtensionImage($extension);
					?>
                    <?php
					 if ((strlen($imgextension)>0)&&(strlen($details["pro_document"])>0)) { ?>
                    <p>Reglamento espec&iacute;fico del proceso:
				  <a href="<?=$domain?>/docs/subasta/<?=$details["pro_document"]?>" target="_blank"><img border="0" src="<?=$domain."/".$imgextension?>" width="16" height="16"/><!-- <?=$details["pro_document"]?>--></a></p><?php } ?>	
						<p><?=utf8_encode($details["pro_description"])?></p>
					</div>
				</div>`
                
			</div>
	