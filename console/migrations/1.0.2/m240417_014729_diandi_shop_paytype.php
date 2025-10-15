<?php

use yii\db\Migration;

class m240417_014729_diandi_shop_paytype extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_shop_paytype}}', [
            'id' => "int(11) unsigned NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL",
            'store_id' => "int(11) NULL",
            'name' => "varchar(30) NOT NULL DEFAULT '' COMMENT '收款方式名称'",
            'account_num' => "varchar(100) NULL COMMENT '账号'",
            'account_thumb' => "varchar(250) NOT NULL DEFAULT '' COMMENT '收款图片展示'",
            'account_desc' => "varchar(50) NOT NULL DEFAULT '0' COMMENT '收款其他备注'",
            'create_time' => "int(11) unsigned NOT NULL DEFAULT '0'",
            'update_time' => "int(11) NULL",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_shop_paytype}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

