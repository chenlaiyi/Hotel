<?php

namespace common\modules\officialaccount\models;

use common\traits\ActiveQuery\StoreTrait;

/**
 * This is the model class for table "{{%officialaccount_wechat_media}}".
 *
 * @property int $id
 * @property int|null $bloc_id
 * @property int|null $store_id
 * @property string|null $filename 文件名称
 * @property string|null $result 响应内容
 * @property int|null $type 媒体类型
 * @property string|null $url 微信素材地址
 * @property string|null $local_url 本地素材地址
 * @property int|null $status 同步状态
 * @property string|null $news_update_time 素材更新时间
 * @property string|null $tags 素材分组
 * @property string|null $media_id 素材ID
 * @property string|null $create_time
 */
class OfficialaccountWechatMedia extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%officialaccount_wechat_media}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id', 'status'], 'integer'],
            [['news_update_time', 'create_time'], 'safe'],
            [['type'], 'string', 'max' => 50],
            [['filename', 'result', 'media_id'], 'string', 'max' => 100],
            [['url', 'local_url', 'tags'], 'string', 'max' => 255],
        ];
    }

    /**
     * 行为.
     */
    public function behaviors(): array
    {
        /*自动添加创建和修改时间*/
        return [
            [
                'class' => \common\behaviors\SaveBehavior::class,
                'updatedAttribute' => 'update_time',
                'createdAttribute' => 'create_time',
                'time_type' => 'datetime',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'bloc_id' => 'Bloc ID',
            'store_id' => 'Store ID',
            'filename' => '文件名称',
            'result' => '响应内容',
            'type' => '媒体类型',
            'url' => '微信素材地址',
            'local_url' => '本地素材地址',
            'status' => '同步状态',
            'news_update_time' => '素材更新时间',
            'tags' => '素材分组',
            'media_id' => '素材ID',
            'create_time' => 'Create Time',
        ];
    }
}