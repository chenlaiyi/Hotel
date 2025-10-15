<?php


namespace common\plugins\diandi_auth\models\searchs;


use common\components\DataProvider\ArrayDataProvider;
use common\plugins\diandi_auth\models\enums\ServiceMainEnum;
use common\plugins\diandi_auth\models\MemberList as ZyjMemberListModel;
use yii\base\Model;
use yii\data\Pagination;


/**
 * ZyjMemberList represents the model behind the search form of `\plugins\diandi_auth\models\ZyjMemberList`.
 */
class MemberList extends ZyjMemberListModel
{

    public $role = 0;

    /**
     * {@inheritdoc}
     */

    public function rules(): array
    {

        return [

            [['id', 'bloc_id', 'store_id', 'member_cloud', 'status', 'isLogin'], 'integer'],
            [['name', 'username', 'role', 'password', 'mobile', 'create_time', 'update_time', 'spec'], 'safe'],

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

        $query = ZyjMemberListModel::find()->alias('m')->with(['role'])->joinWith(['store as s', 'cloud as c']);


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
            'status'      => $this->status,
            'isLogin'     => $this->isLogin,
            'spec'        => $this->spec,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'm.name', $this->name])
            ->andFilterWhere(['like', 'm.username', $this->username])
            ->andFilterWhere(['like', 'm.mobile', $this->mobile]);

        $store_id = $this->store_id;
        $cloud_id = $this->member_cloud;


        if ($cloud_id) {
            $query->andFilterWhere(['m.member_cloud' => $cloud_id]);
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


        foreach ($list as $key => &$value) {
            $spec = [];
            if (!$value['spec'] && is_array($value['spec'])) {
                foreach ($value['spec'] as $item) {
                    $spec[] = ServiceMainEnum::getLabel($item);
                }
            }

            /**
             * 将spec 转换为字符串
             */
            $value['spec'] = implode(',', $spec);
            if ($value['role']) {
                $role = [];
                foreach ($value['role'] as $item) {
                    if ($item['role']) {
                        $role[] = $item['role']['name'];
                    }
                }

                $value['role'] = implode(',', $role);
            }
        }


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

