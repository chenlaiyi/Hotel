<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-31 08:18:56
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-20 10:24:09
 */
namespace addons\diandi_place\models\place;
use admin\models\addons\models\Bloc;
use common\components\ActiveRecord\YiiActiveRecord;
use common\traits\ActiveQuery\BlocLinkTrait;
use Yii;
/**
 * 酒店品牌
 * This is the model class for table "{{%diandi_place_brand}}".
 *
 * @public int $id
 * @public int|null $bloc_id 公司ID
 * @public int|null $store_id 商户ID
 * @public string|null $title 品牌名称
 * @public int|null $displayorder 排序
 * @public int|null $status 状态1启用0未启用
 * @public string|null $create_time
 * @public string|null $update_time
 *
 * @property-read mixed $bloc
 */
class PlaceBrand extends YiiActiveRecord
{
    use BlocLinkTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_place_brand}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id',  'displayorder', 'status','member_id'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['title'], 'string', 'max' => 255],
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
                'time_type' => 'datetime'
            ],
        ];
    }
    public function getBloc(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Bloc::class,['bloc_id'=>'bloc_id']);
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('place', 'ID'),
            'bloc_id' => Yii::t('place', '公司ID'),
            'title' => Yii::t('place', '品牌名称'),
            'displayorder' => Yii::t('place', '排序'),
            'status' => Yii::t('place', '状态1启用0未启用'),
            'create_time' => Yii::t('place', 'Create Time'),
            'update_time' => Yii::t('place', 'Update Time'),
        ];
    }
}
