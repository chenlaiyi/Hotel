<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-09 11:23:39
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-10 15:07:47
 */
namespace addons\diandi_place\models\place;
use common\traits\ActiveQuery\StoreTrait;
use Yii;
/**
 * 评价星级
 * This is the model class for table "{{%diandi_place_comment_stars}}".
 *
 * @public int $id id
 * @public int $bloc_id 公司ID
 * @public int $store_id 商户ID
 * @public int|null $comment_id 评价ID
 * @public string|null $create_time 创建时间
 * @public string|null $update_time 更新时间
 * @public string|null $title 评论内容
 * @public int|null $start_num 评价星级
 */
class PlaceCommentStars extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%diandi_place_comment_stars}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id', 'comment_id', 'start_num'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['title'], 'string', 'max' => 100],
        ];
    }
    /**
     * 行为.
     */
    public function behaviors()
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
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('place', 'id'),
            'bloc_id' => Yii::t('place', '公司ID'),
            'store_id' => Yii::t('place', '商户ID'),
            'comment_id' => Yii::t('place', '评价ID'),
            'create_time' => Yii::t('place', '创建时间'),
            'update_time' => Yii::t('place', '更新时间'),
            'title' => Yii::t('place', '评论内容'),
            'start_num' => Yii::t('place', '评价星级'),
        ];
    }
}
