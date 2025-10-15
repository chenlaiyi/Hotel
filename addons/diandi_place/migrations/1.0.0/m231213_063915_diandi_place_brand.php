<?php
use yii\db\Migration;
class m231213_063915_diandi_place_brand extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%diandi_place_brand}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL DEFAULT '0' COMMENT '公司ID'",
            'title' => "varchar(255) NULL DEFAULT '' COMMENT '品牌名称'",
            'displayorder' => "int(11) NULL DEFAULT '0' COMMENT '排序'",
            'status' => "int(11) NULL DEFAULT '0' COMMENT '状态1启用0未启用'",
            'create_time' => "datetime NULL DEFAULT '0000-00-00 00:00:00'",
            'update_time' => "datetime NULL DEFAULT '0000-00-00 00:00:00'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='酒店品牌'");
        /* 索引设置 */
        $this->createIndex('indx_displayorder','{{%diandi_place_brand}}','displayorder',0);
        /* 表数据 */
        $this->insert('{{%diandi_place_brand}}',['id'=>'5','bloc_id'=>'91','title'=>'12','displayorder'=>'12','status'=>'1','create_time'=>'2023-06-20 10:15:07','update_time'=>'2023-06-20 10:32:19']);
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_brand}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
