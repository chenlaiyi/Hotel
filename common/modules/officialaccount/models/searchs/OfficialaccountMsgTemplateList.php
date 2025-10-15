<?php

namespace common\modules\officialaccount\models\searchs;

use common\components\DataProvider\ArrayDataProvider;
use common\modules\officialaccount\models\OfficialaccountMsgTemplateList as OfficialaccountMsgTemplateListModel;
use yii\base\Model;
use yii\data\Pagination;

/**
 * OfficialaccountMsgTemplateList represents the model behind the search form of
 * `common\modules\officialaccount\models\OfficialaccountMsgTemplateList`.
 */
class OfficialaccountMsgTemplateList extends OfficialaccountMsgTemplateListModel
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'bloc_id', 'status'], 'integer'],
            [['template_id', 'url', 'data', 'miniprogram_appid', 'miniprogram_pagepath', 'create_time', 'update_time'], 'safe'],
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
        $query = OfficialaccountMsgTemplateListModel::find();


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return false;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id'      => $this->id,
            'bloc_id' => $this->bloc_id,
            'status'  => $this->status,
        ]);

        $query->andFilterWhere(['like', 'template_id', $this->template_id])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'data', $this->data])
            ->andFilterWhere(['like', 'miniprogram_appid', $this->miniprogram_appid])
            ->andFilterWhere(['like', 'miniprogram_pagepath', $this->miniprogram_pagepath])
            ->andFilterWhere(['like', 'create_time', $this->create_time])
            ->andFilterWhere(['like', 'update_time', $this->update_time]);
        $query->findBlocs();

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
