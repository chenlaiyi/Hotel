<?php

namespace common\plugins\diandi_website\models;

use common\traits\ActiveQuery\StoreTrait;

/**
 * This is the model class for table "{{%diandi_website_post_info}}".
 *
 * @property int $id 主键ID
 * @property int|null $bloc_id
 * @property int|null $store_id
 * @property string $product_name 咨询的产品名称
 * @property string|null $remark 备注说明
 * @property string $inquiry_type 资讯类型（如：技术咨询、商务合作等）
 * @property string $contact 联系方式（电话/邮箱）
 * @property string $company_name 公司名称
 * @property string|null $create_time 创建时间
 * @property string|null $username
 * @property string|null $update_time
 * @property int|null $customer_id
 */
class DiandiWebsitePostInfo extends \yii\db\ActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_website_post_info}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id', 'customer_id', 'inquiry_type'], 'integer'],
            [['product_name', 'contact', 'company_name'], 'required'],
            [['remark'], 'string'],
            [['create_time', 'update_time'], 'safe'],
            [['product_name', 'contact', 'company_name', 'username'], 'string', 'max' => 255]
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
            'id' => '主键ID',
            'bloc_id' => 'Bloc ID',
            'store_id' => 'Store ID',
            'product_name' => '咨询的产品名称',
            'remark' => '备注说明',
            'inquiry_type' => '资讯类型（如：技术咨询、商务合作等）',
            'contact' => '联系方式（电话/邮箱）',
            'company_name' => '公司名称',
            'create_time' => '创建时间',
            'username' => 'Username',
            'update_time' => 'Update Time',
            'customer_id' => 'Customer ID',
        ];
    }
}