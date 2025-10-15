<?php

namespace common\plugins\diandi_auth\models\searchs;

use common\components\DataProvider\ArrayDataProvider;
use common\plugins\diandi_auth\models\BlocConfAppNav as BlocConfAppNavModel;
use yii\base\Model;
use yii\data\Pagination;

/**
 * BlocConfAppNav represents the model behind the search form of `common\plugins\diandi_auth\models\BlocConfAppNav`.
 */
class BlocConfAppNav extends BlocConfAppNavModel
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'bloc_id', 'sort_order', 'status'], 'integer'],
            [['text', 'tab_name', 'page_path', 'icon_path', 'selected_icon_path', 'create_time', 'update_time'], 'safe'],
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
        $query = BlocConfAppNavModel::find()->with([
            'bloc' => function ($query) {
                $query->select(['bloc_id', 'business_name']);
            }
        ]);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return false;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id'          => $this->id,
            'bloc_id'     => $this->bloc_id,
            'sort_order'  => $this->sort_order,
            'status'      => $this->status,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'tab_name', $this->tab_name])
            ->andFilterWhere(['like', 'page_path', $this->page_path])
            ->andFilterWhere(['like', 'icon_path', $this->icon_path])
            ->andFilterWhere(['like', 'selected_icon_path', $this->selected_icon_path]);
        $query->findBloc();
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
        //foreach ($list as $key => &$value) {
        //    $value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
        //    $value['update_time'] = date('Y-m-d H:i:s',$value['update_time']);
        //} 


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
