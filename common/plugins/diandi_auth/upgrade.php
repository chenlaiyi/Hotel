<?php


namespace common\plugins\diandi_auth;


use common\interfaces\AddonWidget;
use yii\db\Migration;


/**
 * 升级数据库
 *
 * Class Upgrade
 * @package addons\Merchants
 */
class upgrade extends Migration implements AddonWidget
{

    /**
     * @var array
     */

    public array $versions = [

        '1.0.0', // 默认版本

        '1.0.1',

        '1.0.2',

    ];


    /**
     * @param $addon
     * @return mixed|void
     * @throws \yii\db\Exception
     */

    public function run($addon)
    {

        switch ($addon->version) {

            case '1.0.1':

                // 增加测试 - 冗余的字段

                // $this->addColumn('{{%addon_example_curd}}', 'field1', 'varchar(48)');

                break;

            case '1.0.2':

                // 删除测试 - 冗余的字段

                // $this->dropColumn('{{%addon_example_curd}}', 'field2');

                break;

        }

    }

}

