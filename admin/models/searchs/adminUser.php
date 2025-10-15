<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-12-22 23:06:50
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-19 16:47:18
 */

namespace admin\models\searchs;

use admin\models\User;
use admin\services\AuthService;
use admin\services\UserService;
use common\components\DataProvider\ArrayDataProvider;
use common\helpers\ErrorsHelper;
use common\models\enums\UserStatus;
use common\models\UserBloc;
use common\models\UserStore;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;

/**
 * adminUser represents the model behind the search form of `admin\models\User`.
 *
 * @property mixed|null $id
 * @property mixed $store_id
 * @property mixed|null $is_login
 * @property int|mixed $status
 * @property mixed $bloc_id
 * @property $username
 * @property $email
 * @property $mobile
 */
class adminUser extends User
{

    public int $group_id = 0;
    public int $user_id = 0;

    /**
     * 根据商户查 是否根据store_id读取
     *
     * @var mixed|int
     */
    public int $isStore = 1;

    /**
     * 根据公司查 是否根据bloc_id读取
     *
     * @var mixed|int
     */
    public mixed $isBloc = 1;

    //根据自己的公司权限查询
    public mixed $isCompany = 1;

    //  根据自己的商户权限查询
    public mixed $isMerchant = 0;
    //  根据自己的部分权限查询
    public int $isPart = 1;

    /**
     * 是否根据自身权限读取
     *
     * @var mixed|int
     */
    public int $isSelf = 1;

    //user_ids
    public int $isUserIds = 0;
    public int $isSys = 0;

    public int $search_store_id = 0;

    public mixed $user_ids = [];

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'store_id', 'bloc_id', 'status', 'department_id', 'is_super_admin', 'search_store_id', 'user_id'], 'integer'],
            [['username', 'email', 'mobile', 'group_id', 'user_ids'], 'safe'],
            [['isStore', 'isBloc', 'isPart', 'isSelf', 'isUserIds', 'isSys', 'user_ids'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with a search query applied.
     *
     * @param array $params
     *
     * @return array| bool
     */
    public function search(array $params): array|bool
    {
        $query = User::find()->distinct()->joinWith('userGroup as userGroup')
            ->where([
                    'delete_time' => null,
                ]
            );

        $this->load($params);
        $query->andFilterWhere(['is_sys' => 1]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return false;
        }

        // grid filtering conditions
        $query->andFilterWhere([
                'id' => $this->id,
                'status' => $this->status,
                'is_login' => $this->is_login,
            ]
        );

        if (!empty($this->group_id)) {
            $query->andFilterWhere([
                    'userGroup.group_id' => $this->group_id,
                ]
            );
        }

        if (!empty($this->store_id) && $this->isStore) {
            $query->andFilterWhere([
                    'store_id' => $this->store_id,
                ]
            );
        }

        if (!empty($this->bloc_id)) {
            $query->andFilterWhere([
                    'bloc_id' => $this->bloc_id,
                ]
            );
        }

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'mobile', $this->mobile]);
        if (!empty($this->department_id)) {
            $query->andFilterWhere([
                    'department_id' => (int)$this->department_id,
                ]
            );
        }

        if (isset($this->is_super_admin)) {
            $query->andFilterWhere([
                    'is_super_admin' => (int)$this->is_super_admin,
                ]
            );
        }
        $pageSize = Yii::$app->request->input('pageSize', 20);
        $page = Yii::$app->request->input('page', 1);
        $count = $query->count();

        // 使用总数来创建一个分页对象
        $pagination = new Pagination([
                'totalCount' => $count,
                'pageSize' => $pageSize,
                // 'page' => $page - 1,
                // 'pageParam'=>'page'
            ]
        );

        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy(['created_at' => SORT_DESC])
            ->asArray()
            ->all();

        foreach ($list as &$value) {
            $value['last_time'] = date('Y-m-d H:i:s', $value['last_time']);
            $value['created_at'] = date('Y-m-d H:i:s', $value['created_at']);
            $value['updated_at'] = date('Y-m-d H:i:s', $value['updated_at']);
        }

        $provider = new ArrayDataProvider([
                'key' => 'id',
                'allModels' => $list,
                'totalCount' => $count ?? 0,
                'total' => $count ?? 0,
                'sort' => [
                    'attributes' => [
                        //'member_id',
                    ],
                    'defaultOrder' => [
                        //'member_id' => SORT_DESC,
                    ],
                ],
                'pagination' => [
                    'pageSize' => $pageSize,
                ],
            ]
        );
        return $provider->toArray();

    }

    public function searchyw(array $params): array|bool
    {
        $query = User::find()->alias('u')->with('store')->distinct()->joinWith('userGroup as userGroup')
            ->where([
                    'u.delete_time' => null,
                    'u.status' => UserStatus::APPROVE,
                ]
            );

        $this->load($params);

        if (!$this->validate()) {
            $msg = ErrorsHelper::getModelError($this);
            throw new \Exception($msg);
        }

        $user_id = Yii::$app->user->id ?? 0;

        $isSuperAdmin = UserService::isSuperAdmin($user_id);
        $isbusinessRoles = UserService::isbusinessRoles($user_id);

        // grid filtering conditions
        $query->andFilterWhere([
                'u.status' => $this->status,
                'u.is_login' => $this->is_login,
            ]
        );

        if (!empty($this->group_id)) {
            $query->andFilterWhere([
                    'userGroup.group_id' => $this->group_id,
                ]
            );
        }

        //  总管理员查询
        if ($isSuperAdmin) {
            $user_ids = $this->user_ids;
            if (!empty($user_ids) && $this->isUserIds) {
                $query->andFilterWhere([
                        'u.id' => $user_ids,
                    ]
                );
            }
            /**
             * 根据公司查询用户，总管理员可以查询所有公司，只有有权限
             */
            if (!empty($this->bloc_id) && $this->isBloc) {
                $query->findBloc('u')->findBlocs('u');
            }

            /**
             * 分公司/商户
             */
            if ($this->isStore && !empty($this->store_id)) {
                $query->andFilterWhere([
                        'u.store_id' => $this->store_id,
                    ]
                );
            }

            /**
             * 部门
             */
            if (!empty($this->department_id) && $this->isPart) {
                $query->andFilterWhere([
                        'u.department_id' => (int)$this->department_id,
                    ]
                );
            }

        } else if ($isbusinessRoles) {
            //  超级管理员查询

            //            $isStore = 1;
            // $isBloc = 1;
            // $isCompany = 1;
            // $isMerchant = 1; //根据自己的商户权限查询
            // $isPart = 1; //根据自己的部分权限查询
            // $isSelf = 1; //根据自己的人员权限查询
            // $isUserIds //根据用户集合查询

            /**
             * 根据公司查询用户，
             */
            if (!empty($this->bloc_id) && $this->isBloc) {

                /**
                 * 查询当前公司已授权的用户集合
                 */
                $user_arrId = UserBloc::find()->where([
                        'bloc_id' => $this->bloc_id,
                    ]
                )->select('user_id')->groupBy('user_id')->column();
                /**
                 * 用户是当前公司或者有当前公司权限的用户
                 */
                $query->andFilterWhere([
                        'or',
                        ['u.bloc_id' => $this->bloc_id],
                        ['u.id' => $user_arrId],
                    ]
                );
            }

            /**
             * 分公司/商户
             */
            if ($this->isStore && !empty($this->store_id)) {
                $query->andFilterWhere([
                        'u.store_id' => $this->store_id,
                    ]
                );
            }

            /**
             * 部门
             */
            if (!empty($this->department_id) && $this->isPart) {
                $query->andFilterWhere([
                        'u.department_id' => (int)$this->department_id,
                    ]
                );
            }

            $user_ids = $this->user_ids;
            if (!empty($user_ids) && $this->isUserIds) {
                $query->andFilterWhere([
                        'u.id' => $user_ids,
                    ]
                );
            }

            $query->andFilterWhere(['like', 'u.username', $this->username])
                ->andFilterWhere(['like', 'u.email', $this->email])
                ->andFilterWhere(['like', 'u.mobile', $this->mobile]);

            $query->findUserLinkStore('store_id', 'u');

            if ($this->isSelf) {
                $query->findUserLinkUid('id', 'u');
            }
        } else {
            //  业务员查询

            //            $isStore = 1;
            // $isBloc = 1;
            // $isCompany = 1;
            // $isMerchant = 1; //根据自己的商户权限查询
            // $isPart = 1; //根据自己的部分权限查询
            // $isSelf = 1; //根据自己的人员权限查询
            // $isUserIds //根据用户集合查询
            /**
             * 根据公司查询用户，
             */
            if (!empty($this->bloc_id) && $this->isBloc) {

                /**
                 * 查询当前公司已授权的用户集合
                 */
                $user_arrId = UserBloc::find()->where([
                        'bloc_id' => $this->bloc_id,
                    ]
                )->select('user_id')->groupBy('user_id')->column();
                /**
                 * 用户是当前公司或者有当前公司权限的用户
                 */
                $query->andFilterWhere([
                        'or',
                        ['u.bloc_id' => $this->bloc_id],
                        ['u.id' => $user_arrId],
                    ]
                );
            }
            /**
             * 分公司/商户
             */
            if ($this->isStore && !empty($this->store_id)) {
                $query->andFilterWhere([
                        'u.store_id' => $this->store_id,
                    ]
                );
            } else if ($this->isStore && empty($this->store_id)) {
                $user_store_ids = AuthService::getUserStoreIds($user_id);

                $query->andFilterWhere([
                        'u.store_id' => $user_store_ids,
                    ]
                );
            }


            /**
             * 部门
             */
            if (!empty($this->department_id) && $this->isPart) {
                $query->andFilterWhere([
                        'u.department_id' => (int)$this->department_id,
                    ]
                );
            }

            $user_ids = $this->user_ids;
            if (!empty($user_ids) && $this->isUserIds) {
                $query->andFilterWhere([
                        'u.id' => $user_ids,
                    ]
                );
            }

            $query->andFilterWhere(['like', 'u.username', $this->username])
                ->andFilterWhere(['like', 'u.email', $this->email])
                ->andFilterWhere(['like', 'u.mobile', $this->mobile]);

            if ($this->isSelf) {
                $query->findUserLinkUid('id', 'u');
                $query->findUserLinkStore('store_id', 'u');
            }
        }

//        search_store_id
        if ($this->search_store_id) {
            $query->andFilterWhere([
                'u.store_id' => $this->search_store_id,
            ]);
        }
        $query->andFilterWhere(['or', ['u.is_sys' => $this->isSys], ['u.is_business_admin' => 1]]);
        $query->andFilterWhere(['like', 'u.username', $this->username])
            ->andFilterWhere(['like', 'u.email', $this->email])
            ->andFilterWhere(['like', 'u.mobile', $this->mobile]);

        $pageSize = Yii::$app->request->input('pageSize', 20);
        $page = Yii::$app->request->input('page', 1);
        $count = $query->count();

        // 使用总数来创建一个分页对象
        $pagination = new Pagination([
                'totalCount' => $count,
                'pageSize' => $pageSize,
                'page' => $page - 1,
                'pageParam' => 'page',
            ]
        );
        if (!empty($params['sort'])) {
            $params['sort'] = json_decode($params['sort'], true);
            if (isset($params['sort']['created_at'])) {
                $query->orderBy('u.created_at ' . $params['sort']['created_at']);
            }

            if (isset($params['sort']['last_time'])) {
                $query->orderBy('u.last_time ' . $params['sort']['last_time']);
            }
        } else {
            $query->orderBy('u.created_at  desc');
        }

        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();


        foreach ($list as &$value) {
            $value['last_time'] = date('Y-m-d H:i:s', $value['last_time']);
            $value['created_at'] = date('Y-m-d H:i:s', $value['created_at']);
            $value['updated_at'] = date('Y-m-d H:i:s', $value['updated_at']);
        }

        $provider = new ArrayDataProvider([
                'key' => 'id',
                'allModels' => $list,
                'totalCount' => $count ?? 0,
                'total' => $count ?? 0,
                'sql' => $query->createCommand()->getRawSql(),
                'sort' => [
                    'attributes' => [
                        //'member_id',
                    ],
                    'defaultOrder' => [
                        //'member_id' => SORT_DESC,
                    ],
                ],
                'pagination' => [
                    'pageSize' => $pageSize,
                ],
            ]
        );
        return $provider->toArray();
    }

    /**
     * 用户授权数据查询
     *
     * @param $params
     * @return array|bool
     * @throws \Exception
     */
    public function searchUser($params):array|bool
    {
        $query = User::find()->alias('u')->distinct()->joinWith('userGroup as userGroup')
            ->where([
                    'u.delete_time' => null,
                ]
            );

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return false;
        }

        // grid filtering conditions
        $query->andFilterWhere([
                'u.status' => $this->status,
                'u.is_login' => $this->is_login,
            ]
        );

        if (!empty($this->group_id)) {
            $query->andFilterWhere([
                    'userGroup.group_id' => $this->group_id,
                ]
            );
        }
        $isbusinessRoles = UserService::isbusinessRoles();
        $isSuperAdmin = UserService::isSuperAdmin();
        if ($isSuperAdmin === 0) {
            if (!empty($this->store_id)) {
                $query->andFilterWhere([
                        'u.store_id' => $this->store_id,
                    ]
                );
            }
        }
        if (!empty($this->department_id)) {
            $query->andFilterWhere([
                    'department_id' => (int)$this->department_id,
                ]
            );
        }
        /**
         * 如果是超级管理员，
         */

        if (!empty($this->bloc_id)) {
            $query->andFilterWhere([
                    'u.bloc_id' => $this->bloc_id,
                ]
            );
        }

        $query->andFilterWhere(['like', 'u.username', $this->username])
            ->andFilterWhere(['like', 'u.email', $this->email])
            ->andFilterWhere(['like', 'u.mobile', $this->mobile]);

        // $user_id = $this->user_id;
        $user_id = Yii::$app->user->id;
        if (empty($user_id)) {
            throw new \Exception('请选择要授权的用户');
        }

        $store_ids = UserStore::find()->where(['user_id' => $user_id])->select('store_id')->groupBy('store_id')->column();

        if ($this->isSelf) {
            $query->findUserLinkUid('id', 'u');
        }

        $query->andWhere(['in', 'u.store_id', $store_ids]);
        // echo $query->createCommand()->getRawSql();
        $pageSize = Yii::$app->request->input('pageSize', 20);
        $page = Yii::$app->request->input('page', 1);
        $count = $query->count();

        // 使用总数来创建一个分页对象
        $pagination = new Pagination([
                'totalCount' => $count,
                'pageSize' => $pageSize,
                // 'page' => $page - 1,
                // 'pageParam'=>'page'
            ]
        );

        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy(['created_at' => SORT_DESC])
            ->asArray()
            ->all();

        foreach ($list as &$value) {
            $value['last_time'] = date('Y-m-d H:i:s', $value['last_time']);
            $value['created_at'] = date('Y-m-d H:i:s', $value['created_at']);
            $value['updated_at'] = date('Y-m-d H:i:s', $value['updated_at']);
        }

        $provider = new ArrayDataProvider([
                'key' => 'id',
                'allModels' => $list,
                'totalCount' => $count ?? 0,
                'total' => $count ?? 0,
                'sort' => [
                    'attributes' => [
                        //'member_id',
                    ],
                    'defaultOrder' => [
                        //'member_id' => SORT_DESC,
                    ],
                ],
                'pagination' => [
                    'pageSize' => $pageSize,
                ],
            ]
        );
        return $provider->toArray();
    }
}
