<?php

use yii\db\Migration;

class m240417_014729_diandi_subscription_package_usergroup extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_subscription_package_usergroup}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'package_id' => "int(11) NOT NULL COMMENT '套餐ID'",
            'group_item_id' => "int(11) NULL COMMENT '用户组权限ID'",
            'create_time' => "datetime NULL COMMENT '创建时间'",
            'update_time' => "datetime NULL COMMENT '更新时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_subscription_package_usergroup}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

