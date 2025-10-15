<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-18 10:21:38
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-21 09:52:46
 */

namespace admin\modules\customer\controllers;

use admin\controllers\AController;
use admin\models\DdApiAccessToken;
use admin\models\User as adminUser;
use admin\modules\customer\models\CustomerUser;
use admin\modules\customer\models\forms\EdituserinfoForm;
use admin\modules\customer\models\forms\LoginForm;
use admin\modules\customer\models\searchs\SearchCustomerUser;
use admin\modules\customer\services\AccessTokenService;
use admin\services\AuthService;
use admin\services\UserService;
use common\helpers\ErrorsHelper;
use common\helpers\ImageHelper;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use common\helpers\StringHelper;
use common\models\DdWebsiteContact;
use common\models\forms\PasswdForm;
use common\models\User;
use common\models\UserBloc;
use common\models\UserStore;
use common\models\WebsiteStationGroup;
use common\plugins\diandi_auth\models\DiandiAuthDepartments;
use common\services\admin\AccessTokenService as AdminAccessTokenService;
use common\services\common\WebsiteGroup;
use diandi\addons\models\ActionLog;
use diandi\addons\models\AddonsUser;
use diandi\addons\models\form\Api;
use diandi\admin\components\UserStatus;
use diandi\admin\models\AuthAssignmentGroup;
use Throwable;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\data\Pagination;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `app` module
 */
class UserController extends AController
{
    public $modelClass = 'admin\modules\customer\models\User';

    protected array $authOptional = ['login', 'signup', 'repassword', 'sendcode', 'forgetpass', 'refresh', 'ceshi', 'usertype'];

    public int $searchLevel = 0;

    public string $modelSearchName = 'SearchCustomerUser';

    /**
     * Lists all User models.
     *
     * @return array
     */
    public function actionIndex(): array
    {
        $searchModel  = new SearchCustomerUser();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return ResultHelper::json(200, '获取成功', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSignup(): array
    {
        $User            = new adminUser();
        $data            = Yii::$app->request->post();
        $username        = $data['username'] ?? '';
        $mobile          = $data['mobile'] ?? '';
        $email           = $data['email'] ?? '';
        $password        = $data['password'];
        $invitation_code = trim($data['invitation_code'] ?? '');

        if (empty($username)) {
            return ResultHelper::json(401, '用户名不能为空');
        }
        if (empty($mobile)) {
            return ResultHelper::json(401, '手机号不能为空');
        }
        if (empty($password)) {
            return ResultHelper::json(401, '密码不能为空');
        }
        $settings = Yii::$app->settings;
        $settings->invalidateCache();
        $is_send_code = $settings->get('Website', 'is_send_code');
        if ((int) $is_send_code === 1) {
            $code = Yii::$app->request->input('sms_code', '');
            if (empty($code)) {
                return ResultHelper::json(401, '验证码不能为空');
            }
            $sendCode = Yii::$app->cache->get($mobile . '_code');
            if ($code != $sendCode) {
                return ResultHelper::json(401, '验证码错误');
            }
        }

        try {
            if (empty($email)) {
                $email = $mobile . '@qq.com';
            }
            $res = $User->signup($username, $mobile, $email, $password, 1, $invitation_code);
        } catch (ErrorException | Exception | Throwable $e) {
            return ResultHelper::json(400, $e->getMessage(), (array) $e);
        }

        return ResultHelper::json(200, '注册成功', (array) $res);
    }

    /**
     * @throws Exception|Throwable
     */
    public function actionLogin(): array
    {
        Yii::beginProfile('actionLogin');
        $model = new LoginForm();
        try {
            $data = Yii::$app->getRequest()->getBodyParams();
            if (key_exists('username', $data) && StringHelper::validatePhoneNumber($data['username'])) {
                $data['type']   = 2;
                $data['mobile'] = $data['username'];
            }

            if ($model->load($data, '') && $userinfo = $model->login()) {
                Yii::endProfile('actionLogin');
                $uid = $userinfo['user']['id'];
                $isAuth = WebsiteGroup::checkUserCompany($uid);
                if (!$isAuth){
                    return ResultHelper::json(401, '当前用户未授权',[
                        'uid' => $uid,
                    ]);
                }
                return ResultHelper::json(200, '登录成功', (array) $userinfo);
            } else {
                $message = ErrorsHelper::getModelError($model);
                Yii::endProfile('actionLogin');
                return ResultHelper::json('401', $message);
            }
        } catch (InvalidConfigException $e) {
            Yii::endProfile('actionLogin');
            throw new Exception($e->getMessage(), 400);
        }
    }

    public function actionRepassword(): array
    {
        $model = new PasswdForm();
        if ($model->load(Yii::$app->request->post(), '')) {
            if (! $model->validate()) {
                $res = ErrorsHelper::getModelError($model);

                return ResultHelper::json(404, $res);
            }

            $data     = Yii::$app->request->post();
            $mobile   = $data['mobile'];
            $settings = Yii::$app->settings;

            $info = $settings->getAllBySection('Website');
            if ((int) $info['is_send_code'] === 1) {
                $code     = $data['code'];
                $sendcode = Yii::$app->cache->get($mobile . '_code');
                if ($code != $sendcode) {
                    return ResultHelper::json(401, '验证码错误');
                }
            }
            $userType = Yii::$app->params['userType'];

            $userModel = $userType === 'admin' ? new User() : new customerUser();

            $member                = $userModel::findByMobile($data['mobile']);
            $member->password_hash = Yii::$app->security->generatePasswordHash($model->newpassword);
            $member->generatePasswordResetToken();
            if ($member->save()) {
                Yii::$app->user->logout();

                if ($userType === 'admin') {

                    $AccessTokenService = new AdminAccessTokenService();
                } else {
                    $AccessTokenService = new AccessTokenService();
                }
                $userinfo = $AccessTokenService->getAccessToken($member, 1);

                // 清除验证码
                Yii::$app->cache->delete($mobile . '_code');

                return ResultHelper::json(200, '修改成功', $userinfo);
            }
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(404, $msg);
        } else {
            $res = ErrorsHelper::getModelError($model);

            return ResultHelper::json(404, $res);
        }
    }

    /**
     * @return array
     */
    public function actionUserinfo(): array
    {
        $mobile = Yii::$app->request->input('mobile') ?? '';
        Yii::$app->cache->flush();
        $data = Yii::$app->request->post();

        $user_id  = Yii::$app->user->identity->user_id ?? 0;
        $userType = Yii::$app->params['userType'];
        if ($userType === 'admin') {
            $userModel          = new adminUser();
            $AccessTokenService = new AdminAccessTokenService();
        } else {
            $userModel          = new customerUser();
            $AccessTokenService = new AccessTokenService();
        }
        if (! empty($mobile)) {
            $userobj = $userModel::findByMobile($data['mobile']);
        } else {
            $userobj = $userModel::findIdentity($user_id);
        }

        if (empty($userobj)) {
            return ResultHelper::json(401, '用户资料获取失败', [
                'userType' => $userType,
            ]);
        }

        $userinfo = $AccessTokenService->getAccessToken($userobj, 1);

        $settings = Yii::$app->settings;
        $settings->invalidateCache();
        $info  = $settings->getAllBySection('Website');
        $isImg = Yii::$app->request->input('isImg', 0);
        //过滤敏感信息
        $infoUrl = [];
        $Origin  = Yii::$app->request->getOrigin();
        if ($Origin && ! strpos($Origin, 'localhost') && ! strpos($Origin, '127.0.0.1')) {
            $url                     = parse_url($Origin);
            $infoUrl                 = WebsiteStationGroup::find()->where(['domain_url' => $url])->asArray()->one();
            $infoUrl['is_send_code'] = $info['is_send_code'] ?? 0;
        }

        $Website = (array) $infoUrl ?: (array) $info;
        unset(
            $Website['qcloud_sdk_app_id'],
            $Website['qcloud_secret_id'],
            $Website['qcloud_secret_key'],
            $Website['qcloud_sign_name'],
            $Website['qcloud_access_key_id'],
            $Website['qcloud_access_key_secret'],
            $Website['sign_name'],
            $Website['site_status'],
            $Website['template_code'],
            $Website['access_key_id'],
            $Website['access_key_secret'],
            $Website['aliyun_access_key_id'],
            $Website['aliyun_access_key_secret'],
            $Website['aliyun_sign_name'],
            $Website['aliyun_template_code']
        );

        if (isset($Website['blogo'])) {
            $Website['blogo'] = ImageHelper::tomedia($Website['blogo']);
        }

        $roles             = AuthAssignmentGroup::find()->where(['user_id' => $user_id])->select('item_name')->column();
        $userinfo['roles'] = $roles;
        $Api               = new Api();
        $Api->getConf($userinfo['user']['bloc_id']);

        $Api['app_id']           = (int) $Api['app_id'];
        $Api['bloc_id']          = (int) $Api['bloc_id'];
        $Api['id']               = (int) $Api['id'];
        $Api['is_showall']       = (int) $Api['is_showall'];
        $Api['member_id']        = (int) $Api['member_id'];
        $Api['swoole_member_id'] = (int) $Api['swoole_member_id'];

        return ResultHelper::json(200, '获取成功', [
            'userinfo' => $userinfo,
            'Website'  => $Website,
            'apiConf'  => $Api,
            'apiurl'   => Yii::$app->request->hostInfo,
        ]);
    }

    /**
     * 绑定手机号
     * @return array|object[]|string[]
     */
    public function actionBindmobile(): array
    {
        $use_id  = Yii::$app->user->identity->id;
        $company = Yii::$app->request->input('company');
        $code    = Yii::$app->request->input('code');
        $mobile  = Yii::$app->request->input('mobile');
        $userobj = User::find()->where(['mobile' => $mobile])->asArray()->one();
        if (! $company) {
            return ResultHelper::json(401, '公司名称不能为空');
        }
        loggingHelper::writeLog('userController', 'actionBindmobile', '绑定手机号', [
            'userobj' => $userobj,
            'mobile'  => $mobile,
        ]);
        if (! empty($userobj) && $userobj['id'] != $use_id) {
            //查手机号存在，且用户ID不同
            return ResultHelper::json(401, '手机号已存在不能绑定');
        }
        $sendcode = Yii::$app->cache->get($mobile . '_code');

        if ($code != $sendcode) {
            return ResultHelper::json(401, '验证码错误');
        }

        $fields['company'] = $company;
        $fields['mobile']  = $mobile;
        $newpassword       = Yii::$app->cache->get('newpassword');
        $res               = Yii::$app->service->adminAccessTokenService->editInfo($use_id, $fields, $newpassword);
        if ($res) {
            return ResultHelper::json(200, '绑定手机号成功');
        } else {
            return ResultHelper::json(401, '绑定失败', $res);
        }
    }

    /**
     * 修改用户信息
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionEdituserinfo(): array
    {
        $model = new EdituserinfoForm();
        if ($model->load(Yii::$app->request->post(), '')) {
            if (! $model->validate()) {
                $res = ErrorsHelper::getModelError($model);

                return ResultHelper::json(404, $res);
            }
            $userinfo = $model->edituserinfo();
            if ($userinfo) {
                return ResultHelper::json(200, '修改成功', (array) $userinfo);
            }
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(404, $msg);
        } else {
            $res = ErrorsHelper::getModelError($model);

            return ResultHelper::json(404, $res);
        }
    }

    public function actionEditInfo()
    {
        $paras = Yii::$app->request->input();
        $id    = $paras['id'] ?? 0;

        if (! $id) {
            return ResultHelper::json(400, '缺少 id 参数');
        }

        $info = CustomerUser::findOne($id);

        if (empty($info)) {
            return ResultHelper::json(400, '数据异常');
        }

        if (isset($paras['customer_id']) && $info->customer_id != $paras['customer_id']) {
            $info->customer_id = $paras['customer_id'];
        }

        if (isset($paras['auth_role_id']) && $info->auth_role_id != $paras['auth_role_id']) {
            $info->auth_role_id = $paras['auth_role_id'];
        }

        if (isset($paras['company']) && $info->company != $paras['company']) {
            $info->company = $paras['company'];
        }

        if (isset($paras['username']) && $info->username != $paras['username']) {
            $info->username = $paras['username'];
        }

        if (isset($paras['mobile']) && $info->mobile != $paras['mobile']) {
            $info->mobile = $paras['mobile'];
        }

        if (isset($paras['status']) && $info->status != $paras['status']) {
            $info->status = $paras['status'];
        }

        if (isset($paras['avatar']) && $info->avatar != $paras['avatar']) {
            $info->avatar = $paras['avatar'];
        }

        if (isset($paras['disabled']) && $info->disabled != $paras['disabled']) {
            $info->disabled = $paras['disabled'];
        }

        if (isset($paras['password'])) {
            $info->setPassword($paras['password']);
        }

        $r = $info->save(false);

        if ($r) {
            return ResultHelper::json(200, '修改成功', $info->toArray());
        }

        return ResultHelper::json(400, '保存失败');
    }

    public function actionForgetpass(): array
    {
        $mobile     = Yii::$app->request->input('mobile');
        $password   = Yii::$app->request->input('password');
        $repassword = Yii::$app->request->input('repassword');
        $code       = Yii::$app->request->input('sms_code');
        $sendcode   = Yii::$app->cache->get($mobile . '_code');

        $settings = Yii::$app->settings;
        $settings->invalidateCache();
        $info = $settings->getAllBySection('Website');
        if (empty($mobile)) {
            return ResultHelper::json(401, '手机号不能为空');
        }

        if (empty($password)) {
            return ResultHelper::json(401, '密码不能为空');
        }

        if (empty($repassword)) {
            return ResultHelper::json(401, '确认密码不能为空');
        }

        if (trim($repassword) != trim($password)) {
            return ResultHelper::json(401, '两次输入的密码不同');
        }

        if ((int) $info['is_send_code'] === 1) {
            if (empty($code)) {
                return ResultHelper::json(401, '验证码不能为空');
            }
            if ($code != $sendcode) {
                return ResultHelper::json(401, '验证码错误');
            }
        }
        $user = User::findByMobile($mobile);
        if (empty($user)) {
            return ResultHelper::json(401, '该账户不存在或未通过审核');
        }
        $adminAccessTokenService = new AccessTokenService();
        $res                     = $adminAccessTokenService->forgetpassword($user, $password);
        if ($res) {
            // 清除验证码
            Yii::$app->cache->delete($mobile . '_code');

            return ResultHelper::json(200, '修改成功');
        } else {
            return ResultHelper::json(401, '修改失败');
        }
    }

    public function actionSendcode(): array
    {
        $type = Yii::$app->request->input('type');
        if (! in_array($type, ['forgetpass', 'register', 'bindMobile', 'login'])) {
            return ResultHelper::json(401, '验证码请求不合法，请传入字段类型type');
        }
        $mobile          = Yii::$app->request->input('mobile');
        $where           = [];
        $where['mobile'] = $mobile;

        // 首先校验手机号是否重复
        $user = User::find()->where($where)->asArray()->one();

        if (empty($user) && $type === 'forgetpass') {
            return ResultHelper::json(401, '手机号不存在');
        }

        if (empty($mobile)) {
            return ResultHelper::json(401, '手机号不能为空');
        }

        // 首先校验手机号是否重复
        if ($user && $type === 'register') {
            return ResultHelper::json(401, '手机号已经存在');
        }

        $code = random_int(1000, 9999);
        Yii::$app->cache->set($mobile . '_code', $code);
        $settings = Yii::$app->settings;
        $config   = $settings->getAllBySection('Website');

        $res = Yii::$app->service->adminSmsService->send($mobile, $code, '注册', 0);

        return ResultHelper::json(200, '发送成功', $res);
    }

    public function actionRefresh(): array
    {

        $refresh_token = Yii::$app->request->input('refresh_token');

        $user = DdApiAccessToken::find()
            ->where(['refresh_token' => $refresh_token])
            ->one();

        if (! $user) {
            return ResultHelper::json(403, '令牌错误，找不到用户!');
        }
        $AccessTokenService = new AccessTokenService();
        $access_token       = $AccessTokenService->RefreshToken($user['user_id'], $user['group_id']);

        return ResultHelper::json(200, '发送成功', ['access_token' => $access_token]);
    }

    public function actionFeedback(): array
    {

        $name     = Yii::$app->request->input('name');
        $contact  = Yii::$app->request->input('contact');
        $feedback = Yii::$app->request->input('feedback');
        $contacts = new DdWebsiteContact();

        $data = [
            'name'     => $name,
            'contact'  => $contact,
            'feedback' => $feedback,
        ];

        if ($contacts->load($data, '') && $contacts->save()) {
            return ResultHelper::json(200, '反馈成功');
        } else {
            $errors = ErrorsHelper::getModelError($contacts);

            return ResultHelper::json(401, $errors);
        }
    }

    public function actionAddons(): array
    {
        $id         = Yii::$app->request->input('id');
        $AddonsUser = new AddonsUser([
            'user_id' => $id,
        ]);
        $opts = $AddonsUser->getItems();

        return ResultHelper::json(200, '获取成功', [
            'assigned'  => array_values($opts['assigned']['modules']),
            'available' => array_values($opts['available']['modules']),
        ]);
    }

    public function actionDelete($id): array
    {
        // UserService::deleteUser($id);
        $user = CustomerUser::findOne($id);

        if ($user) {
            $r = $user->delete();

            if ($r) {
                return ResultHelper::json(200, '删除成功');
            }
        }

        return ResultHelper::json(400, '删除失败');
    }

    public function actionActivate($id)
    {
        /* @var $user User */
        $user = $this->findModel($id);
        if ($user->status == UserStatus::INACTIVE) {
            $user->status = UserStatus::ACTIVE;
            if ($user->save()) {
                return ResultHelper::json(200, '审核成功');
            } else {
                $errors = $user->firstErrors;

                return ResultHelper::json(401, reset($errors));
            }
        } else {
            return ResultHelper::json(401, '用户状态不正确');
        }
    }

    public function actionUpstatus(): array
    {
        $user_id = Yii::$app->request->input('user_id');
        $type    = Yii::$app->request->input('type');

        if (empty($user_id)) {
            return ResultHelper::json(401, '用户ID不能为空');
        }

        if (empty($type)) {
            return ResultHelper::json(401, '操作类型不能为空');
        }

        if (UserService::upStatus($user_id, $type)) {
            return ResultHelper::json(200, '修改成功');
        } else {
            return ResultHelper::json(401, '修改失败');
        }
    }

    /**
     * @throws Throwable
     * @throws ErrorException
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionCreate(): array
    {
        $username       = Yii::$app->request->input('username','');
        $mobile         = Yii::$app->request->input('mobile');
        $password       = Yii::$app->request->input('password');
        $email          = Yii::$app->request->input('email');
        $status         = Yii::$app->request->input('status');
        $company        = Yii::$app->request->input('company', '');
        $customer_id    = Yii::$app->request->input('customer_id', 0);
        $is_create_bloc = Yii::$app->request->input('is_create_bloc', 0);
        $disabled       = Yii::$app->request->input('disabled', 0);

        if (empty($username)) {
            return ResultHelper::json(401, '姓名不能为空');
        }
        if (empty($mobile)) {
            return ResultHelper::json(401, '手机号不能为空');
        }
        /**
         * 手机号长度校验
         */
        if (strlen($mobile) != 11) {
            return ResultHelper::json(401, '手机号长度不正确');
        }

        if (empty($password)) {
            return ResultHelper::json(401, '密码不能为空');
        }

        if (strlen($password) < 6) {
            return ResultHelper::json(401, '密码至少6位');
        }

        if (!$disabled == 1) {
            $disabled = 0;
        }

        $model = new CustomerUser();

        $res = $model->signup($username, $mobile, $email, $password, (int) $status, $is_create_bloc === 1, '', 0, $company, $customer_id, $disabled);

        if ($res) {
            if (key_exists('code', $res) && $res['code'] !== 200) {
                return ResultHelper::json(400, $res['message']);
            }

            return ResultHelper::json(200, '添加成功', (array) $res);
        } else {
            $msg = ErrorsHelper::getModelError($model);
            loggingHelper::writeLog('admin', 'signup', '用户添加失败', [
                'msg' => $msg,
                'res' => $res,
            ]);
            return ResultHelper::json(400, $msg ?: $res['message']);
        }
    }

    public function actionSetinfo(): array
    {
        $user_id    = Yii::$app->request->input('user_id');
        $addons     = AddonsUser::find()->where(['user_id' => $user_id])->with(['addons'])->indexBy('module_name')->asArray()->all();
        $addonsList = [];

        foreach ($addons as $key => $value) {
            if (empty($value['addons'])) {
                unset($addons[$key]);
            } else {
                $addonsList[] = [
                    'id'         => $value['id'],
                    'value'      => $value['id'],
                    'is_default' => $value['is_default'],
                    'text'       => $value['addons']['title'],
                ];
            }
        }

        // 公司授权
        $UserBloc     = UserBloc::find()->where(['user_id' => $user_id])->with(['bloc'])->indexBy('bloc_id')->asArray()->all();
        $UserBlocList = [];
        foreach ($UserBloc as $key => $value) {
            if (empty($value['bloc'])) {
                unset($UserBloc[$key]);
            } else {
                $UserBlocList[] = [
                    'value'      => $value['id'],
                    'id'         => $value['id'],
                    'is_default' => $value['is_default'],
                    'text'       => $value['bloc']['business_name'],
                ];
            }
        }

        // 商户授权
        $UserStore     = UserStore::find()->where(['user_id' => $user_id])->with(['store'])->indexBy('store_id')->asArray()->all();
        $UserStoreList = [];
        foreach ($UserStore as $key => $value) {
            if (empty($value['store'])) {
                unset($UserStore[$key]);
            } else {
                $UserStoreList[] = [
                    'value'      => $value['id'],
                    'id'         => $value['id'],
                    'is_default' => $value['is_default'],
                    'text'       => $value['store']['name'],
                ];
            }
        }

        return ResultHelper::json(200, '获取成功', [
            'addons'    => $addonsList,
            'UserBloc'  => $UserBlocList,
            'UserStore' => $UserStoreList,
        ]);
    }

    public function actionCeshi()
    {
        $user_id = Yii::$app->user->id ?? 0;
        return ResultHelper::json(200, '获取成功', [
            'user_id' => $user_id,
        ]);
    }

    public function actionDefaultInfo(): array
    {
        $user_id        = Yii::$app->request->input('user_id');
        $addons_user_id = AddonsUser::find()->where(['user_id' => $user_id, 'is_default' => 1])->select('id')->scalar();
        $store_user_id  = UserStore::find()->where(['user_id' => $user_id, 'is_default' => 1])->select('id')->scalar();

        return ResultHelper::json(200, '获取成功', [
            'addons_user_id' => $addons_user_id,
            'store_user_id'  => $store_user_id,
        ]);
    }

    public function actionDefault(): array
    {
        $user_id        = Yii::$app->request->input('user_id');
        $store_user_id  = Yii::$app->request->input('store_user_id');
        $bloc_user_id   = Yii::$app->request->input('bloc_user_id');
        $addons_user_id = Yii::$app->request->input('addons_user_id');

        if (empty($user_id)) {
            return ResultHelper::json(400, '用户ID不能为空');
        }

        if (empty($addons_user_id)) {
            return ResultHelper::json(400, '请选择业务类型');
        } else {
            $addons = new AddonsUser();
            $addons->updateAll([
                'is_default' => 0,
            ], [
                'user_id' => $user_id,
            ]);

            $addons->updateAll([
                'is_default' => 1,
            ], [
                'user_id' => $user_id,
                'id'      => $addons_user_id,
            ]);
        }

        if (empty($store_user_id)) {
            return ResultHelper::json(400, '请选择商户');
        } else {
            // 公司默认
            $UserBloc = new UserBloc();

            $UserBloc->updateAll([
                'is_default' => 0,
            ], [
                'user_id' => $user_id,
            ]);

            $UserBloc->updateAll([
                'is_default' => 1,
            ], [
                'user_id' => $user_id,
                'id'      => $bloc_user_id,
            ]);
            // 商户默认

            $UserStore = new UserStore();

            $UserStore->updateAll([
                'is_default' => 0,
            ], [
                'user_id' => $user_id,
            ]);

            $UserStore->updateAll([
                'is_default' => 1,
            ], [
                'user_id' => $user_id,
                'id'      => $store_user_id,
            ]);

            // 更新用户表中的商户与公司
            $store_id     = $UserStore->find()->where(['id' => $store_user_id])->select('store_id')->scalar();
            $bloc_id      = $UserBloc->find()->where(['id' => $bloc_user_id])->select('bloc_id')->scalar();
            $customerUser = customerUser::findOne(['id' => $user_id]);

            $customerUser->status   = $customerUser['status'];
            $customerUser->bloc_id  = (int) $bloc_id;
            $customerUser->store_id = (int) $store_id;
            try {
                $customerUser->update();
            } catch (StaleObjectException $e) {

                return ResultHelper::json(400, $e->getMessage());
            } catch (Throwable $e) {
                return ResultHelper::json(400, $e->getMessage());
            }
        }

        return ResultHelper::json(200, '设置成功');
    }

    public function actionUpdate($id): array
    {
        $model         = $this->findModel($id);
        $data          = Yii::$app->request->post();
        $department_id = Yii::$app->request->input('department_id');
        $user          = User::find()->where(['id' => $id])->one();

        if ($department_id && $model->department_id != $department_id) {
            $store_id        = DiandiAuthDepartments::find()->where(['id' => $department_id])->select('store_id')->scalar();
            $model->store_id = $store_id;
            $exists          = UserStore::find()->where(['user_id' => $id, 'store_id' => $store_id])->exists();
            if (! $exists) {
                AuthService::authStore($id, [$store_id]);
            }
        }

        $newpassword = Yii::$app->request->input('newpassword', '');
        if ($newpassword) {
            $model->password_hash = Yii::$app->security->generatePasswordHash($newpassword);
            $model->generatePasswordResetToken();
            unset($data['newpassword']);
        }

        /**
         * 用户名 和 手机号不能重复
         */
        if ($data['username'] != $model->username || $data['mobile'] != $model->mobile) {
            $exists = User::find()->where(['or', ['username' => $model->username], ['mobile' => $model->mobile]])->exists();
            if ($exists) {
                return ResultHelper::json(400, '用户名或手机号已存在');
            }
        }

        if ($model->load($data, '') && $model->save()) {
            $group_ids = Yii::$app->request->input('group_ids', []);
            if (! empty($group_ids)) {

                UserService::authUserGroup($id, array_column($group_ids, 'id'));
            }
            return ResultHelper::json(200, '获取成功', [
                'model' => $model,
            ]);
        } else {
            $msg = ErrorsHelper::getModelError($model);

            return ResultHelper::json(400, $msg);
        }
    }

    public function actionConfig(): array
    {

        $user_id  = Yii::$app->user->identity->user_id;
        $UserBloc = UserBloc::find()->where(['user_id' => $user_id, 'is_default' => 1, 'status' => 1])->asArray()->one();
        $store_id = UserStore::find()->where(['user_id' => $user_id, 'is_default' => 1, 'status' => 1])->select('store_id')->scalar();
        $Api      = new Api();
        $apiConf  = $Api->getConf($UserBloc['bloc_id']);
        $data     = [
            'baseUrl'    => Yii::$app->request->getHostName(),
            'bloc_id'    => $UserBloc['bloc_id'],
            'store_id'   => $store_id,
            'siteUrl'    => Yii::$app->request->getHostName(),
            'app_id'     => $apiConf['app_id'],
            'app_secret' => $apiConf['app_secret'],
        ];

        return ResultHelper::json(200, '设置成功', $data);
    }

    public function actionLog(): array
    {
        $user_id  = Yii::$app->user->identity->user_id;
        $key_id   = Yii::$app->request->input('key_id');
        $pageSize = 20;
        $query    = ActionLog::find()->where(['user_id' => $user_id]);
        if (! empty($key_id)) {
            $query->andWhere(['key_id' => $key_id]);
        }
        $count = $query->count();
        // 使用总数来创建一个分页对象
        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize'   => $pageSize,
            // 'page'=>$page-1
            // 'pageParam'=>'page'
        ]);

        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy([
                "logtime" => SORT_DESC,
            ])
            ->asArray()
            ->all();

        $lists = [];
        foreach ($list as $key => $value) {
            $time           = date('Y-m-d', strtotime($value['logtime']));
            $lists[$time][] = $value;
        }

        return ResultHelper::json(200, '获取成功', $lists);
    }

    public function actionUsertype()
    {
        $result = [
            'user_type' => '',
        ];
        $mobile = Yii::$app->request->input('mobile');

        if (empty($mobile)) {
            return ResultHelper::json(400, 'mobile 参数不能为空');
        }

        // 员工账户验证
        $user = customerUser::findOne([
            'mobile' => $mobile,
        ]);

        if ($user) {
            $result['user_type'] = 'customer';
            return ResultHelper::json(200, '获取成功', $result);
        }

        /**
         * 客商不存在
         */
        $admin = adminUser::findOne([
            'mobile' => $mobile,
        ]);

        if ($admin) {
            $result['user_type'] = 'employee';
            return ResultHelper::json(200, '获取成功', $result);
        }

        return ResultHelper::json(400, $mobile . ' 用户不存在');
    }

    public function actionDisabled()
    {
        $customer_user_id = Yii::$app->request->input('customer_user_id');
        $disabled         = Yii::$app->request->input('disabled', 0);

        if (empty($customer_user_id)) {
            return ResultHelper::json(400, '用户标识 (customer_user_id) 不能为空');
        }

        if ($disabled != 0) {
            $disabled = 1;
        } else {
            $disabled = 0;
        }

        $user = CustomerUser::findOne($customer_user_id);

        if (empty($user)) {
            return ResultHelper::json(400, '客商账号 数据不存在');
        }

        $user->disabled = $disabled;

        $r = $user->save(false);

        if ($r) {
            return ResultHelper::json(200, '修改成功');
        }

        return ResultHelper::json(400, '修改失败');
    }
}
