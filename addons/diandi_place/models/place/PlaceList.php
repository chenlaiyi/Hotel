<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-05 11:05:25
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-28 10:31:13
 */
namespace addons\diandi_place\models\place;
use addons\diandi_place\models\room\PlaceRoom;
use addons\diandi_place\models\room\PlaceRoomSlide;
use addons\diandi_place\models\room\PlaceRoomType;
use common\behaviors\SaveBehavior;
use common\helpers\loggingHelper;
use common\models\DdRegion;
use common\models\UserMember;
use common\traits\ActiveQuery\StoreLinkTrait;
use common\traits\ActiveQuery\StoreTrait;
use diandi\addons\models\BlocStore;
use diandi\addons\models\StoreLabel;
use diandi\addons\models\StoreLabelLink;
use Yii;
use yii\db\ActiveQuery;
use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;
/**
 * 酒店列表
 * This is the model class for table "{{%diandi_place_list}}".
 *
 * @public int         $id
 * @public int|null    $bloc_id      公司ID
 * @public int|null    $store_id     商户ID
 * @public string|null $name        酒店名称
 * @public float|null  $lng          经度
 * @public float|null  $lat          维度
 * @public string|null $address      具体地址
 * @public int|null    $province   省份
 * @public int|null    $city   城市
 * @public int|null    $county   区县
 * @public int|null    $roomcount    房间总量
 * @public int|null    $status       酒店状态
 * @public string|null $phone        联系电话
 * @public string|null $mail         联系邮箱
 * @public string|null $thumb        酒店图片
 * @public string|null $description  酒店简介
 * @public string|null $content      酒店介绍
 * @public string|null $traffic      周边交通
 * @public string|null $thumbs       酒店相册
 * @public string|null $sales
 * @public int|null    $displayorder 排序
 * @public int|null    $level        酒店星级
 * @public string|null $device       服务设施
 * @public int|null    $brandid      所属品牌
 * @public int         $language      语言类型标志/默认中文0
 * @public string|null $create_time
 * @public string|null $update_time
 *
 * @property-read ActiveQuery $regionP
 * @property-read ActiveQuery $coupon
 * @property-read ActiveQuery $landlord
 * @property-read ActiveQuery $store
 * @property-read ActiveQuery $room
 * @property-read ActiveQuery $regionA
 * @property-read ActiveQuery $regionC
 * @property-read ActiveQuery $tier
 * @property-read ActiveQuery $service
 * @property-read ActiveQuery $rim
 * @property-read ActiveQuery $comment
 * @property-read ActiveQuery $brand
 * @property-read int $user
 * @property-read ActiveQuery $roomType
 */
class PlaceList extends ActiveRecord
{
    use StoreLinkTrait;
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    public function getUser()
    {
        $appId = Yii::$app->id;
        $user_id = 0;
        $member_id = 0;
        switch ($appId) {
            case 'app-admin':
                if (!empty(Yii::$app->user->identity->user_id)) {
                    $user_id = Yii::$app->user->identity->user_id;
                }
                break;
            case 'app-api':
                if (!empty(Yii::$app->user->identity->member_id)) {
                    $member_id = Yii::$app->user->identity->member_id??0;
                }
                $user_id = PlaceLandlord::find()->where(['member_id' => $member_id])->select('user_id')->scalar();
                if (empty($user_id)){
                    $user_id =UserMember::find()->where(['member_id'=>$member_id])->select('user_id')->scalar();
                    PlaceLandlord::updateAll(['user_id'=>$user_id],['member_id'=>$member_id]);
                }
                break;
        }
        loggingHelper::writeLog('diandi_place', 'getUser', '获取用户ID', [
            'user_id' => $user_id,
            'appId' => $appId,
            'member_id' => $member_id,
        ]);
        return $user_id;
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_place_list}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['type'], 'required'],
            [['store_id'], 'unique','on'=>'create'],
            [['is_show', 'address_show', 'bloc_id', 'province', 'city', 'county', 'roomcount', 'status', 'displayorder', 'level', 'brandid', 'language', 'type', 'landlord_id','apartment_type','customer_id'], 'integer'],
            [['price','lng', 'lat', 'comment_start', 'comment_num'], 'number'],
            [['lease_type','time_length'],'integer'],
            [['description', 'content', 'traffic', 'thumbs', 'sales', 'device'], 'string'],
            [['create_time', 'update_time'], 'safe'],
            [['name', 'address', 'phone', 'mail', 'thumb'], 'string', 'max' => 255],
            [['bloc_id', 'name'], 'unique', 'targetAttribute' => ['bloc_id', 'name'], 'message' => '酒店名称重复']
        ];
    }
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['price','lease_type','time_length','bloc_id','store_id','name','lng','lat','is_show','address_show','type','address','province','city','county','apartment_type','roomcount','status','phone','mail','thumb','description','content','traffic','thumbs','sales','displayorder','comment_num','comment_start','level','device','brandid','language','landlord_id','create_time','update_time'];
        $scenarios[self::SCENARIO_UPDATE] = ['price','lease_type','time_length','bloc_id','name','lng','lat','is_show','address_show','type','address','province','city','county','apartment_type','roomcount','status','phone','mail','thumb','description','content','traffic','thumbs','sales','displayorder','comment_num','comment_start','level','device','brandid','language','landlord_id','create_time','update_time'];
        return $scenarios;
    }
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
    public function getStore(): ActiveQuery
    {
        return $this->hasOne(BlocStore::class, ['store_id' => 'store_id']);
    }
    public function getComment(): ActiveQuery
    {
        return $this->hasOne(PlaceComment::class, ['hotel_id' => 'id'])->with(['member'])->orderBy([
            'create_time' => SORT_DESC
        ]);
    }
    /**
     * 酒店服务
     * @return ActiveQuery
     * @date 2023-06-05
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function getService(): ActiveQuery
    {
        return $this->hasMany(PlaceServer::class, ['hotel_id' => 'id']);
    }
    public function getCoupon(): ActiveQuery
    {
        return $this->hasMany(PlaceCoupon::class, ['store_id' => 'store_id']);
    }
    /**
     * 酒店周边.
     *
     * @return ActiveQuery
     * @date 2023-03-26
     *
     * @example
     *
     * @author Wang Chunsheng
     *
     * @since
     */
    public function getRim(): ActiveQuery
    {
        return $this->hasMany(PlaceRim::class, ['hotel_id' => 'id']);
    }
    /**
     * 一级区域
     *
     * @return ActiveQuery
     * @date 2023-03-26
     *
     * @example
     *
     * @author Wang Chunsheng
     *
     * @since
     */
    public function getRegionP(): ActiveQuery
    {
        return $this->hasOne(DdRegion::class, ['id' => 'province']);
    }
    /**
     * 二级区域
     *
     * @return ActiveQuery
     * @date 2023-03-26
     *
     * @example
     *
     * @author Wang Chunsheng
     *
     * @since
     */
    public function getRegionC(): ActiveQuery
    {
        return $this->hasOne(DdRegion::class, ['id' => 'city']);
    }
    /**
     * 三级区域
     *
     * @return ActiveQuery
     * @date 2023-03-26
     *
     * @example
     *
     * @author Wang Chunsheng
     *
     * @since
     */
    public function getRegionA(): ActiveQuery
    {
        return $this->hasOne(DdRegion::class, ['id' => 'county']);
    }
    /**
     * 酒店品牌.
     *
     * @return ActiveQuery
     * @date 2023-03-26
     *
     * @example
     *
     * @author Wang Chunsheng
     *
     * @since
     */
    public function getBrand(): ActiveQuery
    {
        return $this->hasOne(PlaceBrand::class, ['id' => 'brandid']);
    }
    /**
     * 楼层
     *
     * @return ActiveQuery
     * @date 2023-05-08
     *
     * @example
     *
     * @author Wang Chunsheng
     *
     * @since
     */
    public function getTier(): ActiveQuery
    {
        return $this->hasMany(PlaceTier::class, ['hotel_id' => 'id'])->with(['unit']);
    }
    /**
     * 房间.
     *
     * @return ActiveQuery
     * @date 2023-05-08
     *
     * @example
     *
     * @author Wang Chunsheng
     *
     * @since
     */
    public function getRoom(): ActiveQuery
    {
        return $this->hasMany(PlaceRoom::class, ['hotel_id' => 'id'])->with(['server']);
    }
    /**
     * 房型.
     *
     * @return ActiveQuery
     * @date 2023-06-05
     *
     * @example
     *
     * @author wang chunSheng
     *
     * @since
     */
    public function getRoomType(): ActiveQuery
    {
        return $this->hasMany(PlaceRoomType::class, ['type_id' => 'type']);
    }
    /**
     * 房东
     * @return ActiveQuery
     * @date 2023-06-05
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function getLandlord(): ActiveQuery
    {
        return $this->hasOne(PlaceLandlord::class, ['bloc_id' => 'bloc_id'])->where(['is_admin'=>1])->with(['member']);
    }
    public function getLabel()
    {
        return $this->hasMany(StoreLabelLink::class,['store_id'=>'store_id']);
    }
    public function getSlide()
    {
        return $this->hasMany(PlaceRoomSlide::class,['store_id'=>'store_id']);
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('place', 'ID'),
            'bloc_id' => Yii::t('place', '公司ID'),
            'type' => Yii::t('place', '酒店类型'),
            'name' => Yii::t('place', '酒店名称'),
            'landlord_id' => Yii::t('place', '房东'),
            'lng' => Yii::t('place', '经度'),
            'lat' => Yii::t('place', '维度'),
            'address' => Yii::t('place', '具体地址'),
            'province' => Yii::t('place', '省份'),
            'city' => Yii::t('place', '城市'),
            'county' => Yii::t('place', '区县'),
            'roomcount' => Yii::t('place', '房间总量'),
            'status' => Yii::t('place', '酒店状态'),
            'phone' => Yii::t('place', '联系电话'),
            'mail' => Yii::t('place', '联系邮箱'),
            'thumb' => Yii::t('place', '酒店图片'),
            'description' => Yii::t('place', '酒店简介'),
            'content' => Yii::t('place', '酒店介绍'),
            'traffic' => Yii::t('place', '周边交通'),
            'thumbs' => Yii::t('place', '酒店相册'),
            'sales' => Yii::t('place', 'Sales'),
            'displayorder' => Yii::t('place', '排序'),
            'level' => Yii::t('place', '酒店星级'),
            'device' => Yii::t('place', '服务设施'),
            'brandid' => Yii::t('place', '所属品牌'),
            'language' => Yii::t('place', '语言类型标志/默认中文0'),
            'create_time' => Yii::t('place', 'Create Time'),
            'update_time' => Yii::t('place', 'Update Time'),
        ];
    }
}
