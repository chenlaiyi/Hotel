<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2024-03-01 23:28:34
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-03-03 13:39:56
 */

namespace common\modules\officialaccount\models\form;

use common\modules\officialaccount\models\enums\WechatMaterialTypeEnum;
use common\modules\officialaccount\models\enums\WechatMaterialTypesEnum;
use common\modules\officialaccount\models\OfficialaccountWechatMedia;
use EasyWeChat\Kernel\Messages\Article;
use EasyWeChat\Kernel\Messages\Media;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\validators\Validator;
use yii\web\UploadedFile;

/**
 * 素材上传(表单)验证类
 * @package callmez\wechat\models
 */
class MediaForm extends Model
{
    /**
     * 素材类型
     * @var string
     */
    public $type;

    /**
     * 素材类别
     * @var string
     */
    public $material = WechatMaterialTypeEnum::MATERIAL_TEMPORARY;

    /**
     * 上传文件
     * @var UploadedFile
     */
    public $file;


    protected $wechat;

    /**
     * @inhertdoc
     */
    public function __construct($config = [])
    {
        $this->wechat = Yii::$app->wechat->app;
        parent::__construct($config);
    }

    /**
     * @inhertdoc
     */
    public function rules()
    {
        return [
            [['type', 'material', 'file'], 'required'],
            [['type'], 'in', 'range' => WechatMaterialTypeEnum::getConstantsByName()],
            [['material'], 'in', 'range' => WechatMaterialTypesEnum::getConstantsByName()],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'jpg, amr, mp3, mp4', 'maxSize' => 10485760], // 10MB
            [['file'], 'checkFile']
        ];
    }

    /**
     * 各类型上传文件验证
     * @param $attribute
     * @param $params
     */
    public function checkFile($attribute, $params)
    {
        // 按照类型 验证上传
        switch ($this->type) {
            case WechatMaterialTypesEnum::TYPE_IMAGE:
                $rule = [[$attribute], 'file', 'skipOnEmpty' => false, 'extensions' => 'jpg', 'maxSize' => 1048576]; // 1MB
                break;
            case WechatMaterialTypesEnum::TYPE_THUMB:
                $rule = [[$attribute], 'file', 'skipOnEmpty' => false, 'extensions' => 'jpg', 'maxSize' => 524288]; // 64KB
                break;
            case WechatMaterialTypesEnum::TYPE_VOICE:
                $rule = [[$attribute], 'file', 'skipOnEmpty' => false, 'extensions' => 'amr, mp3', 'maxSize' => 2097152]; // 2MB
                break;
            case WechatMaterialTypesEnum::TYPE_VIDEO:
                $rule = [[$attribute], 'file', 'skipOnEmpty' => false, 'extensions' => 'mp4', 'maxSize' => 10485760]; // 10MB
                break;
            default:
                return;
        }
        $validator = Validator::createValidator($rule[1], $this, (array) $rule[0], array_slice($rule, 2));
        $validator->validateAttributes($this);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type' => '素材类型',
            'material' => '素材类别',
            'file' => '素材'
        ];
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        /**
         * 本地保存
         */
        $path = '@attachment/officialaccount/' . md5($_SERVER['REQUEST_TIME_FLOAT']) . '/' . $this->file->name;
        $filePath = Yii::getAlias($path);
        FileHelper::createDirectory(dirname($filePath));
        $this->file->saveAs($filePath);


        // 按照类型 验证上传
        switch ($this->type) {
            case WechatMaterialTypesEnum::TYPE_IMAGE:
                $result = $this->wechat->material->uploadImage($filePath);
                // {
                //    "media_id":MEDIA_ID,
                //    "url":URL
                // }
                break;
            case WechatMaterialTypesEnum::TYPE_THUMB:
                $result = $this->wechat->material->uploadThumb($filePath);

                break;
            case WechatMaterialTypesEnum::TYPE_VOICE:
                $result = $this->wechat->material->uploadVoice($filePath);

                break;
            case WechatMaterialTypesEnum::TYPE_VIDEO:
                $result = $this->wechat->material->uploadVideo($filePath);

                break;
            case WechatMaterialTypesEnum::TYPE_ARTICLE:
                $mediaId = 0;
                $article = new Article([
                    'title' => 'xxx',
                    'thumb_media_id' => $mediaId,
                    //...
                ]);
                $result = $this->wechat->material->uploadArticle($article);

                break;
            default:
                $this->addError('material', '错误的素材类别');
                return false;

        }



        if (!$result) {
            $this->addError('file', '素材上传错误');
            return false;
        }

        $media = new OfficialaccountWechatMedia();
        $media->setAttributes([
            'mediaId' => $result['media_id'],
            'filename' => $this->file->name,
            'type' => $this->type,
            'material' => $this->material,
            'result' => $result
        ]);
        return $media->save();
    }
}
