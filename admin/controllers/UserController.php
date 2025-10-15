<?php

/**
 * @Author: Wang Chunsheng 2192138785@qq.com
 * @Date:   2020-03-05 11:45:49
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-07-17 20:21:31
 */

namespace admin\controllers;

use addons\diandi_agent\models\DiandiAgentUser;
use admin\models\BlocAddons;
use admin\models\DdApiAccessToken;
use admin\models\forms\EdituserinfoForm;
use admin\models\forms\LoginForm;
use admin\models\User;
use admin\services\AuthService;
use admin\services\UserService;
use admin\traits\UserTrait;
use common\helpers\ErrorsHelper;
use common\helpers\ImageHelper;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use common\helpers\StringHelper;
use common\models\DdWebsiteContact;
use common\models\forms\PasswdForm;
use common\models\UserBloc;
use common\models\UserStore;
use common\models\WebsiteStationGroup;
use common\plugins\diandi_auth\models\DiandiAuthDepartments;
use common\services\common\WebsiteGroup;
use diandi\addons\models\ActionLog;
use diandi\addons\models\AddonsUser;
use diandi\addons\models\BlocStore;
use diandi\addons\models\form\Api;
use diandi\admin\components\UserStatus;
use diandi\admin\models\AuthAssignmentGroup;
use diandi\admin\models\searchs\User as ModelsUser;
use diandi\admin\models\User as AdminModelsUser;
use Throwable;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\data\Pagination;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

class UserController extends AController
{
    use UserTrait;

    public $modelClass = 'admin\models\User';

    protected array $authOptional = ['login', 'signup', 'repassword', 'sendcode', 'forgetpass', 'refresh', 'userinfo'];

    public int $searchLevel = 0;

    public function actionUserlist(): array
    {
        $searchModel = new ModelsUser(
            [
                'module_name' => 'system',
            ]
        );

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return ResultHelper::json(
            200,
            '获取成功',
            [
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
            ]
        );
    }

    public function actionSignup(): array
    {
        $User            = new User();
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
        } catch (ErrorException|Exception|Throwable $e) {
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
            $data   = Yii::$app->getRequest()->getBodyParams();
            $mobile = Yii::$app->request->input('mobile');
            if (empty($mobile)) {
                return ResultHelper::json(401, '手机号不能为空');
            }
            if (!StringHelper::validatePhoneNumber($mobile)) {
                return ResultHelper::json(401, '手机号格式错误');
            }

            if ($model->load($data, '') && $userinfo = $model->login()) {
                Yii::endProfile('actionLogin');
                $uid    = $userinfo['user']['id'];
                $isAuth = WebsiteGroup::checkUserCompany($uid);
                if (!$isAuth) {
                    return ResultHelper::json(401, '当前用户未授权');
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
            if (!$model->validate()) {
                $res = ErrorsHelper::getModelError($model);

                return ResultHelper::json(404, $res);
            }

            $data     = Yii::$app->request->post();
            $mobile   = $data['mobile'];
            $settings = Yii::$app->settings;

            $info         = $settings->getAllBySection('Website');
            $is_send_code = $info['is_send_code'] ?? 0;
            if ((int) $is_send_code === 1) {
                $code     = $data['code'];
                $sendcode = Yii::$app->cache->get($mobile . '_code');
                if ($code != $sendcode) {
                    return ResultHelper::json(401, '验证码错误');
                }
            }

            $member                = User::findByMobile($data['mobile']);
            $member->password_hash = Yii::$app->security->generatePasswordHash($model->newpassword);
            $member->generatePasswordResetToken();
            if ($member->save()) {
                Yii::$app->user->logout();
                $service            = Yii::$app->service;
                $service->namespace = 'admin';
                $userinfo           = $service->AccessTokenService->getAccessToken($member, 1);
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

        $data = Yii::$app->request->post();

        $user_id = Yii::$app->user->identity->user_id ?? 0;
        if (empty($user_id)) {
            return ResultHelper::json(200, '用户没有登录', ['isLogin' => false,]);
        }
        if (!empty($mobile)) {
            $userobj = User::findByMobile($data['mobile']);
        } else {
            $userobj = User::findIdentity($user_id);
        }

        if (empty($userobj)) {
            return ResultHelper::json(401, '用户资料获取失败');
        }

        $service            = Yii::$app->service;
        $service->namespace = 'admin';
        $userinfo           = $service->AccessTokenService->getAccessToken($userobj, 1);

        $settings = Yii::$app->settings;
        $settings->invalidateCache();
        $info  = $settings->getAllBySection('Website');
        $isImg = Yii::$app->request->input('isImg', 0);
        //过滤敏感信息
        $infoUrl = [];
        $Origin  = Yii::$app->request->getOrigin();
        if ($Origin && !strpos($Origin, 'localhost') && !strpos($Origin, '127.0.0.1')) {
            $url                     = parse_url($Origin);
            $infoUrl                 = WebsiteStationGroup::find()->where(['domain_url' => $url])->asArray()->one();
            $infoUrl['is_send_code'] = $info['is_send_code'] ?? 0;
        }

        $Website = WebsiteGroup::getWebsiteInfo();

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
     *
     * @return array|object[]|string[]
     */
    public function actionBindmobile(): array
    {
        $use_id  = Yii::$app->user->identity->id;
        $company = Yii::$app->request->input('company');
        $code    = Yii::$app->request->input('code');
        $mobile  = Yii::$app->request->input('mobile');
        $userobj = User::find()->where(['mobile' => $mobile])->asArray()->one();
        if (!$company) {
            return ResultHelper::json(401, '公司名称不能为空');
        }
        loggingHelper::writeLog(
            'userController',
            'actionBindmobile',
            '绑定手机号',
            [
                'userobj' => $userobj,
                'mobile'  => $mobile,
            ]
        );
        if (!empty($userobj) && $userobj['id'] != $use_id) {
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
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionEdituserinfo(): array
    {
        $model = new EdituserinfoForm();
        if ($model->load(Yii::$app->request->post(), '')) {
            if (!$model->validate()) {
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

        $is_send_code = $info['is_send_code'] ?? 0;
        if ((int) $is_send_code === 1) {
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

        $res = Yii::$app->service->adminAccessTokenService->forgetpassword($user, $password);
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
        if (!in_array($type, ['forgetpass', 'register', 'bindMobile', 'login'])) {
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

        if (!$user) {
            return ResultHelper::json(403, '令牌错误，找不到用户!');
        }
        $service            = Yii::$app->service;
        $service->namespace = 'admin';

        $access_token = $service->AccessTokenService->RefreshToken($user['user_id'], $user['group_id']);

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
        $AddonsUser = new AddonsUser(
            [
                'user_id' => $id,
            ]
        );
        $opts       = $AddonsUser->getItems();

        return ResultHelper::json(
            200,
            '获取成功',
            [
                'assigned'  => array_values($opts['assigned']['modules']),
                'available' => array_values($opts['available']['modules']),
            ]
        );
    }

    public function actionDelete($id): array
    {
        $res = UserService::deleteUser($id);
        if ($res) {
            return ResultHelper::json(200, '删除成功');
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
        $username       = Yii::$app->request->input('username');
        $mobile         = Yii::$app->request->input('mobile');
        $password       = Yii::$app->request->input('password');
        $email          = Yii::$app->request->input('email');
        $status         = Yii::$app->request->input('status');
        $bloc_id        = Yii::$app->request->input('bloc_id');
        $is_create_bloc = Yii::$app->request->input('is_create_bloc', 0);

        if (empty($username)) {
            return ResultHelper::json(401, '用户名不能为空');
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
        if (empty($email)) {
            return ResultHelper::json(401, '邮箱不能为空');
        }
        if (empty($password)) {
            return ResultHelper::json(401, '密码不能为空');
        }

        if (strlen($password) < 6) {
            return ResultHelper::json(401, '密码至少6位');
        }

        $is_business_admin = Yii::$app->request->input('is_business_admin', 0);

        if ($is_business_admin) {
            /**
             * 校验公司是否已授权应用权限
             */
            $haveAuth = BlocAddons::find()->where(['bloc_id' => $bloc_id])->exists();
            if (!$haveAuth && $bloc_id) {
                return ResultHelper::json(400, '当前公司未授权应用权限，请先授权应用权限');
            }
        }

        $model = new User();

        $res = $model->signup($username, $mobile, $email, $password, (int) $status, $is_create_bloc === 1);

        if ($res) {
            if (key_exists('code', $res) && $res['code'] !== 200) {
                return ResultHelper::json(400, $res['message']);
            }
            $group_ids = Yii::$app->request->input('group_ids', []);
            if (!empty($group_ids)) {
                UserService::authUserGroup($res['user']['id'], $group_ids);
            }
            if ($bloc_id) {
                AuthService::authUserBloc($res['user']['id'], $bloc_id);
            }
            $department_id = Yii::$app->request->input('department_id');
            $user          = User::find()->where(['id' => $res['user']['id']])->one();

            if ($department_id) {
                $store_id       = DiandiAuthDepartments::find()->where(['id' => $department_id])->select('store_id')->scalar();
                $user->store_id = $store_id;
                AuthService::authStore($res['user']['id'], [$store_id]);
                AuthService::authUserUnSys($res['user']['id']);
            }

            if ($is_business_admin) {
                $user->is_business_admin = $is_business_admin;
            }

            $user->save(false);

            return ResultHelper::json(200, '添加成功', (array) $res);
        } else {
            $msg = ErrorsHelper::getModelError($model);
            loggingHelper::writeLog(
                'admin',
                'signup',
                '用户添加失败',
                [
                    'msg' => $msg,
                    'res' => $res,
                ]
            );
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

        return ResultHelper::json(
            200,
            '获取成功',
            [
                'addons'    => $addonsList,
                'UserBloc'  => $UserBlocList,
                'UserStore' => $UserStoreList,
            ]
        );
    }

    public function actionDefaultInfo(): array
    {
        $user_id        = Yii::$app->request->input('user_id');
        $addons_user_id = AddonsUser::find()->where(['user_id' => $user_id, 'is_default' => 1])->select('id')->scalar();
        $store_user_id  = UserStore::find()->where(['user_id' => $user_id, 'is_default' => 1])->select('id')->scalar();

        return ResultHelper::json(
            200,
            '获取成功',
            [
                'addons_user_id' => $addons_user_id,
                'store_user_id'  => $store_user_id,
            ]
        );
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
            $addons->updateAll(
                [
                    'is_default' => 0,
                ],
                [
                    'user_id' => $user_id,
                ]
            );

            $addons->updateAll(
                [
                    'is_default' => 1,
                ],
                [
                    'user_id' => $user_id,
                    'id'      => $addons_user_id,
                ]
            );
        }

        if (empty($store_user_id)) {
            return ResultHelper::json(400, '请选择商户');
        } else {
            // 公司默认
            $UserBloc = new UserBloc();

            $UserBloc->updateAll(
                [
                    'is_default' => 0,
                ],
                [
                    'user_id' => $user_id,
                ]
            );

            $UserBloc->updateAll(
                [
                    'is_default' => 1,
                ],
                [
                    'user_id' => $user_id,
                    'id'      => $bloc_user_id,
                ]
            );
            // 商户默认

            $UserStore = new UserStore();

            $UserStore->updateAll(
                [
                    'is_default' => 0,
                ],
                [
                    'user_id' => $user_id,
                ]
            );

            $UserStore->updateAll(
                [
                    'is_default' => 1,
                ],
                [
                    'user_id' => $user_id,
                    'id'      => $store_user_id,
                ]
            );

            // 更新用户表中的商户与公司
            $store_id        = $UserStore->find()->where(['id' => $store_user_id])->select('store_id')->scalar();
            $bloc_id         = $UserBloc->find()->where(['id' => $bloc_user_id])->select('bloc_id')->scalar();
            $AdminModelsUser = AdminModelsUser::findOne(['id' => $user_id]);

            $AdminModelsUser->status   = $AdminModelsUser['status'];
            $AdminModelsUser->bloc_id  = (int) $bloc_id;
            $AdminModelsUser->store_id = (int) $store_id;
            try {
                $AdminModelsUser->update();
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
        // $user          = User::find()->where(['id' => $id])->one();

        if ($department_id && $model->department_id != $department_id) {
            $store_id        = DiandiAuthDepartments::find()->where(['id' => $department_id])->select('store_id')->scalar();
            $model->store_id = $store_id;
            $exists          = UserStore::find()->where(['user_id' => $id, 'store_id' => $store_id])->exists();
            if (!$exists) {
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
            $exists = User::find()->where(['or', ['username' => $data['username']], ['mobile' => $data['mobile']]])->andWhere(['!=', 'id', $id])->exists();
            if ($exists) {
                return ResultHelper::json(400, '用户名或手机号已存在');
            }
        }

        if ($model->load($data, '') && $model->save()) {
            #取消编辑 给权限
            // $group_ids = Yii::$app->request->input('group_ids', []);
            // if (!empty($group_ids)) {

            //     UserService::authUserGroup($id, array_column($group_ids, 'id'));
            // }
            return ResultHelper::json(
                200,
                '获取成功',
                [
                    'model' => $model,
                ]
            );
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
        if (!empty($key_id)) {
            $query->andWhere(['key_id' => $key_id]);
        }
        $count = $query->count();
        // 使用总数来创建一个分页对象
        $pagination = new Pagination(
            [
                'totalCount' => $count,
                'pageSize'   => $pageSize,
                // 'page'=>$page-1
                // 'pageParam'=>'page'
            ]
        );

        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy(
                [
                    "logtime" => SORT_DESC,
                ]
            )
            ->asArray()
            ->all();

        $lists = [];
        foreach ($list as $key => $value) {
            $time           = date('Y-m-d', strtotime($value['logtime']));
            $lists[$time][] = $value;
        }

        return ResultHelper::json(200, '获取成功', $lists);
    }

    /**
     * @return [type]             [description]
     * @author Michael Liang
     * @email  Liang15946@163.com
     * @date   2025-07-10
     * @desc   获取用户选项，2级级联 分公司-用户
     */
    public function actionList()
    {
        $result  = [];
        $bloc_id = Yii::$app->request->input('bloc_id', 0);

        $query = BlocStore::find()
            ->select([
                'store_id',
                'name',
            ])
            ->where([
                'bloc_id' => $bloc_id,
                // 'status'  => 1,
            ])
            ->orderBy([
                'store_id' => SORT_DESC,
            ]);

        $query->andWhere(['not', ['name' => null]]);
        $query->andWhere(['not', ['name' => '']]);

        $stores = $query->all();

        foreach ($stores as $_item) {
            $_children = [];
            $_store_id = $_item->store_id;
            $_users    = User::find()->select([
                'id',
                'username',
            ])->where([
                'store_id' => $_store_id,
            ])->orderBy([
                'id' => SORT_DESC,
            ])->all();

            foreach ($_users as $_user) {
                $_children[] = [
                    'value' => $_user->id,
                    'label' => $_user->username,
                ];
            }

            $_tmp = [
                'value'    => $_item->store_id,
                'label'    => $_item->name,
                'children' => $_children,
            ];

            $result[] = $_tmp;
        }

        return ResultHelper::json(200, '获取成功', $result);
    }

    public function actionOption()
    {
        $result  = [];
        $bloc_id = Yii::$app->request->input('bloc_id', 0);
        $user_id = Yii::$app->user->id;

        if ($user_id && $bloc_id) {

        }
        $service = new UserService();
        $result  = $service->getUserOptions($user_id, $bloc_id);

        return ResultHelper::json(200, '获取成功', $result);
    }
}
