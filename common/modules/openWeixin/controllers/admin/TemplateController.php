<?php

namespace common\modules\openWeixin\controllers\admin;

use admin\controllers\AController;
use common\helpers\ResultHelper;
use common\modules\openWeixin\services\OpenWechatWxappService;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use GuzzleHttp\Exception\GuzzleException;
use Yii;

class TemplateController extends AController
{
    public string $modelSearchName = 'DdWechatFans';

    protected array $authOptional = [];

    public $modelClass = 'api\modules\officialaccount\models\DdWechatFans';

    public function actionAddtotemplate(): array
    {
        $name = Yii::$app->request->input('draft_id');
        $code = Yii::$app->request->input('template_type');
        $code_type = Yii::$app->request->input('code_type');
        $legal_persona_wechat = Yii::$app->request->input('legal_persona_wechat');
        $legal_persona_name = Yii::$app->request->input('legal_persona_wechat');
        try {
            if (empty($name)) {
                return ResultHelper::json(200, '企业名（需与工商部门登记信息一致）；如果是“无主体名称个体工商户”则填“个体户+法人姓名”，例如“个体户张三”');
            }
            if (empty($code)) {
                return ResultHelper::json(200, '企业代码不能为空');
            }
            if (empty($code_type)) {
                return ResultHelper::json(200, '企业代码类型不能为空');
            }
            if (empty($legal_persona_wechat)) {
                return ResultHelper::json(200, '法人微信号不能为空');
            }
            if (empty($legal_persona_name)) {
                return ResultHelper::json(200, '法人姓名不能为空');
            }
            $Res = Yii::$app->WxappClient->registerMiniprogram($name, $code, $code_type, $legal_persona_wechat, $legal_persona_name);
            if ((int)$Res['errcode'] === 0) {
                $Res = OpenWechatWxappService::registerMiniprogram($name, $code, $code_type, $legal_persona_wechat, $legal_persona_name);
                return ResultHelper::json(200, '获取成功', $Res);
            } else {
                return ResultHelper::json(400, $Res['errmsg']);
            }
        } catch (InvalidConfigException $e) {
            return ResultHelper::json(400, $e->getMessage(), (array)$e);
        } catch (GuzzleException $e) {
            return ResultHelper::json(400, $e->getMessage(), (array)$e);
        }
    }
}