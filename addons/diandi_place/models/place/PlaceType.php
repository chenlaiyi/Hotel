<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-05-08 13:15:52
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-05-24 13:13:44
 */
namespace addons\diandi_place\models\place;
use addons\diandi_place\models\landlord\PlaceLandlordType;
use common\behaviors\SaveBehavior;
use common\components\ActiveRecord\YiiActiveRecord;
use common\plugins\diandi_auth\models\cloud\DiandiAuthAddonsCate;
use common\traits\ActiveQuery\StoreTrait;
use Yii;
use yii\db\ActiveQuery;
/**
 * This is the model class for table "{{%diandi_place_type}}".
 *
 * @public int $id
 * @private  bool $is_default
 * @public string|null $title
 * @public int|null $bloc_id
 * @public int|null $store_id
 * @public string|null $create_time
 * @public string|null $update_time
 *
 * @property-read ActiveQuery $landlordType
 * @property-read ActiveQuery $roomType
 */
class PlaceType extends DiandiAuthAddonsCate
{
    use StoreTrait;
    /**
     * 行为.
     */
    public function behaviors(): array
    {
        /*自动添加创建和修改时间*/
        return [
            [
                'class' => SaveBehavior::class,
                'updatedAttribute' => 'update_time',
                'createdAttribute' => 'create_time',
                'time_type' => 'datetime',
            ],
        ];
    }
    function getLandlordType(): ActiveQuery
    {
        return $this->hasOne(PlaceLandlordType::class, ['type_id' => 'id']);
    }
    function getRoomType(): ActiveQuery
    {
        return $this->hasMany(PlaceTypeList::class,['place_type_id'=>'id']);
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'bloc_id' => 'Bloc ID',
            'store_id' => 'Store ID',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
