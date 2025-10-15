<?php

namespace common\modules\officialaccount\controllers\admin;

use common\modules\officialaccount\jobs\SendWechatMessageTask;
use common\modules\officialaccount\services\WeChatTemplateMessage;
use Yii;
use common\modules\officialaccount\models\OfficialaccountMsgTemplateList;
use common\modules\officialaccount\models\searchs\OfficialaccountMsgTemplateList as OfficialaccountMsgTemplateListSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use admin\controllers\AController;
use common\helpers\ResultHelper;
use common\helpers\ErrorsHelper;
use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;
use yii\db\StaleObjectException;
use Throwable;

/**
 * SendController implements the CRUD actions for OfficialaccountMsgTemplateList model.
 */
class SendController extends AController
{
    public string $modelSearchName = "OfficialaccountMsgTemplateListSearch";

    public $modelClass = '';


    /**
     * Lists all OfficialaccountMsgTemplateList models.
     * @return array
     */
    public function actionIndex(): array
    {
        $searchModel = new OfficialaccountMsgTemplateListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return ResultHelper::json(200, '获取成功', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelSearchName' => $this->modelSearchName,
            'field' => $searchModel->attributeLabels()
        ]);
    }

    /**
     * Displays a single OfficialaccountMsgTemplateList model.
     * @param string $id
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id): array
    {
        $view = $this->findModel($id);
        $detail = $view->toArray();
        $detail['data'] = json_decode($detail['data']);
        return ResultHelper::json(200, '获取成功', $detail);
    }

    /**
     * Creates a new OfficialaccountMsgTemplateList model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return array
     */
    public function actionCreate(): array
    {
        $model = new OfficialaccountMsgTemplateList();
        $data = Yii::$app->request->post();
        $data['data'] = json_encode($data['data'],JSON_UNESCAPED_UNICODE);
        if ($model->load($data, '') && $model->save()) {
            return ResultHelper::json(200, '创建成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(400, $msg);
        }
    }

    /**
     * Updates an existing OfficialaccountMsgTemplateList model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id): array
    {
        $model = $this->findModel($id);
        $data = Yii::$app->request->post();
        $data['data'] = json_encode($data['data'],JSON_UNESCAPED_UNICODE);

        if ($model->load($data, '') && $model->save()) {
            return ResultHelper::json(200, '编辑成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(400, $msg);
        }
    }

    /**
     * Deletes an existing WeihExhibitionServiceProvider model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id): array
    {
        $this->findModel($id)->delete();

        return ResultHelper::json(200, '删除成功');
    }

    /**
     * 推送模板消息
     * @return array
     */
    function actionMsg()
    {
        $openid = Yii::$app->request->input('openid');
        $id = Yii::$app->request->input('id');
        $REs = WeChatTemplateMessage::wechatSendAll($id,$openid);
        $errmsg = $REs['errmsg']??'';
        if ($errmsg){
            return ResultHelper::json(400, $errmsg);
        }
//        $REs = Yii::$app->queue->push(new SendWechatMessageTask([
//            'id' => $id, // 替换为实际的消息ID
//        ]));

        return ResultHelper::json(200, '推送成功',['res'=>$REs]);
    }


    /**
     * Finds the OfficialaccountMsgTemplateList model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return array|ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): array|ActiveRecord
    {
        if (($model = OfficialaccountMsgTemplateList::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}