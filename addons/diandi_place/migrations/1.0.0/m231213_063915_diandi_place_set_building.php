<?php
use yii\db\Migration;
class m231213_063915_diandi_place_set_building extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%diandi_place_set_building}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL COMMENT '公司ID'",
            'store_id' => "int(11) NULL DEFAULT '0' COMMENT '商户ID'",
            'title' => "varchar(255) NULL DEFAULT '' COMMENT '楼栋名称'",
            'type' => "int(11) NOT NULL COMMENT '房源类型（酒店，公寓，名宿，茶室）'",
            'is_address' => "int(11) NULL DEFAULT '0' COMMENT '是否设置具体地址'",
            'address' => "varchar(255) NULL DEFAULT '0' COMMENT '具体地址'",
            'province' => "int(11) NULL DEFAULT '0' COMMENT '省份'",
            'city' => "int(11) NULL DEFAULT '0' COMMENT '城市'",
            'county' => "int(11) NULL DEFAULT '0' COMMENT '区县'",
            'create_time' => "datetime NULL DEFAULT '0000-00-00 00:00:00'",
            'update_time' => "datetime NULL DEFAULT '0000-00-00 00:00:00'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='楼栋管理'");
        /* 索引设置 */
        $this->createIndex('indx_weid','{{%diandi_place_set_building}}','store_id',0);
        /* 表数据 */
        $this->insert('{{%diandi_place_set_building}}',['id'=>'8','bloc_id'=>'0','store_id'=>'0','title'=>'','type'=>'0','is_address'=>NULL,'address'=>'1','province'=>'0','city'=>NULL,'county'=>NULL,'create_time'=>'0000-00-00 00:00:00','update_time'=>'0000-00-00 00:00:00']);
        $this->insert('{{%diandi_place_set_building}}',['id'=>'9','bloc_id'=>'51','store_id'=>'0','title'=>'','type'=>'10','is_address'=>NULL,'address'=>'1','province'=>'0','city'=>NULL,'county'=>NULL,'create_time'=>'0000-00-00 00:00:00','update_time'=>'0000-00-00 00:00:00']);
        $this->insert('{{%diandi_place_set_building}}',['id'=>'5','bloc_id'=>'0','store_id'=>'0','title'=>'2','type'=>'0','is_address'=>NULL,'address'=>'1','province'=>'2','city'=>NULL,'county'=>NULL,'create_time'=>'0000-00-00 00:00:00','update_time'=>'0000-00-00 00:00:00']);
        $this->insert('{{%diandi_place_set_building}}',['id'=>'6','bloc_id'=>'0','store_id'=>'0','title'=>'3','type'=>'0','is_address'=>NULL,'address'=>'1','province'=>'3','city'=>NULL,'county'=>NULL,'create_time'=>'0000-00-00 00:00:00','update_time'=>'0000-00-00 00:00:00']);
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_set_building}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
