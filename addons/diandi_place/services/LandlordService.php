<?php
namespace addons\diandi_place\services;
use addons\diandi_place\models\enums\LandlordStatusEnums;
use addons\diandi_place\models\enums\PlaceMemberAuthStatusEnums;
use addons\diandi_place\models\landlord\PlaceLandlord;
use addons\diandi_place\models\landlord\PlaceLandlordType;
use addons\diandi_place\models\member\PlaceMember;
use addons\diandi_place\models\place\PlaceList;
use addons\diandi_place\models\place\PlaceType;
use admin\models\User;
use api\models\DdMember;
use common\components\ActiveRecord\YiiActiveRecord;
use common\helpers\ErrorsHelper;
use common\helpers\ImageHelper;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use common\models\UserStore;
use common\services\BaseService;
use Throwable;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\db\Query;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
/**
 * 房东服务
 */
class LandlordService extends BaseService
{
    public static function initAuth($member_id): array
    {
        $PlaceLand = PlaceLandlord::find()->where(['member_id'=>$member_id])->asArray()->one();
        if (empty($PlaceLand)){
            return [];
        }
        $PlaceLand['user'] = UserStore::find()->where(['user_id'=>$PlaceLand['user_id'],'is_default'=>1])->select(['bloc_id','store_id'])->asArray()->one();
        return $PlaceLand??[];
    }
    /**
     * 设置业务类型
     * @param int $user_id
     * @param array $config
     * @return array
     * @throws NotFoundHttpException
     */
    public static function setType(int $user_id,array $config=[]): array
    {
        $PlaceLandlordType =  new PlaceLandlordType();
        $PlaceLandlordType->deleteAll([
            'user_id' => $user_id
        ]);
        $type_ids = PlaceType::find()->findBloc()->select('id')->column();
        $setTypeId = array_column($config,'type_id');
        $diff = array_diff($type_ids,$setTypeId);
        foreach ($diff as $type_id) {
            $_PlaceLandlordType = clone $PlaceLandlordType;
            $_PlaceLandlordType->setAttributes([
                'type_id' => $type_id,
                'type_status' => 0,
                'user_id' => $user_id
            ]);
            if (!$_PlaceLandlordType->save()){
                $msg = ErrorsHelper::getModelError($_PlaceLandlordType);
                return ResultHelper::json(400, $msg);
            }
        }
        foreach ($config as $item) {
            $_PlaceLandlordType = clone $PlaceLandlordType;
            $_PlaceLandlordType->setAttributes([
                'type_id' => $item['type_id'],
                'type_status' => $item['type_status'],
                'user_id' => $user_id
            ]);
            if (!$_PlaceLandlordType->save()){
                $msg = ErrorsHelper::getModelError($_PlaceLandlordType);
                return ResultHelper::json(400, $msg);
            }
        }
        return ResultHelper::json(200, '设置成功');
    }
    /**
     * 房东身份认证
     * @param int $member_id
     * @param mixed $realname
     * @param mixed $icard_code
     * @param mixed $icard_front
     * @param mixed $icard_back
     * @return array
     * @throws NotFoundHttpException
     */
    public static function landlordIdentity(int $member_id, mixed $realname, mixed $icard_code, mixed $icard_front, mixed $icard_back)
    {
        $PlaceLand = PlaceLandlord::find()->where(['member_id'=>$member_id])->one();
        // 重复认证
        if ($PlaceLand->status == LandlordStatusEnums::AUTHPASS) {
            return ResultHelper::json(400, '请勿重复认证', []);
        }
        if ($PlaceLand->status == LandlordStatusEnums::AUTHFAIL) {
            return ResultHelper::json(400, '请重新认证', []);
        }
        $PlaceLand->realname          = $realname;
        $PlaceLand->icard_code        = $icard_code;
        $PlaceLand->icard_front       = $icard_front;
        $PlaceLand->icard_back        = $icard_back;
        $PlaceLand->status = LandlordStatusEnums::AUTHPASS;
        if (!$PlaceLand->save()) {
            $msg = ErrorsHelper::getModelError($PlaceLand);
            return ResultHelper::json(400, $msg, []);
        }
        return $PlaceLand?$PlaceLand->toArray():[];
    }
    /**
     * 编辑资料
     * @param int $user_id
     * @param string $realname
     * @param string $avatar
     * @param int $gender
     * @param string $idcard
     * @return bool|false|int
     * @throws StaleObjectException
     * @throws Throwable
     * @date 2023-04-24
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function edit(int $user_id, string $realname, string $avatar, int $gender, string $idcard = '')
    {
        $member = PlaceLandlord::findOne(['user_id'=>$user_id]);
        // 未认证 || 认证失败的 可以修改姓名身份证号
        if (in_array($member->icard_auth_status, [
            PlaceMemberAuthStatusEnums::NO_AUTH, PlaceMemberAuthStatusEnums::AUTH_FAIL
        ])) {
            $member->icard_code = $idcard;
            $member->realname   = $realname;
        }
        $member->avatar = $avatar;
        DdMember::updateAll([
            'gender' => $gender
        ], [
            'user_id'=>$user_id
        ]);
        return $member->update();
    }
    /**
     * 身份认证
     * @param int $user_id
     * @param string $realname
     * @param string $icard_code
     * @param string $icard_front
     * @param string $icard_back
     * @return PlaceMember|array
     * @date 2023-04-24
     * @throws NotFoundHttpException
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function identity(int $user_id, string $realname, string $icard_code, string $icard_front, string $icard_back): array|PlaceLandlord
    {
        $member = PlaceLandlord::findOne(['user_id'=>$user_id]);
        // 未认证 || 认证失败的可以进行房东认证
        if (in_array($member->icard_auth_status, [
            PlaceMemberAuthStatusEnums::AUTHING, PlaceMemberAuthStatusEnums::AUTH_SUCCESS
        ])) {
            $enum = PlaceMemberAuthStatusEnums::listData();
            return ResultHelper::json(400, $enum[$member->icard_auth_status] . ',请勿重复认证', []);
        }
        $member->realname          = $realname;
        $member->icard_code        = $icard_code;
        $member->icard_front       = $icard_front;
        $member->icard_back        = $icard_back;
        $member->icard_auth_status = PlaceMemberAuthStatusEnums::AUTHING;
        if (!$member->save()) {
            $msg = ErrorsHelper::getModelError($member);
            return ResultHelper::json(400, $msg, []);
        }
        return $member;
    }
    /**
     * 资料信息
     * @param int $member_id
     * @return array
     * @date 2023-04-24
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function detail(int $member_id): array
    {
        $member = self::info($member_id);
        return [
            'member_id'         => $member_id,
            'mobile'            => $member['user']['mobile'],
            'avatar'            => ImageHelper::tomedia($member['avatar'], 'avatar.jpg'),
            'idcard'            => $member['icard_code'],
            'realname'          => $member['realname'],
            'icard_auth_status' => $member['icard_auth_status'],
            'store_id'          => $member['store_id'],
            'bloc_id'           => $member['bloc_id'],
            'status'            => $member['status'],
            'gender'            => $member['gender']
        ];
    }
    public static function info($user_id): array|YiiActiveRecord
    {
        $HotelMember = new PlaceLandlord();
        $member = $HotelMember->find()->where(['user_id' => $user_id])->with(['user'])->asArray()->one();
        if (empty($member)) {
            $HotelMember->setAttributes([
                'user_id' => $user_id,
                'realname' => '星级用户'
            ]) && $HotelMember->save();
            $msg = ErrorsHelper::getModelError($HotelMember);
            if ($msg) {
                return ResultHelper::json(400, $msg);
            }
            $member = $HotelMember->find()->where(['user_id' => $user_id])->with(['user'])->asArray()->one();
        }
        $member['avatar'] = ImageHelper::tomedia($member['avatar'], 'avatar15.jpg');
        return $member;
    }
    /**
     * 修改密码
     * @param int $member_id
     * @param string $password
     * @param int $code
     * @return DdMember|array|object[]|string[]
     * @throws ErrorException
     * @throws Exception
     * @date 2023-04-24
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function reSetPassWord(int $member_id, string $password, int $code): array|User
    {
        $member = User::findIdentity($member_id);
        $mobile = $member->mobile;
        $sendcode = Yii::$app->cache->get($mobile . '_code');
        if (empty($sendcode)) {
            return ResultHelper::json(401, '验证码已过期');
        }
        if ($code != $sendcode) {
            return ResultHelper::json(401, '验证码错误');
        }
        $member->password_hash = Yii::$app->security->generatePasswordHash($password);
        $member->generatePasswordResetToken();
        if ($member->save()) {
            Yii::$app->user->logout();
            // 清除验证码
            Yii::$app->cache->delete($mobile . '_code');
            return $member;
        }
        $msg = ErrorsHelper::getModelError($member);
        return ResultHelper::json(400, $msg, []);
    }
    /**
     * 创建密码
     * @return void
     * @date 2023-04-24
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function createPassWord()
    {
        # code...
    }
    /**
     * 创建加密字符串
     * @return void
     * @date 2023-04-24
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function createAuthKey()
    {
        # code...
    }
    /**
     * 获取房东列表
     * @param mixed $page
     * @param mixed $pageSize
     * @param mixed $keywords
     * @param int $user_id
     * @return array
     * @throws Throwable
     */
    public static function listAll(mixed $page, mixed $pageSize, mixed $keywords, int $user_id)
    {
        if (!self::isSuperLandlord($user_id)){
            return ResultHelper::json(400, '非超级房东，无法查看全部房东', [
                'list' => [],
                'total' => 0
            ]);
        }
        $query = PlaceLandlord::find()->where(['>','user_id',0]);
//        $query->where(['user_id' => $user_id]);
        $query->andFilterWhere(['like', 'realname', $keywords]);
        $query->orFilterWhere(['like', 'icard_code', $keywords]);
        $query->orFilterWhere(['like', 'mobile', $keywords]);
        /**
         * 分页
         */
        $count = $query->count();
        $list = $query->with(['building'])->offset(($page - 1) * $pageSize)->limit($pageSize)->asArray()->all();
        return [
            'list' => $list,
            'sql' =>  $query->createCommand()->getRawSql(),
            'total' => $count
        ];
    }
    /**
     * 给所有房东初始化楼栋
     */
    public static function initAllBuilding()
    {
        try {
            $query = (new Query())
                ->select(['user_id', 'realname', 'mobile', 'bloc_id', 'store_id', 'id'])
                ->from(PlaceLandlord::tableName())
                ->where(['>', 'user_id', 0])
                ->andWhere(['place_list_id' => 0]);
            // 获取查询结果
            $landlords = $query->all();
             foreach ($landlords as $landlord) {
                self::initBuilding($landlord['user_id'], $landlord['realname'],$landlord['mobile'], $landlord['bloc_id'],$landlord['store_id'],$landlord['id']);
            }
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }
    /**
     * 判断是否是超级房东
     */
    public static function isSuperLandlord(int $user_id): bool
    {
        $member = PlaceLandlord::find()->where(['user_id' => $user_id])->one();
        if (empty($member)) {
            return false;
        }
        if ($member->is_admin) {
            return true;
        }
        return false;
    }
    /**
     * 房东是否有房
     */
    public static function hasBuilding(int $user_id): bool
    {
        $member = PlaceLandlord::find()->where(['user_id' => $user_id])->one();
        if (empty($member)) {
            return false;
        }
        if ($member->place_list_id) {
            return true;
        }
        return false;
    }

    /**
     * 给房东初始化楼栋
     * @throws \Exception|Throwable
     */
    public static function initBuilding($user_id, string $realname,string $mobile, int $bloc_id,int $store_id,$landlord_id)
    {
        if (!self::hasBuilding($user_id)){
            $PlaceList = new PlaceList();
            $PlaceList->load([
                'name' => $realname,
                'bloc_id' => $bloc_id,
                'store_id' => $store_id,
                'address' => '',
                'phone'=>  $mobile,
                'landlord_id' => $landlord_id,
                'province' => 0,
                'city' => 0,
                'county' => 0,
                'status' => 1,
                'type' => 1,
            ],'');
            if (!$PlaceList->save(false)){
                $msg = ErrorsHelper::getModelError($PlaceList);
                throw new \Exception('创建楼栋:'.$msg);
            }
            /**
             * 更新房东的place_list_id
             */
            PlaceLandlord::updateAll(['place_list_id' => $PlaceList->id], ['id' => $landlord_id]);
        }
    }
    /**
     * 给客商注册初始房东
     * @throws \Exception|Throwable
     */
    public static function registerUser($realname, $mobile): int
    {
        try {
            $User = new User();
            $mobile = self::filterMobile($mobile);
            $email = trim($mobile).'@qq.com';
            $password = '123456';
            $res = $User->signup($realname, trim($mobile) , $email, $password);
            if (is_array($res) && $res && key_exists('user',$res)){
                return $res['user']['id'];
            }elseif (is_array($res) && $res && key_exists('code',$res) && $res['code'] == 400){
                return $res['user']['id'];
            }
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }
    /**
     * 从字符串中过滤出手机号
     */
    public static function filterMobile($str): string
    {
        $pattern = '/1[3-9]\d{9}/';
        preg_match($pattern, $str, $matches);
        if (isset($matches[0])) {
            return $matches[0];
        }
        return '';
    }
}