<?php
session_start();

// Generate a random 5-character string
$characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // Excluded confusing chars like 1, I, 0, O
$captcha_code = '';
for ($i = 0; $i < 5; $i++) {
    $captcha_code .= $characters[rand(0, strlen($characters) - 1)];
}

// Store in session
$_SESSION['captcha_code'] = $captcha_code;

// Create image
$width = 130;
$height = 45;
$image = imagecreatetruecolor($width, $height);

// Colors - High contrast
$bg_color = imagecolorallocate($image, 255, 255, 255); // Pure White
$text_color = imagecolorallocate($image, 0, 51, 153); // Deep Blue
$line_color = imagecolorallocate($image, 200, 220, 255); // Very faint blue lines
$pixel_color = imagecolorallocate($image, 180, 180, 180); // Light gray dots

// Fill background
imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);

// Add very light noise lines (only 3)
for ($i = 0; $i < 3; $i++) {
    imageline($image, 0, rand() % $height, $width, rand() % $height, $line_color);
}

// Add fewer random pixels
for ($i = 0; $i < 50; $i++) {
    imagesetpixel($image, rand() % $width, rand() % $height, $pixel_color);
}

// Add text - Font size 5 is the largest default
// Positioned clearly in the center
imagestring($image, 5, 40, 15, $captcha_code, $text_color);

// Output image
header("Cache-Control: no-cache, must-revalidate");
header("Content-type: image/png");
imagepng($image);
imagedestroy($image);
?>
