<?php
namespace common\models;

use common\components\ActiveRecord\YiiActiveRecord;
use Yii;

class AuthAssignmentGroup extends YiiActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%auth_assignment_group}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'group_id', 'item_id', 'item_name', 'user_id', 'created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'group_id'   => 'group_id',
            'item_id'    => 'item_id',
            'item_name'  => 'item_name',
            'user_id'    => 'user_id',
            'created_at' => 'created_at',
        ];
    }
}
