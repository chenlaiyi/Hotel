<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2020-05-15 22:50:42
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-19 15:22:56
 */

namespace common\behaviors;

use admin\models\addons\models\Bloc as ModelsBloc;
use common\helpers\loggingHelper;
use diandi\addons\models\ActionLog;
use common\models\User;
use EasySwoole\Utility\SnowFlake;
use Yii;
use yii\base\Behavior;
use yii\db\BaseActiveRecord;
use yii\db\Exception;

/**
 * @author Skilly
 */
class SaveBehavior extends Behavior
{
    public string $createdAttribute = 'create_time';

    public string $updatedAttribute = 'update_time';

    public string $adminAttribute  = 'admin_id';
    public string $memberAttribute = 'member_id';

    public string $storeAttribute = 'store_id';

    public string $blocAttribute = 'bloc_id';

    public string $blocPAttribute = 'bloc_pid'; //上级公司

    public string $globalBlocAttribute = 'global_bloc_id'; //上级公司
                                                           //department_id
    public string $departmentAttribute = 'department_id';

    /**
     * 雪花ID
     * @var string
     */
    public string $snow_id = 'id';

    public array $attributes = [];

    public array $noAttributes = [];

    public bool $is_bloc = false; //是否是集团数据模型

    public string $time_type = 'init'; //默认为init,可以设置为datetime

    public mixed $value = '';

    private array $_map = [];

    /**
     * @return void
     * 不能使用store_id、bloc_id作为主键
     */
    public function init(): void
    {

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => [$this->createdAttribute, $this->updatedAttribute, $this->blocAttribute, $this->storeAttribute, $this->blocPAttribute, $this->adminAttribute, $this->globalBlocAttribute,$this->snow_id], //准备数据 在插入之前更新created和updated两个字段
                BaseActiveRecord::EVENT_BEFORE_UPDATE => [$this->updatedAttribute, $this->blocAttribute, $this->storeAttribute, $this->blocPAttribute, $this->adminAttribute],                                                      // 在更新之前更新updated字段
            ];
        }

        $bloc_id  = Yii::$app->request->input('bloc_id');
        $store_id = Yii::$app->request->input('store_id');

        // 后台多级数据传递,控制台不做这个处理
        if (Yii::$app->id !== 'app-console' && Yii::$app->id !== 'install-console') {
            $blocs = Yii::$app->request->input('blocs',[]);
            if (! empty($blocs) && is_array($blocs) ) {
                $blocs    = Yii::$app->request->input('blocs');
                $bloc_id  = $blocs[0];
                $store_id = $blocs[1] ?? 0;
            }
        }
        $blocPid = ModelsBloc::find()->where(['bloc_id' => $bloc_id])->select('pid')->scalar();

        $admin_id  = Yii::$app->user->identity->id ?? 0;
        $member_id = Yii::$app->user->identity->member_id ?? 0;
        $time      = $this->time_type === 'init' ? time() : date('Y-m-d H:i:s', time());

        //适应默认使用用户数据的情况
        if (empty($bloc_id) && ! empty($admin_id)) {
            $bloc_id = User::find()->where(['id' => $admin_id])->select('bloc_id')->scalar();
        }

        if (empty($store_id) && ! empty($admin_id)) {
            $store_id = User::find()->where(['id' => $admin_id])->select('store_id')->scalar();
        }

        if ($this->value) {
            $time = $this->value;
        }

        $department_id = Yii::$app->user->identity->departmentId ?? 0;

        $data          = [
            $this->createdAttribute    => $time, //在这里你可以随意格式化
            $this->updatedAttribute    => $time,
            $this->snow_id             => SnowFlake::make(1,1),
            $this->blocAttribute       => (int) $bloc_id,
            $this->storeAttribute      => (int) $store_id,
            $this->blocPAttribute      => $blocPid ? (int) $blocPid : 0,
            $this->memberAttribute     => (int) $member_id,
            $this->departmentAttribute => (int) $department_id,
            $this->globalBlocAttribute => Yii::$app->params['global_bloc_id'] ?? 0,
        ];
        if (Yii::$app->id === 'app-admin') {
            $data[$this->adminAttribute] = (int) $admin_id;
        }

        $this->_map = $data;
        // DebugService::consoleWrite('行为-内存测试4');

    }

    //@see http://www.yiichina.com/doc/api/2.0/yii-base-behavior#events()-detail
    public function events(): array
    {
        return array_fill_keys(array_keys($this->attributes), 'evaluateAttributes');
    }

    public function addActionLog()
    {
        /**
         * 在这里写入操作详细日志 ActionLog
         * user_id
         * operation
         * logtime
         * class_name
         * action_name
         * action_data
         * logip
         * 需要排除当前父类不能是ActionLog
         */
        /**
         * 需要在添加后，删除后，修改后记录日志
         */
        if (Yii::$app->id === 'app-console' || Yii::$app->id === 'install-console') {
            return;
        }

        $ActionLog  = new ActionLog();
        $class_name = $this->owner->className();
        loggingHelper::writeLog('SaveBehavior', 'addActionLog', '记录日志', [
            'class_name'  => $class_name,
            'action_name' => Yii::$app->controller->action->id ?? '',
            'action_data' => Yii::$app->request->input(),
        ]);
        if ($class_name != ActionLog::class) {
            if (Yii::$app->id === 'app-admin') {
                $ActionLog->user_id = Yii::$app->user->identity->id ?? 0;
            } else {
                $ActionLog->user_id = Yii::$app->user->identity->member_id ?? 0;
            }
            $ActionLog->type = Yii::$app->id;
            /**
             * 根据模型类获取表备注
             */
            $action  = Yii::$app->controller->action->id;
            $actions = [
                'create' => '新增',
                'update' => '修改',
                'delete' => '删除',
            ];
            $table_comment        = $this->getTableComment();
            $operation            = ($actions[$action] ?? '操作') . $table_comment;
            $ActionLog->operation = $operation;
            /**
             * key_id 获取主键得值，区分事件
             */
            $key_id = 0;
            if (in_array($action, ['update', 'delete'])) {
                $key_id = $this->owner->getPrimaryKey();
            }
            $ActionLog->key_id      = $key_id;
            $ActionLog->logtime     = date('Y-m-d H:i:s', time());
            $ActionLog->class_name  = $class_name;
            $ActionLog->action_name = $action;
            $ActionLog->action_data = json_encode(Yii::$app->request->input());
            $ActionLog->logip       = Yii::$app->request->userIP;
            $ActionLog->save();
        }
    }

    /**
     * 获取当前模型关联表的注释
     * @return string|null 返回表注释，如果不存在则返回 null
     * @throws Exception
     */
    public function getTableComment(): ?string
    {
        // 获取模型关联的表的元信息
        $tableSchema = $this->owner->getTableSchema()->name;
        $comment     = Yii::$app->db->createCommand("SELECT TABLE_COMMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = '$tableSchema'")->queryScalar();
        if ($comment) {
            return $comment;
        }
        return ''; // 如果没有注释或表不存在，返回 null
    }

    public function evaluateAttributes($event): void
    {
        if (! empty($this->attributes[$event->name])) {
            $this->addActionLog();

            $attributes = $this->attributes[$event->name];
            foreach ($attributes as $attribute) {
                // 如果赋值了，就不需要改变
                if (isset($this->owner->attributes[$attribute]) && $attribute == 'store_id') {
                    continue;
                }

                if (isset($this->owner->attributes[$attribute]) && $attribute == 'bloc_id') {
                    continue;
                }

                if (isset($this->owner->attributes[$attribute]) && $attribute == 'admin_id') {
                    continue;
                }

                if (Yii::$app->id !== 'app-admin') {
                    unset($this->_map[$this->adminAttribute]);
                }

                loggingHelper::writeLog('SaveBehavior', 'init', '保存前数据处理', [
                    'class'           => $this->owner->className(),
                    'attribute'       => $attribute,
                    'owner'           => $this->owner->attributes,
                    'map'             => $this->_map,
                    'value'           => $this->getValue($attribute),
                    'owner_attribute' => $this->owner->attributes[$attribute] ?? null,
                ]);
                if (array_key_exists($attribute, $this->owner->attributes) && ! in_array($attribute, $this->noAttributes)) {
                    $this->owner->$attribute = $this->getValue($attribute);
                }
            }
        }
    }

    protected function getValue($attribute)
    {
        return $this->_map[$attribute] ?? 0;
    }

    /**
     * 声明一个析构方法.
     */
    public function __destruct()
    {
        $data = Yii::$app->request->input();

        unset($data, $this->_map, $this->attributes, $this->owner);
    }
}
