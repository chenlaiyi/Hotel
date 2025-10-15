<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-24 09:20:02
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-05-19 10:08:30
 */
namespace addons\diandi_place\services\bloc;
use addons\diandi_place\models\enums\PlaceMemberAuthStatusEnums;
use addons\diandi_place\models\member\PlaceMember;
use addons\diandi_place\services\MemberService;
use api\models\DdMember;
use common\helpers\ErrorsHelper;
use common\helpers\ImageHelper;
use common\helpers\ResultHelper;
use common\services\BaseService;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
class PlaceBlocService extends BaseService
{
}
