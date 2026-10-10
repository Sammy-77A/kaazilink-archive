<?php

function createDummyImage($filename, $text) {
    $im = imagecreatetruecolor(400, 250);
    $bg = imagecolorallocate($im, 240, 240, 240);
    $textColor = imagecolorallocate($im, 40, 40, 40);
    imagefilledrectangle($im, 0, 0, 399, 249, $bg);
    imagestring($im, 5, 20, 100, $text, $textColor);
    imagejpeg($im, $filename, 80);
    imagedestroy($im);
}

createDummyImage(__DIR__ . '/id_front.jpg', 'KENYA NATIONAL ID FRONT');
createDummyImage(__DIR__ . '/id_back.jpg', 'KENYA NATIONAL ID BACK');
createDummyImage(__DIR__ . '/kra_pin.jpg', 'KRA PIN CERTIFICATE');
echo "Created test KYC image files in scratch/\n";
