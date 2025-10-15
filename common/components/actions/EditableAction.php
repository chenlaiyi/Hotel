<?php

namespace common\components\actions;

use common\helpers\ResultHelper;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\web\BadRequestHttpException;

/**
 * Class EditableAction
 *
 * @package yii2mod\editable
 *
 * @property-read array|mixed $modelAttribute
 */
class EditableAction extends Action
{
    /**
     * @var string the class name to handle
     */
    public string $modelClass;

    /**
     * @var string the scenario to be used (optional)
     */
    public string $scenario = Model::SCENARIO_DEFAULT;

    /**
     * @var \Closure a function to be called previous saving model. The anonymous function is preferable to have the
     * model passed by reference. This is useful when we need to set model with extra data previous update
     */
    public  $preProcess;

    /**
     * @var bool whether to create a model if a primary key parameter was not found
     */
    public bool $forceCreate = false;

    /**
     * @var string default pk column name
     */
    public string $pkColumn = 'id';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->pkColumn = Yii::$app->request->post('pkColumn','id');

        if ($this->modelClass === null) {
            throw new InvalidConfigException('The "modelClass" property must be set.');
        }
    }

    /**
     * Runs the action
     *
     * @return array
     *
     * @throws BadRequestHttpException
     */
    public function run()
    {
        $model = $this->findModelOrCreate();
        $attribute = $this->getModelAttribute();

        if ($this->preProcess && is_callable($this->preProcess, true)) {
            call_user_func($this->preProcess, $model);
        }

        $model->setScenario($this->scenario);
        $model->$attribute = Yii::$app->request->post('value');

        if ($model->validate([$attribute]) && $model->save(false)) {
            return ResultHelper::json(200, 'Success');
        } else {
            throw new BadRequestHttpException($model->getFirstError($attribute));
        }
    }

    /**
     * @return array|mixed
     *
     * @throws BadRequestHttpException
     */
    private function getModelAttribute()
    {
        $attribute = Yii::$app->request->post('name');

        if (strpos($attribute, '.')) {
            $attributeParts = explode('.', $attribute);
            $attribute = array_pop($attributeParts);
        }

        if ($attribute === null) {
            throw new BadRequestHttpException('Attribute cannot be empty.');
        }

        return $attribute;
    }

    /**
     * @return yii\db\ActiveRecord
     *
     * @throws BadRequestHttpException
     */
    private function findModelOrCreate()
    {
//        $pk = unserialize(base64_decode(Yii::$app->request->post('pk')));
        $pk = Yii::$app->request->post('pk');
        $class = $this->modelClass;
        $model = $class::findOne([$this->pkColumn => $pk]);

        if (!$model) {
            if ($this->forceCreate) {
                $model = new $class();
            } else {
                throw new BadRequestHttpException('Entity not found by primary key ' . $pk);
            }
        }

        return $model;
    }
}
