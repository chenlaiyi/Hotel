<?php

namespace common\modules\officialaccount\models\searchs;

use yii\base\Model;
use common\components\DataProvider\ArrayDataProvider;
use common\modules\officialaccount\models\DdWechatFans as DdWechatFansModel;
use yii\data\Pagination;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;

/**
 * DdWechatFans represents the model behind the search form of `common\modules\officialaccount\models\DdWechatFans`.
 */
class DdWechatFans extends DdWechatFansModel
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['fanid', 'bloc_id', 'store_id', 'member_id', 'update_time', 'create_time', 'gender', 'follow'], 'integer'],
            [['avatarUrl', 'openid', 'nickname', 'groupid', 'fans_info', 'unionid', 'country', 'city', 'province', 'secretKey', 'followtime', 'unfollowtime', 'tag'], 'safe'],
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
        $query = DdWechatFansModel::find();

        

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return false;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'fanid' => $this->fanid,
            'bloc_id' => $this->bloc_id,
            'store_id' => $this->store_id,
            'member_id' => $this->member_id,
            'update_time' => $this->update_time,
            'create_time' => $this->create_time,
            'gender' => $this->gender,
            'follow' => $this->follow,
            'followtime' => $this->followtime,
            'unfollowtime' => $this->unfollowtime,
        ]);

        $query->andFilterWhere(['like', 'avatarUrl', $this->avatarUrl])
            ->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'groupid', $this->groupid])
            ->andFilterWhere(['like', 'fans_info', $this->fans_info])
            ->andFilterWhere(['like', 'unionid', $this->unionid])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'secretKey', $this->secretKey])
            ->andFilterWhere(['like', 'tag', $this->tag]);
        $query->findBlocs();
        $count = $query->count();
        $pageSize   =\Yii::$app->request->input('pageSize');
        $page       =\Yii::$app->request->input('page');
        // 使用总数来创建一个分页对象
        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize' => $pageSize,
            'page' => $page - 1,
            // 'pageParam'=>'page'
        ]);

        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        
        foreach ($list as $key => &$value) {
            $value['tagidList'] = Json::decode($value['tag']);
        }
            

        $provider = new ArrayDataProvider([
            'key'=>'id',
            'allModels' => $list,
            'totalCount' => $count,
            'total'=> $count,
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
            ]
        ]);
        
        return $provider->toArray();
        
    }
}
