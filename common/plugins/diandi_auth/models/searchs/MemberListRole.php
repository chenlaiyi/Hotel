<?php


namespace common\plugins\diandi_auth\models\searchs;


use common\components\DataProvider\ArrayDataProvider;
use common\plugins\diandi_auth\models\MemberListRole as ZyjMemberListRoleModel;
use yii\base\Model;
use yii\data\Pagination;


/**
 * ZyjMemberListRole represents the model behind the search form of `\plugins\diandi_auth\models\ZyjMemberListRole`.
 */
class MemberListRole extends ZyjMemberListRoleModel

{

    /**
     * {@inheritdoc}
     */

    public function rules(): array

    {

        return [

            [['id', 'bloc_id', 'store_id', 'accountId', 'roleId'], 'integer'],
            [['create_time', 'update_time', 'spec'], 'safe'],

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

        $query = ZyjMemberListRoleModel::find();


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
            'accountId'   => $this->accountId,
            'roleId'      => $this->roleId,
            'spec'        => $this->spec,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
        ]);


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

