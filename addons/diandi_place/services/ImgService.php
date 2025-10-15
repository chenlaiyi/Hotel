<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-12 20:51:33
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-12 20:55:39
 */
namespace addons\diandi_place\services;
use common\services\BaseService;
class ImgService extends BaseService
{
    /**
     * 图片压缩处理
     * @param string $sFile 图片路径
     * @param int $iWidth 自定义图片宽度
     * @param int $iHeight 自定义图片高度
     */
    public static function getThumb($sFile, $iWidth, $iHeight)
    {
        //判断该图片是否存在
        if (!file_exists(public_path() . $sFile)) return $sFile;
        //判断图片格式
        $attach_fileext = get_filetype($sFile);
        if (!in_array($attach_fileext, array('jpg', 'png', 'jpeg'))) {
            return $sFile;
        }
        //压缩图片
        $sFileNameS = str_replace("." . $attach_fileext, "_" . $iWidth . '_' . $iHeight . '.' . $attach_fileext, $sFile);
        //判断是否已压缩图片，若是则返回压缩图片路径
        if (file_exists(public_path() . $sFileNameS)) {
            return $sFileNameS;
        }
        //解决手机端上传图片被旋转问题
        if (in_array($attach_fileext, array('jpeg'))) {
            adjustPicOrientation(public_path() . $sFile);
        }
        //生成压缩图片，并存储到原图同路径下
        self::resizeImage(public_path() . $sFile, public_path() . $sFileNameS, $iWidth, $iHeight);
        if (!file_exists(public_path() . $sFileNameS)) {
            return $sFile;
        }
        return $sFileNameS;
    }
    /**
     *获取文件后缀名
     */
    public static function get_filetype($filename)
    {
        $extend = explode(".", $filename);
        return strtolower($extend[count($extend) - 1]);
    }
    /**
     * 解决手机上传图片被旋转问题
     * @param string $full_filename 文件路径
     */
    public static function adjustPicOrientation($full_filename)
    {
        $exif = exif_read_data($full_filename);
        if ($exif && isset($exif['Orientation'])) {
            $orientation = $exif['Orientation'];
            if ($orientation != 1) {
                $img = imagecreatefromjpeg($full_filename);
                $mirror = false;
                $deg  = 0;
                switch ($orientation) {
                    case 2:
                        $mirror = true;
                        break;
                    case 3:
                        $deg = 180;
                        break;
                    case 4:
                        $deg = 180;
                        $mirror = true;
                        break;
                    case 5:
                        $deg = 270;
                        $mirror = true;
                        break;
                    case 6:
                        $deg = 270;
                        break;
                    case 7:
                        $deg = 90;
                        $mirror = true;
                        break;
                    case 8:
                        $deg = 90;
                        break;
                }
                if ($deg) $img = imagerotate($img, $deg, 0);
                if ($mirror) $img = _mirrorImage($img);
                //$full_filename = str_replace('.jpg', "-O$orientation.jpg", $full_filename);新文件名
                imagejpeg($img, $full_filename, 95);
            }
        }
        return $full_filename;
    }
    /**
     * 生成图片
     * @param string $im 源图片路径
     * @param string $dest 目标图片路径
     * @param int $maxwidth 生成图片宽
     * @param int $maxheight 生成图片高
     */
    public static function resizeImage($im, $dest, $maxwidth, $maxheight)
    {
        $img = getimagesize($im);
        switch ($img[2]) {
            case 1:
                $im = @imagecreatefromgif($im);
                break;
            case 2:
                $im = @imagecreatefromjpeg($im);
                break;
            case 3:
                $im = @imagecreatefrompng($im);
                break;
        }
        $pic_width = imagesx($im);
        $pic_height = imagesy($im);
        $resizewidth_tag = false;
        $resizeheight_tag = false;
        if (($maxwidth && $pic_width > $maxwidth) || ($maxheight && $pic_height > $maxheight)) {
            if ($maxwidth && $pic_width > $maxwidth) {
                $widthratio = $maxwidth / $pic_width;
                $resizewidth_tag = true;
            }
            if ($maxheight && $pic_height > $maxheight) {
                $heightratio = $maxheight / $pic_height;
                $resizeheight_tag = true;
            }
            if ($resizewidth_tag && $resizeheight_tag) {
                if ($widthratio < $heightratio)
                    $ratio = $widthratio;
                else
                    $ratio = $heightratio;
            }
            if ($resizewidth_tag && !$resizeheight_tag)
                $ratio = $widthratio;
            if ($resizeheight_tag && !$resizewidth_tag)
                $ratio = $heightratio;
            $newwidth = $pic_width * $ratio;
            $newheight = $pic_height * $ratio;
            if (function_exists("imagecopyresampled")) {
                $newim = imagecreatetruecolor($newwidth, $newheight);
                imagecopyresampled($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $pic_width, $pic_height);
            } else {
                $newim = imagecreate($newwidth, $newheight);
                imagecopyresized($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $pic_width, $pic_height);
            }
            imagejpeg($newim, $dest);
            imagedestroy($newim);
        } else {
            imagejpeg($im, $dest);
        }
    }
}
