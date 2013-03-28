<?php
header('Content-Type: image/jpeg');
$url = $_GET["url"];
$str = 'convert ' . $url . ' 200x200 -blur 0x30 back.jpg';
exec($str); 
$im = imagecreatefromjpeg("back.jpg");
imagejpeg($im,'',80);
?>