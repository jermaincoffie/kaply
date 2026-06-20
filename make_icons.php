<?php
$src = '/home/u856928007/domains/kaply.nl/public_html/public/images/PWA-icon.png';
$img = imagecreatefrompng($src);
$orig_w = imagesx($img);
$orig_h = imagesy($img);
echo "Origineel: {$orig_w}x{$orig_h}\n";

// 192x192
$out192 = imagecreatetruecolor(192, 192);
imagealphablending($out192, false);
imagesavealpha($out192, true);
imagecopyresampled($out192, $img, 0, 0, 0, 0, 192, 192, $orig_w, $orig_h);
imagepng($out192, '/home/u856928007/domains/kaply.nl/public_html/public/images/PWA-icon-192.png', 6);
echo "192x192 klaar\n";

// 512x512
$out512 = imagecreatetruecolor(512, 512);
imagealphablending($out512, false);
imagesavealpha($out512, true);
imagecopyresampled($out512, $img, 0, 0, 0, 0, 512, 512, $orig_w, $orig_h);
imagepng($out512, '/home/u856928007/domains/kaply.nl/public_html/public/images/PWA-icon-512.png', 6);
echo "512x512 klaar\n";

// maskable 512x512 (80% safe zone)
$size = 512;
$safeSize = (int)($size * 0.8);
$padding = (int)(($size - $safeSize) / 2);
$canvas = imagecreatetruecolor($size, $size);
$bg = imagecolorallocate($canvas, 30, 30, 34);
imagefill($canvas, 0, 0, $bg);
imagecopyresampled($canvas, $img, $padding, $padding, 0, 0, $safeSize, $safeSize, $orig_w, $orig_h);
imagepng($canvas, '/home/u856928007/domains/kaply.nl/public_html/public/images/PWA-icon-maskable.png', 6);
echo "maskable 512x512 klaar\n";

echo "Alles klaar!\n";
