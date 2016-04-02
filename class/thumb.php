<?php

class thumb
{
    public static function make($filename, $maxWidth, $maxHeight)
    {
        $imgPath = XOOPS_UPLOAD_PATH . '/APCal/' . $filename;

        $imgInfo = getimagesize($imgPath);
        $oWidth  = $imgInfo[0];
        $oHeight = $imgInfo[1];
        $ratio   = $oHeight / $oWidth;
        $nWidth  = $maxWidth;
        $nHeight = $maxHeight;

        if ($ratio > 1) {
            $nHeight = $maxHeight;
            $nWidth  = $nHeight / $ratio;
        } else {
            $nWidth  = $maxWidth;
            $nHeight = $nWidth * $ratio;
        }

        return array('width' => $nWidth, 'height' => $nHeight);
    }

    public static function save($filename, $maxWidth, $maxHeight)
    {
        $thumbPath = XOOPS_UPLOAD_PATH . '/APCal/thumbs/' . $filename;
        $imgPath   = XOOPS_UPLOAD_PATH . '/APCal/' . $filename;
        $nSize     = self::make($filename, $maxWidth, $maxHeight);
        $imgInfo   = getimagesize($imgPath);
        $oWidth    = $imgInfo[0];
        $oHeight   = $imgInfo[1];
        $fileType  = $imgInfo[2];

        switch ($fileType) {
            case IMAGETYPE_JPEG:
            default:
                $img = imagecreatefromjpeg($imgPath);
                break;
            case IMAGETYPE_GIF:
                $img = imagecreatefromgif($imgPath);
                break;
            case IMAGETYPE_PNG:
                $img = imagecreatefrompng($imgPath);
                break;
        }

        $nImg    = imagecreatetruecolor($nSize['width'], $nSize['height']);
        $bgColor = imagecolorallocate($nImg, 0xFF, 0xFF, 0xFF);
        imagefill($nImg, 0, 0, $bgColor);
        imagecopyresampled($nImg, $img, 0, 0, 0, 0, $nSize['width'], $nSize['height'], $oWidth, $oHeight);

        switch ($fileType) {
            case IMAGETYPE_JPEG:
            default:
                imagejpeg($nImg, $thumbPath, 75);
                break;
            case IMAGETYPE_GIF:
                imagegif($nImg, $thumbPath);
                break;
            case IMAGETYPE_PNG:
                imagepng($nImg, $thumbPath);
                break;
        }
    }

    public static function exists($filename)
    {
        return file_exists(XOOPS_UPLOAD_PATH . '/APCal/thumbs/' . $filename);
    }
}
