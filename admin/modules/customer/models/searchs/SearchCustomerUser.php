<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-21 00:19:09
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-21 00:55:11
 */

namespace admin\modules\customer\models\searchs;

use admin\modules\customer\models\CustomerUser;
use common\components\DataProvider\ArrayDataProvider;
use common\helpers\ErrorsHelper;
use Exception;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

/**
 * User represents the model behind the search form of `diandi/admin\models\User`.
 */
class SearchCustomerUser extends CustomerUser
{
    public $keyword;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            // [['id', 'status', 'created_at', 'updated_at','department_id','is_super_admin'], 'integer'],
            [['customer_id', 'auth_role_id', 'company', 'username', 'mobile', 'password_hash', 'password_reset_token', 'email', 'parent_bloc_id', 'store_id', 'bloc_id', 'status', 'created_at', 'updated_at', 'verification_token', 'avatar', 'is_login', 'last_login_ip', 'open_id', 'keyword', 'disabled'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied.
     *
     * @param array $params
     *
     * @return array|bool
     * @throws NotFoundHttpException
     */
    public function search($params): array|bool
    {
        // var_dump($params);exit;
        $this->load($params);

        if (! $this->validate()) {
            $msg = ErrorsHelper::getModelError($this);
            throw new Exception($msg);
        }

        $query = CustomerUser::find()->with([
            'role',
        ]);

        $query->where([
            'delete_time' => null,
        ]);

        $this->customer_id = $params['customer_id'] ?? 0;
        // grid filtering conditions
        $query->andFilterWhere([
            'id'     => $this->id,
            'status' => $this->status,
            'mobile' => $this->mobile,
            'email'  => $this->email,
            // 'customer_id' => $params['customer_id'] ?? 0,
        ]);

        $query->findBloc();

        /**
         * 王春生修改，customer_id 需要根据是否存在判断增加搜索条件，因为不应该存在为0的数据
         */
        if ($this->customer_id) {
            $query->andFilterWhere([
                'customer_id' => $this->customer_id,
            ]);
        }

        if ($this->keyword) {
            $query->andFilterWhere([
                'or',
                ['like', 'mobile', $this->keyword],
                ['like', 'company', $this->keyword],
                ['like', 'username', $this->keyword],
            ]);
        }

        $query->orderBy([
            'id' => SORT_DESC,
        ]);

        $count      = $query->count();
        $pageSize   = \Yii::$app->request->input('pageSize', 20);
        $page       = \Yii::$app->request->input('page', 1);
        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize'   => $pageSize,
            'page'       => $page - 1,
        ]);

        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        foreach ($list as &$item) {
            if ($item['last_time']) {
                $item['last_time_str'] = date('Y-m-d H:i:s', $item['last_time']);
            }

            if ($item['created_at']) {
                $item['created_at_str'] = date('Y-m-d H:i:s', $item['created_at']);
            }

            $item['role_text'] = $item['role']['name'] ?? '';
        }

        $dataProvider = new ArrayDataProvider([
            'key'        => 'id',
            'allModels'  => $list,
            'totalCount' => $count,
            'total'      => $count,
            'sql'        => $query->createCommand()->getRawSql(),
            'pagination' => [
                'pageSize' => $pageSize,
            ],
        ]);

        return $dataProvider->toArray();
    }
}
