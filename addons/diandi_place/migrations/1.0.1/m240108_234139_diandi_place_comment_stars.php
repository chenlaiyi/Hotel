<?php
use yii\db\Migration;
class m240108_234139_diandi_place_comment_stars extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%diandi_place_comment_stars}}', [
            'id' => "int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id'",
            'bloc_id' => "int(11) NOT NULL COMMENT '公司ID'",
            'store_id' => "int(11) NOT NULL COMMENT '商户ID'",
            'comment_id' => "int(11) NULL COMMENT '评价ID'",
            'create_time' => "datetime NULL COMMENT '创建时间'",
            'update_time' => "datetime NULL COMMENT '更新时间'",
            'title' => "varchar(100) NULL COMMENT '评论内容'",
            'start_num' => "int(11) NULL COMMENT '评价星级'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='评论表'");
        /* 索引设置 */
        /* 表数据 */
        $this->insert('{{%diandi_place_comment_stars}}',['id'=>'1','bloc_id'=>'0','store_id'=>'0','comment_id'=>'2','create_time'=>'0000-00-00 00:00:00','update_time'=>'0000-00-00 00:00:00','title'=>'2','start_num'=>'2']);
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_comment_stars}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
