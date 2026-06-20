<?php
$src = '/home/u856928007/domains/kaply.nl/public_html/public/images/PWA-icon.png';
$img = imagecreatefrompng($src);
$w = imagesx($img); $h = imagesy($img);
echo "Size: {$w}x{$h}\n";
$out512 = imagecreatetruecolor(512, 512);
imagecopyresampled($out512,$img,0,0,0,0,512,512,$w,$h);
imagepng($out512, '/home/u856928007/domains/kaply.nl/public_html/public/images/PWA-icon-512.png', 8);
$out192 = imagecreatetruecolor(192, 192);
imagecopyresampled($out192,$img,0,0,0,0,192,192,$w,$h);
imagepng($out192, '/home/u856928007/domains/kaply.nl/public_html/public/images/PWA-icon-192.png', 8);
echo "Done\n";
