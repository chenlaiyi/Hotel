<?php
namespace addons\diandi_place\events;
use addons\diandi_place\models\place\PlaceLandlord;
use addons\diandi_place\models\place\PlaceType;
use addons\diandi_place\services\LandlordService;
use common\helpers\loggingHelper;
use Yii;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
class LoginEvent
{
    /**
     * @throws NotFoundHttpException
     */
    public function handleAfterLogin($event) {
        // 获取登录的用户 ID
        $user_id = $event->identity->getId();
        Yii::$app->cache->set('user_id-handleAfterLogin',false);
        $handleAfterLogin = Yii::$app->cache->get('user_id-handleAfterLogin');
        if(!$handleAfterLogin){
            $member = $event->identity->getUserInfo();
            $username = $member['username'];
            $mobile  = $member['mobile'];
            loggingHelper::writeLog('diandi_place','handleAfterLogin','用户登录',[$user_id,$username,$mobile]);
            $haveAuth = PlaceLandlord::find()->where(['user_id'=>$user_id])->findBloc()->exists();
            if (empty($haveAuth)){
                $PlaceLandlord = new PlaceLandlord();
                $PlaceLandlord->user_id = $user_id;
                $PlaceLandlord->realname = $username;
                $PlaceLandlord->mobile = $mobile;
                if ($PlaceLandlord->save()){
                    $configs = PlaceType::find()->findBloc()->select(['id as type_id'])->asArray()->all();
                    array_walk($configs, function (&$value) {
                        $value['type_status'] = 1;
                    });
                    LandlordService::setType($user_id, $configs);
                }
                Yii::$app->cache->set('user_id-handleAfterLogin',true);
            }
        }
    }
}