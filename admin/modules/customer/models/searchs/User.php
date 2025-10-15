<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2020-05-09 15:23:37
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2022-10-28 19:46:34
 */

namespace admin\modules\customer\models\searchs;

use admin\modules\customer\models\User as UserModel;
use common\components\DataProvider\ArrayDataProvider;
use common\helpers\ErrorsHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * User represents the model behind the search form of `diandi/admin\models\User`.
 */
class User extends UserModel
{
    public $keyword;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            // [['id', 'status', 'created_at', 'updated_at','department_id','is_super_admin'], 'integer'],
            [['customer_id', 'auth_role_id', 'company', 'username', 'mobile', 'password_hash', 'password_reset_token', 'email', 'parent_bloc_id', 'store_id', 'bloc_id', 'status', 'created_at', 'updated_at', 'verification_token', 'avatar', 'is_login', 'last_login_ip', 'open_id', 'keyword'], 'safe'],
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
     * @throws NotFoundHttpException|HttpException
     */
    public function search($params): array|bool
    {
        $this->load($params);

        if (! $this->validate()) {
            $msg = ErrorsHelper::getModelError($this);
            throw new HttpException(400,$msg);
        }

        $query = UserModel::find();

        // grid filtering conditions
        $query->andFilterWhere([
            'id'     => $this->id,
            'status' => $this->status,
            'mobile' => $this->mobile,
            'email'  => $this->email,
        ]);

        if ($this->keyword) {
            $query->andFilterWhere(['or',
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
