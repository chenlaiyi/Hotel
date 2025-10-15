<?php

use yii\db\Migration;

class m240417_014729_diandi_place_member_view extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_place_member_view}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL DEFAULT '0' COMMENT '公司ID'",
            'store_id' => "int(11) NULL DEFAULT '0' COMMENT '商户ID'",
            'member_id' => "int(11) NULL COMMENT '会员ID'",
            'hotel_id' => "int(11) NULL DEFAULT '0' COMMENT '酒店ID'",
            'room_id' => "int(11) NULL DEFAULT '0' COMMENT '房间ID'",
            'num' => "int(11) NULL DEFAULT '0' COMMENT '浏览次数'",
            'create_time' => "datetime NULL COMMENT '创建时间'",
            'update_time' => "datetime NULL COMMENT '更新时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED");
        
        /* 索引设置 */
        $this->createIndex('indx_weid','{{%diandi_place_member_view}}','store_id',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_member_view}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

