<?php
$arrayURL = admin::urlArray();
//print_r($arrayURL);

if($arrayURL[$urlPositionTitle]!='divisas' && $arrayURL[$urlPositionTitle]!='session' && $arrayURL[$urlPositionTitle]!='registro')
{
?>
<div id="sidebar">
				<div id="box1" class="box-style">
					<div class="title">
						<h2><span><?=$Category?></span></h2>
					</div>
					<div class="content">
						<ul>
                        <?php
						$arrayURL = admin::urlArray();
						$sql3 = "SELECT distinct a.pca_name, a.pca_url FROM mdl_pro_category a, mdl_subasta b where b.sub_pca_uid=a.pca_uid and b.sub_status='ACTIVE' and a.pca_delete=0 and b.sub_delete=0 order by pca_name ";
						$db3->query($sql3);
						$k=0;
						while($cate = $db3->next_record()) 
						{ 
						?>
							<li><a href="<?=$domain.'/'.$CategoryUrl.'/'.$cate["pca_url"]?>/" <?php if ($arrayURL[$urlPositionSubtitle]==$cate["pca_url"]) echo "class='bold red'";elseif(($k==0)&&(!$arrayURL[$urlPositionSubtitle])) echo "class='bold red'";?>><?=$cate["pca_name"]?></a></li>
						<?php
						$k++;
						}						
						?>
						</ul>
					</div>
				</div>
</div>
<?php
}else if($arrayURL[$urlPositionTitle]!='session' && $arrayURL[$urlPositionTitle]!='registro'){
	
?>
<div id="sidebar">
				<div id="box1" class="box-style">
					<div class="title">
						<h2><span><?=$Category?></span></h2>
					</div>
					<div class="content">
						<ul>
                        <?php
						$arrayURL = admin::urlArray();
						$sql3 = "SELECT distinct a.pca_name, a.pca_url FROM mdl_pro_category a, mdl_subasta b where b.sub_pca_uid=a.pca_uid and b.sub_status='ACTIVE' and a.pca_delete=0 and b.sub_delete=0 order by pca_name ";
						$db3->query($sql3);
						$k=0;
						while($cate = $db3->next_record()) 
						{ 
						?>
							<li><a href="<?=$domain.'/'.$arrayURL[$urlPositionTitle].'/'.$cate["pca_url"]?>/" <?php if ($arrayURL[$urlPositionSubtitle]==$cate["pca_url"]) echo "class='bold red'";elseif(($k==0)&&(!$arrayURL[$urlPositionSubtitle])) echo "class='bold red'";?>>Compra/venta de divisas</a></li>
						<?php
						$k++;
						}						
						?>
						</ul>
					</div>
				</div>
</div>
<?php

}
?>