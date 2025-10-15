<?php

/**
 * @Author: Wang Chunsheng 2192138785@qq.com
 * @Date:   2020-03-01 15:32:39
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-07-18 17:23:40
 */

namespace common\helpers;

use common\components\FileUpload\models\DdUploadFileUsed;
use common\components\FileUpload\models\UploadFile;
use Yii;
use yii\console\Request;
use yii\web\NotFoundHttpException;

/**
 * Class ImageHelper.
 *
 * @author chunchun <2192138785@qq.com>
 */
class ImageHelper
{
    /**
     * 默认图片.
     *
     * @param $imgSrc
     * @param string $defaultImgSre
     *
     * @return string
     */
    function default($imgSrc, $defaultImgSre = '/resources/img/error.png')
    {
        return !empty($imgSrc) ? $imgSrc : Yii::getAlias('@web') . $defaultImgSre;
    }

    /**
     * 默认头像.
     *
     * @param $imgSrc
     * @param string $defaultImgSre
     * @return mixed|string
     */
    public static function defaultHeaderPortrait($imgSrc, $defaultImgSre = '/resources/img/profile_small.jpg')
    {
        return !empty($imgSrc) ? $imgSrc : Yii::getAlias('@web') . $defaultImgSre;
    }


    /**
     * 判断是否图片地址
     *
     * @param string $imgSrc
     *
     * @return bool
     */
    public static function isImg($imgSrc)
    {
        $extend = StringHelper::clipping($imgSrc, '.', 1);

        $imgExtends = [
            'bmp',
            'jpg',
            'gif',
            'jpeg',
            'jpe',
            'jpg',
            'png',
            'jif',
            'dib',
            'rle',
            'emf',
            'pcx',
            'dcx',
            'pic',
            'tga',
            'tif',
            'tiffxif',
            'wmf',
            'jfif',
        ];
        if (in_array($extend, $imgExtends) || strpos($imgSrc, 'http://wx.qlogo.cn') !== false) {
            return true;
        }

        return false;
    }

    public static function getMediaUrl($image)
    {
        $url = '';
        // 根据上传记录判断上传类型
        $storage = UploadFile::find()->where(['file_url' => $image])->select('storage')->scalar();
        $appId = Yii::$app->id;

        if (Yii::$app->request instanceof Request) {
            $hostInfo = '';
        } else {
            $hostInfo = Yii::$app->request->hostInfo;
        }


        $oss = Yii::$app->params['conf']['oss'];

        switch ($storage) {
            case 'alioss':
                $url = $oss['Aliyunoss_url'] ?? '';
                break;
            case 'qiniu':
                $url = $oss['Qiniuoss_url'] ?? '';
                break;
            case 'cos':
                $url = $oss['Tengxunoss_url'] ?? '';
                break;
            default:
                $url = $hostInfo;
                break;
        }

        return $url ? $url : $hostInfo;
    }

    public static function tomedia($image, $type = 'default.jpg')
    {
        $default = '/resource/images/public/' . $type;
        $hostUrl = self::getMediaUrl($image);

        if (empty($image)) {
            $image = $hostUrl  . $default;
        }
        if (is_array($image)) {
            foreach ($image as $key => &$value) {

                if (str_starts_with($value, '//')) {
                    $value = 'Http:' . $value;
                } else {
                    $file = Yii::getAlias('@attachment/'.$value);
                    if (file_exists($file)){
                        $value = $value ? $hostUrl . '/attachment/' . $value : $hostUrl . $default;
                    }else{
                        $value = $hostUrl  . $default;
                    }
                }
            }
        } else {
            if (str_starts_with($image, '//')) {
                return 'Http:' . $image;
            }
            if ((str_starts_with($image, 'Http://')) || (str_starts_with($image, 'https://'))) {
                return $image;
            }

            $file = Yii::getAlias('@attachment/'.$image);

            if (file_exists($file)){

                $image = $hostUrl . '/attachment/' . $image;
            }else{
                $image = $hostUrl .  $default;
            }

        }



        return $image;
    }

    /**
     * 写入文件上传记录.
     *
     * @param int|null $file_name post
     *
     * @return string
     *
     * @throws NotFoundHttpException|NotFoundHttpException
     */
    public static function uploadDb($file_name, $file_size, $file_type, $extension, $file_url = '', $group_id = 0, $storage = 'local')
    {
        $datas = [
            'storage' => $storage,
            'group_id' => $group_id,
            'file_url' => $file_url,
            'file_name' => $file_name,
            'file_size' => $file_size,
            'file_type' => $file_type,
            'extension' => $extension,
            'is_delete' => 0,
        ];

        loggingHelper::writeLog('ImageHelper', 'uploadDb', '文件存储记录', $datas);

        $UploadFile = new UploadFile();
        if ($UploadFile->load($datas, '') && $UploadFile->save()) {
            // 用户关联存储
            $file_id = $UploadFile->file_id;
            $DdUploadFileUsed = new DdUploadFileUsed();
            $DdUploadFileUsed->load([
                'file_id' => $file_id,
                'from_id' => 0,
                'from_type' => $storage,
                'user_id' => (int)Yii::$app->user->identity->id,
            ], '') && $DdUploadFileUsed->save();

            return $UploadFile;
        } else {
            $msg = ErrorsHelper::getModelError($UploadFile);
            loggingHelper::writeLog('ImageHelper', 'uploadDb', '错误记录', $msg);

            return $msg;
        }
    }



    /**
     * 图片上传根据大小进行压缩
     */
    public static function compressImage($image, $size = 1024)
    {
        $image_info = getimagesize($image);
        $image_type = $image_info[2];

        switch ($image_type) {
            case 1:
                $image = imagecreatefromgif($image);
                break;
            case 2:
                $image = imagecreatefromjpeg($image);
                break;
            case 3:
                $image = imagecreatefrompng($image);
        }
        $image_width = imagesx($image);
        $image_height = imagesy($image);
        if ($image_width > $size || $image_height > $size) {
            $new_width = $image_width;
            $new_height = $image_height;
            if ($image_width > $image_height) {
                $new_width = $size;
                $new_height = $image_height * ($size / $image_width);
            } else {
                $new_height = $size;
                $new_width = $image_width * ($size / $image_height);
            }
        }
        $new_image = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height);
        return $new_image;
    }
}
