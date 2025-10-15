<?php

namespace common\modules\officialaccount\models\searchs;

use common\components\DataProvider\ArrayDataProvider;
use common\modules\officialaccount\models\OfficialaccountWechatMenu as OfficialaccountWechatMenuModel;
use yii\base\Model;
use yii\data\Pagination;

/**
 * OfficialaccountWechatMenu represents the model behind the search form of
 * `common\modules\officialaccount\models\OfficialaccountWechatMenu`.
 */
class OfficialaccountWechatMenu extends OfficialaccountWechatMenuModel
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'bloc_id', 'store_id', 'parentId', 'menuLevel', 'msgType', 'menuType', 'menuSort', 'media_id'], 'integer'],
            [['update_time', 'create_time', 'menuName', 'menuUrl', 'appid', 'pagepath'], 'safe'],
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
        $query = OfficialaccountWechatMenuModel::find();


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
            'store_id'    => $this->store_id,
            'update_time' => $this->update_time,
            'create_time' => $this->create_time,
            'parentId'    => $this->parentId,
            'menuLevel'   => $this->menuLevel,
            'msgType'     => $this->msgType,
            'menuType'    => $this->menuType,
            'menuSort'    => $this->menuSort,
            'media_id'    => $this->media_id,
        ]);

        $query->andFilterWhere(['like', 'menuName', $this->menuName])
            ->andFilterWhere(['like', 'menuUrl', $this->menuUrl])
            ->andFilterWhere(['like', 'appid', $this->appid])
            ->andFilterWhere(['like', 'pagepath', $this->pagepath]);
        $query->findBlocs();

        $count    = $query->count();
        $pageSize = \Yii::$app->request->input('pageSize', 20);
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
