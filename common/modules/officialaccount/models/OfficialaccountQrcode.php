<?php

namespace common\modules\officialaccount\models;

use common\models\DdUser;
use common\models\User;
use common\traits\ActiveQuery\StoreTrait;

/**
 * This is the model class for table "{{%officialaccount_qrcode}}".
 *
 * @property int $id
 * @property int $store_id
 * @property int|null $bloc_id
 * @property int|null $user_id
 * @property int|null $member_id
 * @property string $type
 * @property int $extra
 * @property int $qrcid
 * @property string $scene_str
 * @property string $name
 * @property string $keyword
 * @property int $model
 * @property string $ticket
 * @property string $url
 * @property int $expire
 * @property int $subnum
 * @property int|null $update_time
 * @property int $create_time
 * @property int $status
 */
class OfficialaccountQrcode extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%officialaccount_qrcode}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['extra', 'qrcid', 'scene_str', 'ticket', 'url', 'expire', 'subnum', 'status'], 'required'],
            [['type', 'store_id', 'bloc_id', 'user_id', 'member_id', 'extra', 'qrcid', 'model', 'expire', 'subnum', 'status'], 'integer'],
            [['update_time', 'create_time','end_time'], 'safe'],
            [['scene_str'], 'string', 'max' => 64],
            [['name','addons'], 'string', 'max' => 50],
            [['keyword', 'name', 'keyword', 'model'], 'string', 'max' => 100],
            [['ticket','origin_url','data'], 'string', 'max' => 250],
            [['url'], 'string', 'max' => 256],
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
                'adminAttribute' => 'user_id',
                'time_type' => 'datetime',
            ],
        ];
    }

    function getUser()
    {
        return $this->hasOne(DdUser::class,['id'=>'user_id'])->select(['username','mobile','id']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'bloc_id' => 'Bloc ID',
            'user_id' => 'User ID',
            'member_id' => 'Member ID',
            'type' => 'Type',
            'extra' => 'Extra',
            'qrcid' => 'Qrcid',
            'scene_str' => 'Scene Str',
            'name' => 'Name',
            'keyword' => 'Keyword',
            'model' => 'Model',
            'ticket' => 'Ticket',
            'url' => 'Url',
            'expire' => 'Expire',
            'subnum' => 'Subnum',
            'update_time' => 'Update Time',
            'create_time' => 'Create Time',
            'status' => 'Status',
        ];
    }
}