<?php
include_once("core/admin.php");
admin::initializeClient();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset==UTF-8" />
<title></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="<?=$domain?>/css/style.css" rel="stylesheet" type="text/css" media="all" />
<link rel="shortcut icon" href="<?=$domain?>/lib/favicon.ico" />
<link rel="stylesheet" href="<?=$domain?>/css/thickbox.css" type="text/css" media="screen" /> 
<script type="text/javascript">var SERVER='<?=$domain?>'; </script>
<script type="text/javascript" src="<?=$domain?>/js/jquery.js"></script>
<script type="text/javascript" src="<?=$domain?>/js/session.js"></script>

</head>
<body class="listings">
<div id="wrapper">
	<div id="wrapper-bgbtm">
	    <?php include("code/header.php");?>
        <?php include("code/menu_header.php");?>
		<div id="page" class="container">
        <?php include("code/registro.php");?>
		<?php include("code/column.php");?>
		</div>	
	</div>
</div>
<?php include("code/footer.php");?>
</body>
</html>