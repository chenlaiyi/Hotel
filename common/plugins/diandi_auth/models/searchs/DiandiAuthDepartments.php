<?php

namespace common\plugins\diandi_auth\models\searchs;

use common\components\DataProvider\ArrayDataProvider;
use common\plugins\diandi_auth\models\DiandiAuthDepartments as DiandiAuthDepartmentsModel;
use yii\base\Model;
use yii\data\Pagination;

/**
 * DiandiAuthDepartments represents the model behind the search form of
 * `common\plugins\diandi_auth\models\DiandiAuthDepartments`.
 */
class DiandiAuthDepartments extends DiandiAuthDepartmentsModel
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'bloc_id', 'store_id'], 'integer'],
            [['department_name', 'department_head', 'remarks', 'status', 'create_time', 'update_time'], 'safe'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return array|bool
     */
    public function search(array $params): array|bool
    {
        $query = DiandiAuthDepartmentsModel::find()->with(['store']);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return false;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id
        ]);

        if ($this->bloc_id) {
            $query->andFilterWhere(['bloc_id' => $this->bloc_id]);
        }


        if ($this->store_id) {
            $query->andFilterWhere(['store_id' => $this->store_id]);
        } else {
//            $store_id = Yii::$app->request->input('store_id');
            $query->findUserLinkStore();
//            $query->andFilterWhere(['store_id'=>$store_id]);
        }

        $query->andFilterWhere(['like', 'department_name', $this->department_name])
            ->andFilterWhere(['like', 'department_head', $this->department_head])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'status', $this->status]);
//        echo $query->createCommand()->getRawSql();
        $count    = $query->count();
        $pageSize = \Yii::$app->request->input('pageSize');
        $page     = \Yii::$app->request->input('page');
        // 使用总数来创建一个分页对象
        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize'   => $pageSize,
            'page'       => $page - 1,
            // 'pageParam'=>'page'
        ]);

        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        $provider = new ArrayDataProvider([
            'key'        => 'id',
            'allModels'  => $list,
            'totalCount' => $count,
            'total'      => $count,
            'sort'       => [
                'attributes'   => [
                    //'member_id',
                ],
                'defaultOrder' => [
                    //'member_id' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);
        return $provider->toArray();


    }
}
