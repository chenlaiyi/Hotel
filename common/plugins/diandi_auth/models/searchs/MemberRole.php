<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-18 10:21:39
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-21 08:14:31
 */


namespace common\plugins\diandi_auth\models\searchs;


use common\components\DataProvider\ArrayDataProvider;
use common\plugins\diandi_auth\models\MemberRole as ZyjMemberRoleModel;
use yii\base\Model;
use yii\data\Pagination;


/**
 * ZyjMemberRole represents the model behind the search form of `\plugins\diandi_auth\models\ZyjMemberRole`.
 */
class MemberRole extends ZyjMemberRoleModel
{
    public $keyword;

    /**
     * {@inheritdoc}
     */

    public function rules(): array
    {
        return [
            [['id', 'bloc_id', 'store_id', 'accountStoreId', 'accountCloudId'], 'integer'],
            [['name', 'create_time', 'update_time', 'spec', 'keyword'], 'safe'],
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

        $query = ZyjMemberRoleModel::find();


        $this->load($params);


        if (!$this->validate()) {

            // uncomment the following line if you do not want to return any records when validation fails

            // $query->where('0=1');

            return false;
        }


        // grid filtering conditions

        $query->andFilterWhere([
            'id'             => $this->id,
            'bloc_id'        => $this->bloc_id,
            'accountStoreId' => $this->accountStoreId,
            'accountCloudId' => $this->accountCloudId,
            'spec'           => $this->spec,
            'create_time'    => $this->create_time,
            'update_time'    => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        if ($this->keyword) {
            $query->andFilterWhere(['like', 'name', $this->keyword]);
        }

        $count = $query->count();

        $pageSize = \Yii::$app->request->input('pageSize');

        $page = \Yii::$app->request->input('page');

        // 使用总数来创建一个分页对象

        $pagination = new Pagination([

            'totalCount' => $count,

            'pageSize' => $pageSize,

            'page' => $page - 1,

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

            'key' => 'id',

            'allModels' => $list,

            'totalCount' => $count,

            'total' => $count,
            'sql'   => $query->createCommand()->getRawSql(),
            'sort'  => [

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

    public function getOptions()
    {
        $query = ZyjMemberRoleModel::find();

        $query->where([
            'status' => 1
        ])->findBloc();

        return $query->select([
            'id',
            'name'
        ])->asArray()->all();
    }
}
