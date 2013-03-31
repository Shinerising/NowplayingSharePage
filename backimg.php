<?php
// Build the background image for the NowplayingSharePage;
// Imagick plugin needed, you must have it installed and enabled;
// This page get the image url and make some blur then output it;
// It's a good idea to put a blured image as your website's background;
// Actrually it would looks beautiful but not make reading of words hard;

header('Content-Type: image/jpeg');
$url = $_GET["url"];
$str = 'convert ' . $url . ' 200x200 -blur 0x30 back.jpg';
exec($str); 
$im = imagecreatefromjpeg("back.jpg");
imagejpeg($im,'',80);
?>