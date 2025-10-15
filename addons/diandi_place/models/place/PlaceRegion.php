<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-09 11:24:20
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-10 15:07:49
 */
namespace addons\diandi_place\models\place;
use common\traits\ActiveQuery\StoreTrait;
use Yii;
/**
 * 酒店区域
 * This is the model class for table "{{%diandi_place_region}}".
 *
 * @public int $id
 * @public int|null $pid
 * @public string|null $shortname
 * @public string|null $name
 * @public string|null $merger_name
 * @public int|null $level
 * @public string|null $pinyin
 * @public string|null $code
 * @public string|null $zip_code
 * @public string|null $first
 * @public string|null $lng
 * @public string|null $lat
 */
class PlaceRegion extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_place_region}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['pid', 'level','bloc_id','store_id'], 'integer'],
            [['shortname', 'name', 'pinyin', 'code', 'zip_code', 'lng', 'lat'], 'string', 'max' => 100],
            [['merger_name'], 'string', 'max' => 255],
            [['first'], 'string', 'max' => 50],
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
    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('place', 'ID'),
            'pid' => Yii::t('place', 'Pid'),
            'shortname' => Yii::t('place', 'Shortname'),
            'name' => Yii::t('place', 'Name'),
            'merger_name' => Yii::t('place', 'Merger Name'),
            'level' => Yii::t('place', 'Level'),
            'pinyin' => Yii::t('place', 'Pinyin'),
            'code' => Yii::t('place', 'Code'),
            'zip_code' => Yii::t('place', 'Zip Code'),
            'first' => Yii::t('place', 'First'),
            'lng' => Yii::t('place', 'Lng'),
            'lat' => Yii::t('place', 'Lat'),
        ];
    }
}
