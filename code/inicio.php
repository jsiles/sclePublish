<div id="content">
				<div id="box6" class="box-style">
					<div class="title">
						<h2><span>Productos</span></h2>
					</div>
					<div class="content">
                                            <p>Descripci&oacute;n corta de los productos en subasta.</p>
						<p>&nbsp;</p>
					</div>
					<?php
							$sql2 = "SELECT pro_uid, pro_url, pro_name, pro_image, sub_description,sub_mount_base,sub_hour_end, pca_url, sub_deadtime FROM mdl_product, mdl_subasta, mdl_pro_category WHERE sub_uid=pro_sub_uid and sub_pca_uid=pca_uid and sub_delete=0 order by sub_hour_end asc";
							$db2->query($sql2);
							while ($content=$db2->next_record())
							{
								$image1 = PATH_ROOT.'/img/subasta/img_'.$content["pro_image"];
								list($width, $height) = getimagesize($image1);
								if ($width<132) $maxAncho=132-$width;
								else $maxAncho=0;
								

								$timetobe=admin::time_diff($content["sub_hour_end"],date('Y-m-d H:i:s'));
								$timedead=admin::time_diff($content["sub_deadtime"],date('Y-m-d H:i:s'));
								$finish=$content["sub_finish"];
								//echo $timetobe."#".$content["sub_deadtime"]."#".$timedead;
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
								else $faltante='Concluida';
                            ?>
							<div class="item-box">
								<p style="margin-left:<?=$maxAncho?>px;"><a href="<?=$domain.'/'.$CategoryUrl.'/'.$content["pca_url"].'/'.$content["pro_url"].'/'?>"><img src="<?=$domain.'/img/subasta/img_'.$content["pro_image"]?>" class="alignleft" alt="<?=$content["pro_name"]?>" title="<?=$content["pro_name"]?>"/></a></p>
								<h2><a href="<?=$domain.'/'.$CategoryUrl.'/'.$content["pca_url"].'/'.$content["pro_url"].'/'?>"><?=$content["pro_name"]?></a></h2>
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
										$centavos=substr($content["sub_mount_base"],-3);
										$montoGlobal=str_replace($centavos,'',$content["sub_mount_base"]);
										}
									else
									{
										$centavos=substr($valBids,-3);
										$montoGlobal=str_replace($centavos,'',$valBids);
										}
								 	$centavos=str_replace('.','',$centavos);
								?>
									<p class="left">$<?=$montoGlobal?>.<sup><?=$centavos?></sup></p>
									<p class="right"><a href="<?=$domain.'/'.$CategoryUrl.'/'.$content["pca_url"].'/'.$content["pro_url"].'/'?>" class="addcart"><?=$faltante?></a></p>
								</div>
							</div>
							<?php
							}
                            ?>
				</div>
			</div>