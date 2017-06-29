<div id="content">
				<div id="box6" class="box-style">
					<div class="title">
						<h2><span>Productos</span></h2>
					</div>
					<div class="content">
						<!--<p>Descripci&oacute;n corta de los productos en subasta.</p>-->
						<p>&nbsp;</p>
					</div>
<?php			

                                $arrayURL = admin::urlArray();
                                //print_r($arrayURL);
                                //echo $urlPositionSubtitle;
                                $cli_uid = admin::getSession("uidClient");
                                if($arrayURL[$urlPositionSubtitle]) {
				$uidCatgory= admin::getDbValue("select pca_uid from mdl_pro_category where pca_url='".$arrayURL[$urlPositionSubtitle]."' and pca_delete=0");
				//echo "select pca_uid from mdl_pro_category where pca_url='".$arrayURL[$urlPositionSubtitle]."' and pca_delete=0";
				//echo $uidCatgory;
                                if (isset($uidCatgory)){
                                    $sWhere=" and pca_uid=$uidCatgory";
                                    }
                                }else {
					if($arrayURL[$urlPositionTitle]=='divisas') { $sWhere=" and pca_uid=6";}
					else {
						$uidFirst=admin::getDbValue("SELECT TOP 1 a.pca_uid FROM mdl_pro_category a, mdl_subasta b where b.sub_pca_uid=a.pca_uid and b.sub_status='ACTIVE' order by pca_name");
						$sWhere=" and pca_uid=$uidFirst";
						}
					}
							$sql2 = "SELECT pro_uid, sub_uid, pro_url, pro_name, pro_image, sub_description,sub_mount_base,sub_moneda,sub_moneda1,sub_hour_end, pca_url, sub_deadtime,pro_quantity, pro_unidad, sub_uid FROM mdl_product, mdl_subasta, mdl_pro_category, mdl_incoterm WHERE sub_uid=inc_sub_uid and sub_uid=pro_sub_uid and sub_pca_uid=pca_uid and sub_delete=0 and inc_delete=0 and sub_status='ACTIVE' and sub_finish in (1,2)and inc_cli_uid=$cli_uid $sWhere order by sub_hour_end asc";
                                                        //echo $sql2;
							$db2->query($sql2);
							while ($content=$db2->next_record())
							{
								$image1 = PATH_ROOT.'/img/subasta/img_'.$content["pro_image"];
								if(file_exists($image1))
								{
								list($width, $height) = getimagesize($image1);
								if ($width<132) $maxAncho=132-$width;
								else $maxAncho=0;
								}
								$timetobe=admin::time_diff($content["sub_hour_end"],date('Y-m-d H:i:s'));
								$timedead=admin::time_diff($content["sub_deadtime"],date('Y-m-d H:i:s'));
								$finish=$content["sub_finish"];
								//echo date('Y-m-d H:i:s')."#". $timetobe."#".$content["sub_hour_end"]."#".$content["sub_deadtime"]."#".$timedead;
								if (($timetobe>0)&&($finish==0)){
								//$daystobe=intval($timetobe/86400);
								//$timetobe=$timetobe-($daystobe*86400);
								$hourstobe=intval($timetobe/3600);
								$timetobe=$timetobe-($hourstobe*3600);
								$minutetobe=intval($timetobe/60);
								$timetobe=$timetobe-($minutetobe*60);
								$faltante = $hourstobe.'h '.$minutetobe.'m '.$timetobe.'s ';//$daystobe.'d '.$hourstobe.'h '.$minutetobe.'m '.$timetobe.'s ';
								}
								elseif(($timedead>0)&&($finish==0)) $faltante='Iniciada';
								else $faltante='Concluido';
								
								//$valTerminos=admin::getDbValue("select count(*) from mdl_terminos where ter_sub_uid=".$content["sub_uid"]." and ter_cli_uid=".admin::getSession("uidClient"));
								$valIcoterms=admin::getDbValue("select count(*) from mdl_incoterm where inc_sub_uid=".$content["sub_uid"]." and inc_cli_uid=".admin::getSession("uidClient"));
								//echo "select count(*) from mdl_incoterm where inc_sub_uid=".$content["pro_uid"]." and inc_cli_uid=".admin::getSession("uidClient");

								$bidsCompra=admin::getDBvalue("SELECT a.sub_type FROM mdl_subasta a where a.sub_uid=".$content["sub_uid"]);								
								//echo $content["pro_uid"].$bidsCompra."#".$valIcoterms."#";
								
								if($valIcoterms==0)
								{
								
								
									//echo $bidsCompra;
									//if($bidsCompra=='COMPRA')
									$urlLink = $domain.'/code/aviso.php" rel="facebox';
									//else $urlLink = $domain.'/'.$CategoryUrl.'/'.$content["pca_url"].'/'.$content["pro_url"].'/';
									}
									else
									{
										//$urlLink = $domain.'/'.$CategoryUrl.'/'.$content["pca_url"].'/'.$content["pro_url"].'/'.'" onclick="alert(1);return false;';
										$valTerminos = admin::getDbValue("select count(*) from mdl_terminos where ter_sub_uid=".$content["sub_uid"]." and ter_cli_uid=".admin::getSession("uidClient"));

											if($valTerminos ==0)
											{
                                                                                            //$test=$domain.'/'.$CategoryUrl.'/'.$content["pca_url"].'/'.$content["pro_url"].'/';
                                                                                            //echo $test;
                                                                                            //echo (SymmetricCrypt::Encrypt($test));
											$token = SymmetricCrypt::Encrypt($domain.'/'.$CategoryUrl.'/'.$content["pca_url"].'/'.$content["pro_url"].'/');
											$_keycode = SymmetricCrypt::encrypt($content["sub_uid"]);
											$urlLink = $domain.'/code/reglamento_subasta.php?_keycode='.urlencode($_keycode).'&" rel="facebox';
											}else
											{
											$urlLink = $domain.'/'.$CategoryUrl.'/'.$content["pca_url"].'/'.$content["pro_url"].'/';										
											}
										}
									//echo $urlLink;
                            ?>
                            <div class="item-box">
                             <?php
					if(file_exists(PATH_ROOT.'/img/subasta/img_'.$content["pro_image"]))
					{
					?>
							
								<p style="margin-left:<?=$maxAncho?>px;"><a href="<?=$urlLink?>">
                               
                                <img src="<?=$domain.'/img/subasta/img_'.$content["pro_image"]?>" border="0" class="alignleft" alt="<?=$content["pro_name"]?>" title="<?=$content["pro_name"]?>"/>
                                
                                </a></p>
                                <?php
					}
					if($arrayURL[$urlPositionTitle]=="divisas")
					{
						$unidad = $content["sub_moneda1"];
						$unidad = admin::getDbValue("select cur_description from mdl_currency where cur_uid=$unidad");
					}
					else
					$unidad =utf8_encode($content["pro_unidad"]);					
					$moneda = $content["sub_moneda"];
					$moneda = admin::getDbValue("select cur_description from mdl_currency where cur_uid=$moneda");
					
					?>
								<h2><a href="<?=$urlLink?>"><?=$content["pro_quantity"]." ".$unidad." ".utf8_encode($content["pro_name"])?></a></h2>
                                                                <p><?=$content["sub_description"]?></p>
							  <div class="details">
                                <?php
								$bidsCompra=admin::getDBvalue("SELECT a.sub_type FROM mdl_subasta a, mdl_bid b where b.bid_pro_uid='".$content["pro_uid"]."' and b.bid_sub_uid=a.sub_uid");
									if($bidsCompra=='COMPRA') 
									$valBids=admin::getDBvalue("SELECT min(bid_mountxfac) FROM mdl_bid where bid_pro_uid='".$content["pro_uid"]."'");
									else
									$valBids=admin::getDBvalue("SELECT max(bid_mountxfac) FROM mdl_bid where bid_pro_uid='".$content["pro_uid"]."'");
									if(!$valBids) 
								    {
										$montoGlobal=$content["sub_mount_base"];
										}
									else
									{
										$montoGlobal=$valBids;
										}
								 	$centavos=str_replace('.','',$centavos);
								?>
                                	<br />
                                    <p style="font-weight:bold">Fecha de la subasta: <?=$content["sub_hour_end"]?></p><div class="clear"></div>
                                    <p class="left">Tiempo restante: <?=$faltante?></p><div class="clear"></div>
                                    <p class="left">Precio: <?=$moneda." ".admin::numberFormat($montoGlobal)?></p>
									<?php
                                                                        //echo $urlLink;
									if($faltante!='Concluido')
									{
                                    ?><p class="right"><a href="<?=$urlLink?>" class="addcart">Ir al proceso</a></p>
                                    <?php
									}
									?>
								</div>
							</div>
							<?php
							}
                            ?>
				</div>
			</div>