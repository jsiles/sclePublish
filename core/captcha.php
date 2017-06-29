<?php
require_once 'admin.php';
$code=admin::getParam("t","strip");
$code=SymmetricCrypt::Decrypt($code);
$im = imagecreatetruecolor(100, 40);
$bg = imagecolorallocate($im, 22, 86, 165); //background color blue
$fg = imagecolorallocate($im, 255, 255, 255);//text color white
imagefill($im, 0, 0, $bg);
imagestring($im, 5, 30, 12,  $code, $fg);
header("Cache-Control: no-cache, must-revalidate");
header('Content-type: image/png');
imagepng($im);
imagedestroy($im);
?>