<?php

namespace common\plugins\diandi_auth\models\searchs\cloud;

use common\components\DataProvider\ArrayDataProvider;
use common\plugins\diandi_auth\models\cloud\DiandiAuthAddons as DiandiAuthAddonsModel;
use yii\base\Model;
use yii\data\Pagination;

/**
 * DiandiAuthAddons represents the model behind the search form of `plugins\diandi_auth\models\cloud\DiandiAuthAddons`.
 */
class DiandiAuthAddons extends DiandiAuthAddonsModel
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'mid', 'is_nav', 'settings', 'is_install', 'cate_id'], 'integer'],
            [['identifie', 'type', 'title', 'version', 'ability', 'description', 'author', 'url', 'logo', 'versions', 'parent_mids', 'applets'], 'safe'],
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
        $query = DiandiAuthAddonsModel::find();


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return false;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id'         => $this->id,
            'mid'        => $this->mid,
            'is_nav'     => $this->is_nav,
            'settings'   => $this->settings,
            'is_install' => $this->is_install,
            'cate_id'    => $this->cate_id,
        ]);

        $query->andFilterWhere(['like', 'identifie', $this->identifie])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'version', $this->version])
            ->andFilterWhere(['like', 'ability', $this->ability])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'versions', $this->versions])
            ->andFilterWhere(['like', 'parent_mids', $this->parent_mids])
            ->andFilterWhere(['like', 'applets', $this->applets]);

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
