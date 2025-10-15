<?php


namespace common\models;


use addons\diandi_distribution\models\order\DistributionOrder;
use common\models\User;
use common\components\ActiveRecord\YiiActiveRecord;
use common\helpers\HashidsHelper;
use common\helpers\loggingHelper;
use common\traits\ActiveQuery\StoreTrait;

use Yii;


/**
 * This is the model class for table "{{%user_member}}".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $member_id
 * @property int|null $is_default
 * @property string|null $create_time
 * @property string|null $update_time
 */
class UserMember extends YiiActiveRecord
{

    use StoreTrait;


    /**
     * {@inheritdoc}
     */

    public static function tableName(): string
    {

        return '{{%user_member}}';

    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $member = DdMember::find()->where(['member_id' => $this->member_id])->asArray()->one();
            $username = !empty($member['username']) ? $member['username'] : $member['mobile'];
            $transaction = User::getDb()->beginTransaction();
            try {
                $user_id = User::find()->where(['username' => $username])->select('id')->scalar();
                loggingHelper::writeLog('UserMember', 'afterSave', '添加会员', [
                    'member' => $member,
                    'member_id' => $this->member_id,
                    'user_id' => $user_id,
                    'username' => $username,
                    'sql' => User::find()->where(['username' => $username])->select('id')->createCommand()->getRawSql()
                ]);
                if (empty($user_id)) {
                    /**
                     * 注册管理员
                     */
                    $adminUser = new User();
                    $maxId = User::find()->max('id');
                    //            $adminUser->open_id = $user->id;
                    //            $adminUser->union_id = $user->getOriginal()['unionid'] ?? null;
                    $password = Yii::$app->security->generateRandomString(8);
                    $res = $adminUser->signup($username, $member['mobile'], ($maxId + 1) . '@cn.com', $password, 1);
                    loggingHelper::writeLog('UserMember', 'beforeSave', '注册管理员', $res);
                    $this->user_id = $res['user']['id'];
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
//                throw new \Exception($e->getMessage());
            }
            return true;
        } else{
            return false;
        }
    }


    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id'], 'integer'],
            [['user_id', 'member_id', 'is_default'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['member_id'], 'unique', 'targetAttribute' => ['member_id','user_id']]
        ];
    }


    /**
     * 行为.
     */
    public function behaviors(): array
    {
        /*自动添加创建和修改时间*/
        return [
            [
                'class' => \common\behaviors\SaveBehavior::className(),
                'updatedAttribute' => 'update_time',
                'createdAttribute' => 'create_time',
                'time_type' => 'datetime',
            ],
        ];
    }


    /**
     * {@inheritdoc}
     */

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'member_id' => 'Member ID',
            'is_default' => 'Is Default',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

}