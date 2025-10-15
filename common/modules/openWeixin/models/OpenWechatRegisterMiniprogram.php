<?php

namespace common\modules\openWeixin\models;

use common\traits\ActiveQuery\StoreTrait;

/**
 * 注册企业小程序
 * This is the model class for table "{{%bloc_open_wechat_register_miniprogram}}".
 *
 * @property int $id
 * @property int|null $bloc_id
 * @property string|null $name 企业名（需与工商部门登记信息一致）
 * @property string|null $code 企业代码
 * @property int|null $code_type 企业代码类型
 * @property string|null $legal_persona_wechat 法人微信号
 * @property string|null $legal_persona_name 法人姓名
 * @property string|null $component_phone 第三方联系电话
 * @property int|null $errcode 错误码
 * @property string|null $errmsg 错误信息
 * @property int|null $status 状态
 * @property string|null $update_time
 * @property string|null $create_time
 */
class OpenWechatRegisterMiniprogram extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%bloc_open_wechat_register_miniprogram}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'code_type', 'errcode', 'status'], 'integer'],
            [['update_time', 'create_time'], 'safe'],
            [['name', 'code', 'legal_persona_name'], 'string', 'max' => 255],
            [['legal_persona_wechat', 'errmsg'], 'string', 'max' => 100],
            [['component_phone'], 'string', 'max' => 50],
            [['bloc_id', 'errcode', 'errmsg'], 'unique', 'targetAttribute' => ['bloc_id', 'errcode', 'errmsg']],
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
                'class' => \common\behaviors\SaveBehavior::className(),
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
            'name' => '企业名（需与工商部门登记信息一致）',
            'code' => '企业代码',
            'code_type' => '企业代码类型',
            'legal_persona_wechat' => '法人微信号',
            'legal_persona_name' => '法人姓名',
            'component_phone' => '第三方联系电话',
            'errcode' => '错误码',
            'errmsg' => '错误信息',
            'status' => '状态',
            'update_time' => 'Update Time',
            'create_time' => 'Create Time',
        ];
    }
}