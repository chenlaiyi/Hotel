<?php
namespace addons\diandi_place\Traits;
use addons\diandi_distribution\models\order\DistributionOrder;
use addons\diandi_place\models\enums\LandlordStatusEnums;
use addons\diandi_place\models\landlord\PlaceLandlord;
use addons\diandi_place\models\place\PlaceBrand;
use admin\models\User;
use api\models\DdApiAccessToken;
use common\helpers\ErrorsHelper;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use common\models\UserMember;
use Yii;
use yii\db\Transaction;
use yii\web\HttpException;
trait LandlordTrait
{
    /**
     * @throws HttpException
     */
    function beforeAction($action): bool
    {
        return parent::beforeAction($action);
    }
}