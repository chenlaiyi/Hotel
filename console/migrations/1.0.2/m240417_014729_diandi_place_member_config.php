<?php

use yii\db\Migration;

class m240417_014729_diandi_place_member_config extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_place_member_config}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL DEFAULT '0' COMMENT '公司ID'",
            'store_id' => "int(11) NULL DEFAULT '0' COMMENT '商户ID'",
            'member_id' => "int(11) NULL COMMENT '会员ID'",
            'is_open' => "int(11) NULL COMMENT '房源是否公开'",
            'lead_time' => "decimal(11,2) NULL DEFAULT '0.00' COMMENT '提前授权时间'",
            'delay_time' => "decimal(11,2) NULL DEFAULT '0.00' COMMENT '延迟授权时间'",
            'maintain_time' => "decimal(11,2) NULL COMMENT '维护时间'",
            'electrovalence' => "decimal(11,2) NULL COMMENT '电价'",
            'create_time' => "datetime NULL COMMENT '创建时间'",
            'update_time' => "datetime NULL COMMENT '更新时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED");
        
        /* 索引设置 */
        $this->createIndex('indx_weid','{{%diandi_place_member_config}}','store_id',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_member_config}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

