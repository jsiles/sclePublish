<?php
/* 
Powered by logoscomunicaciones.com
redimencionIMG donde:
$origen = nombre de la imagen a redimencionar
$destino = el nombre de la nueva imagen thumbs
$ancho = el ancho al que se quiere redimensionar
$calidad = la calidad de la imagen 100 - mejor calidad
Formatos Aceptados: JPG, BMP, GIF, BMP
*/

// Conversor de archivos BMP a GD para posterior uso en resize
function ConvertBMP2GD($src, $dest = false) 
	{
	if(!($src_f = fopen($src, "rb"))) {
		return false;
		}
	if(!($dest_f = fopen($dest, "wb"))) {
		return false;
		}
	$header = unpack("vtype/Vsize/v2reserved/Voffset", fread($src_f,14));
	$info = unpack("Vsize/Vwidth/Vheight/vplanes/vbits/Vcompression/Vimagesize/Vxres/Vyres/Vncolor/Vimportant", fread($src_f, 40));
	extract($info);
	extract($header);
	if($type != 0x4D42) { // signature "BM"
		return false;
		}

	$palette_size = $offset - 54;
	$ncolor = $palette_size / 4;
	$gd_header = "";
	// true-color vs. palette
	$gd_header .= ($palette_size == 0) ? "\xFF\xFE" : "\xFF\xFF";
	$gd_header .= pack("n2", $width, $height);
	$gd_header .= ($palette_size == 0) ? "\x01" : "\x00";
	if($palette_size) {
		$gd_header .= pack("n", $ncolor);
		}
	// no transparency
	$gd_header .= "\xFF\xFF\xFF\xFF";
	
	fwrite($dest_f, $gd_header);
	
	if($palette_size) 
		{
		$palette = fread($src_f, $palette_size);
		$gd_palette = "";
		$j = 0;
		while($j < $palette_size) 
			{
			$b = $palette{$j++};
			$g = $palette{$j++};
			$r = $palette{$j++};
			$a = $palette{$j++};
			$gd_palette .= "$r$g$b$a";
			}
		$gd_palette .= str_repeat("\x00\x00\x00\x00", 256 - $ncolor);
		fwrite($dest_f, $gd_palette);
		}
	$scan_line_size = (($bits * $width) + 7) >> 3;
	$scan_line_align = ($scan_line_size & 0x03) ? 4 - ($scan_line_size &
	0x03) : 0;
	
	for($i = 0, $l = $height - 1; $i < $height; $i++, $l--) 
		{
		// BMP stores scan lines starting from bottom
		fseek($src_f, $offset + (($scan_line_size + $scan_line_align) *
		$l));
		$scan_line = fread($src_f, $scan_line_size);
		if($bits == 24) {
			$gd_scan_line = "";
			$j = 0;
			while($j < $scan_line_size) {
				$b = $scan_line{$j++};
				$g = $scan_line{$j++};
				$r = $scan_line{$j++};
				$gd_scan_line .= "\x00$r$g$b";
				}
			}
		else if($bits == 8) 
			{
			$gd_scan_line = $scan_line;
			}
		else if($bits == 4) 
			{
			$gd_scan_line = "";
			$j = 0;
			while($j < $scan_line_size) {
				$byte = ord($scan_line{$j++});
				$p1 = chr($byte >> 4);
				$p2 = chr($byte & 0x0F);
				$gd_scan_line .= "$p1$p2";
				}
			$gd_scan_line = substr($gd_scan_line, 0, $width);
			}
		else if($bits == 1) 
			{
			$gd_scan_line = "";
			$j = 0;
			while($j < $scan_line_size) {
				$byte = ord($scan_line{$j++});
				$p1 = chr((int) (($byte & 0x80) != 0));
				$p2 = chr((int) (($byte & 0x40) != 0));
				$p3 = chr((int) (($byte & 0x20) != 0));
				$p4 = chr((int) (($byte & 0x10) != 0));
				$p5 = chr((int) (($byte & 0x08) != 0));
				$p6 = chr((int) (($byte & 0x04) != 0));
				$p7 = chr((int) (($byte & 0x02) != 0));
				$p8 = chr((int) (($byte & 0x01) != 0));
				$gd_scan_line .= "$p1$p2$p3$p4$p5$p6$p7$p8";
				}
			$gd_scan_line = substr($gd_scan_line, 0, $width);
			}
		fwrite($dest_f, $gd_scan_line);
		}
	fclose($src_f);
	fclose($dest_f);
	return true;
	}
// FUNCION CONSTRUIDA PARA PODER CREAR IMAGENES DESDE ARCHIVOS BMP
function ImageCreateFromBMP($filename) 
	{
	$tmp_name = tempnam("/tmp", "GD");
	if(ConvertBMP2GD($filename, $tmp_name)) {
		$img = imagecreatefromgd($tmp_name);
		unlink($tmp_name);
		return $img;
		}
	return false;
	}  

function redimensionar_jpeg($img_original, $img_nueva, $img_nueva_anchura, $img_nueva_altura, $img_nueva_calidad)
{ 
    // crear una imagen desde el original
	$archivoURL = explode("/",$img_original); 	// Separamos por slashes 
	$archivoPos=count($archivoURL);				// Contamos cuantas separaciones hay
	$archivo = $archivoURL[$archivoPos-1];		// Obtenemos el nombre de archivo
	$ext = explode(".",$archivo); 				// Separamos por puntos	
	$extNro = count($ext);						// Contamos cuantas separaciones hay
	$extention = strtoupper($ext[$extNro-1]);	// Obtenemos la extencion del archivo en mayuscula
	
	if ($extention=="GIF")
		{
		$img = imagecreatefromgif($img_original);
		}
	if ($extention=="PNG")
		{
		$img = imagecreatefrompng($img_original);
		}	
	if ($extention=="JPG" or $extention=="JPEG")
		{
	    $img = imagecreatefromjpeg($img_original);
		}
	if ($extention=="BMP")
		{
	    $img = ImageCreateFromBMP($img_original);
		}	
	
	$Xancho = imagesx($img);
	$Xalto = imagesy($img);
	
    // crear una imagen nueva


    $thumb = imagecreatetruecolor($img_nueva_anchura,$img_nueva_altura);
//	echo "funca";die;
    // redimensiona la imagen original copiandola en la imagen
	imagecopyresampled($thumb,$img,0,0,0,0,$img_nueva_anchura,$img_nueva_altura,ImageSX($img),ImageSY($img));
//ImageCopyResized($thumb,$img,0,0,0,0,$img_nueva_anchura,$img_nueva_altura,ImageSX($img),ImageSY($img));
     // guardar la nueva imagen redimensionada donde indicia $img_nueva
    imagejpeg($thumb,$img_nueva,$img_nueva_calidad);
    imagedestroy($img);	
}
//REDIMENCIONA IMAGEN
function redimImg($origen,$destino,$ancho,$alto,$calidad)
	{
	$destino_temporal=tempnam("tmp/","tmp");
	redimensionar_jpeg($origen, $destino_temporal, $ancho, $alto, $calidad);
	// guardamos la imagen
	$fp=fopen($destino,"w");
	fputs($fp,fread(fopen($destino_temporal,"r"),filesize($destino_temporal)));
	fclose($fp);
	/*
	// mostramos la imagen
	echo "Imagen Normal";
	echo "<img src='imagen1.jpg'>";
	echo "Imagen Reducida";
	echo "<img src='imagen1little.jpg'>";
	*/
	}

//REDIMENCIONA SOLO EL ANCHO DE LA IMAGEN
function redimImgWidth($origen,$destino,$ancho,$calidad)
	{
	list($width, $height) = getimagesize($origen);
	$ancho = $ancho; //$t_ancho * $alto / $ancho	//$t_ancho * $alto / $ancho;
	$alto = ($ancho * $height)/$width;	
	$destino_temporal=tempnam("tmp/","tmp");
	redimensionar_jpeg($origen, $destino_temporal, $ancho, $alto, $calidad);
	// guardamos la imagen
	$fp=fopen($destino,"w");
	fputs($fp,fread(fopen($destino_temporal,"r"),filesize($destino_temporal)));
	fclose($fp);
	}
	
//REDIMENCIONA SOLO EL ALTO DE LA IMAGEN
function redimImgHeight($origen,$destino,$alto,$calidad)
	{
	list($width, $height) = getimagesize($origen);
	$ancho = ($alto * $width)/$height;
	$alto = $alto;
	$destino_temporal=tempnam("tmp/","tmp");
	redimensionar_jpeg($origen, $destino_temporal, $ancho, $alto, $calidad);
	// guardamos la imagen
	$fp=fopen($destino,"w");
	fputs($fp,fread(fopen($destino_temporal,"r"),filesize($destino_temporal)));
	fclose($fp);
	}
//REDIMENCIONA IMAGEN POR PORCENTAJE
function redimImgPercent($origen,$destino,$porcentaje,$calidad)
	{
	list($width, $height) = getimagesize($origen);	
	$ancho = $width * ($porcentaje/100);
	$alto = $height * ($porcentaje/100);	
	$destino_temporal=tempnam("tmp/","tmp");
	redimensionar_jpeg($origen, $destino_temporal, $ancho, $alto, $calidad);

	// guardamos la imagen
	$fp=fopen($destino,"w");
	fputs($fp,fread(fopen($destino_temporal,"r"),filesize($destino_temporal)));
	fclose($fp);
	}
function redimImgWH($origen,$destino,$dimension,$calidad)
	{
	list($width, $height) = getimagesize($origen);
	
	if ($width>$height)
		{
		$ancho = $dimension;
		$alto = ($dimension * $height)/$width;		
		}
	else
		{
		$ancho = ($dimension * $width)/$height;
		$alto = $dimension;
		}
	

	$destino_temporal=tempnam("tmp/","tmp");
	redimensionar_jpeg($origen, $destino_temporal, $ancho, $alto, $calidad);
	// guardamos la imagen
	$fp=fopen($destino,"w");
	fputs($fp,fread(fopen($destino_temporal,"r"),filesize($destino_temporal)));
	fclose($fp);
	}  
//FUNCION CROPEADORA
function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
	list($imagewidth, $imageheight, $imageType) = getimagesize($image);
	$imageType = image_type_to_mime_type($imageType);
	$newImageWidth = ceil($width * $scale);
	$newImageHeight = ceil($height * $scale);
	$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
	switch($imageType) 
		{
		case "image/gif":
			$source=imagecreatefromgif($image); 
			break;
	    case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
			$source=imagecreatefromjpeg($image); 
			break;
	    case "image/png":
		case "image/x-png":
			$source=imagecreatefrompng($image); 
			break;
  		}
	imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
	switch($imageType) 
		{
		case "image/gif":
	  		imagegif($newImage,$thumb_image_name); 
			break;
      	case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
	  		imagejpeg($newImage,$thumb_image_name,90); 
			break;
		case "image/png":
		case "image/x-png":
			imagepng($newImage,$thumb_image_name);  
			break;
    	}
	chmod($thumb_image_name, 0777);
	return $thumb_image_name;
	}	
?>