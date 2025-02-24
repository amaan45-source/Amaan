<?php
session_start();

// Generate a random 6-character string
$captcha_text = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz23456789"), 0, 6);
$_SESSION['captcha'] = $captcha_text;

// Create image
$width = 120;
$height = 50;
$image = imagecreate($width, $height);
$bg_color = imagecolorallocate($image, 255, 255, 255);
$text_color = imagecolorallocate($image, 243, 152, 16);

// Add text to the image
imagestring($image, 5, 25, 10, $captcha_text, $text_color);

// Set header to display image
header("Content-Type: image/png");
imagepng($image);
imagedestroy($image);
?>
