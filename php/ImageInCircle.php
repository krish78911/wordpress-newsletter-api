<?php
/**
 * Make image into circled image
 */
require_once 'ParameterInterface.php';
class ImageInCircle { // return image

    function circle($img,$size=null) {

        $image_s = imagecreatefromstring(file_get_contents($img));
        $width = imagesx($image_s);
        $height = imagesy($image_s);
        if(!$size) $size = min($width, $height);
        $newwidth = $size;
        $newheight = $size;
        $image = imagecreatetruecolor($newwidth, $newheight);
        imagealphablending($image, true);
        imagecopyresampled($image, $image_s, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        $mask = imagecreatetruecolor($newwidth, $newheight);
        $transparent = imagecolorallocate($mask, 255, 0, 0);
        imagecolortransparent($mask,$transparent);
        imagefilledellipse($mask, $newwidth/2, $newheight/2, $newwidth, $newheight, $transparent);
        $red = imagecolorallocate($mask, 0, 0, 0);
        imagecopymerge($image, $mask, 0, 0, 0, 0, $newwidth, $newheight, 100);
        imagecolortransparent($image,$red);
        imagefill($image, 0, 0, $red);
        imagedestroy($mask);
        $image2 = imagecreatetruecolor($newwidth, $newheight);
        imagesavealpha($image2, true);
        imagefill($image2, 0, 0, imagecolorallocatealpha($image2, 0, 0, 0, 127));
        imagefilledellipse($image2, $newwidth/2, $newheight/2, $newwidth, $newheight, imagecolorallocate($image2, 0, 0, 0));
        imagecopy($image2, $image, 0, 0, 0, 0, $newwidth, $newheight);
        imagedestroy($image);
        return $image2;

    }
    
}

?>
