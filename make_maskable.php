<?php
$src = '/home/u856928007/domains/kaply.nl/public_html/public/images/PWA-icon.png';
$img = imagecreatefrompng($src);
$orig = 1024;
$size = 512;
$safeSize = (int)($size * 0.8);
$padding = (int)(($size - $safeSize) / 2);
$canvas = imagecreatetruecolor($size, $size);
$bg = imagecolorallocate($canvas, 30, 30, 34);
imagefill($canvas, 0, 0, $bg);
imagecopyresampled($canvas, $img, $padding, $padding, 0, 0, $safeSize, $safeSize, $orig, $orig);
imagepng($canvas, '/home/u856928007/domains/kaply.nl/public_html/public/images/PWA-icon-maskable.png', 6);
echo "Done\n";
