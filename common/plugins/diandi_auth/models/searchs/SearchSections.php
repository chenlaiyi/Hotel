<?php

namespace common\plugins\diandi_auth\models\searchs;

use common\components\DataProvider\ArrayDataProvider;
use common\plugins\diandi_auth\models\Sections;
use yii\base\Model;
use yii\data\Pagination;

/**
 * SearchSections represents the model behind the search form of `common\plugins\diandi_auth\models\Sections`.
 */
class SearchSections extends Sections
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'disabled', 'bloc_id', 'store_id'], 'integer'],
            [['section', 'description', 'back_up', 'create_time', 'update_time'], 'safe'],
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
        $query = Sections::find();

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return false;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id'          => $this->id,
            'disabled'    => $this->disabled,
            // 'bloc_id'     => $this->bloc_id,
            // 'store_id'    => $this->store_id,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'section', $this->section])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'back_up', $this->back_up]);

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

        $query = $query->offset($pagination->offset)
            ->limit($pagination->limit);

        $list = $query->all();

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
            ],
        ]);
        return $provider->toArray();


    }
}
