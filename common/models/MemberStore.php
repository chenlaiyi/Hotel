<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2020-05-12 20:49:40
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-03-10 19:13:00
 */

namespace common\models;

use common\components\ActiveRecord\YiiActiveRecord;
use diandi\addons\models\Bloc;
use diandi\addons\models\BlocStore;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "diandi_user_bloc".
 *
 * @public int         $id
 * @public int|null    $member_id     管理员id
 * @public int|null    $bloc_id     集团id
 * @public int|null    $store_id    子公司id
 * @public string|null $create_time
 * @public string|null $update_time
 *
 * @property-read mixed $bloc
 * @property-read mixed $store
 * @property-read mixed $user
 */
class MemberStore extends YiiActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%member_store}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['member_id', 'bloc_id', 'store_id', 'status', 'is_default'], 'integer'],
            ['is_default', 'default', 'value' => 0],
            ['status', 'default', 'value' => 1],
            [['create_time', 'update_time'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'create_time', // 自己根据数据库字段修改
                'updatedAtAttribute' => 'update_time', // 自己根据数据库字段修改, // 自己根据数据库字段修改
                'value' => function () {return time(); },
            ],
        ];
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'member_id']);
    }

    public function getBloc(): ActiveQuery
    {
        return $this->hasOne(Bloc::class, ['bloc_id' => 'bloc_id']);
    }

    public function getStore(): ActiveQuery
    {
        return $this->hasOne(BlocStore::class, ['store_id' => 'store_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'member_id' => '管理员id',
            'bloc_id' => '集团id',
            'store_id' => '子公司id',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
