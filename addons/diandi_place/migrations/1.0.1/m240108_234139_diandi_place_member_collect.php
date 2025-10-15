<?php
use yii\db\Migration;
class m240108_234139_diandi_place_member_collect extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%diandi_place_member_collect}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL",
            'store_id' => "int(11) NULL",
            'member_id' => "int(11) NULL COMMENT '会员id'",
            'to_id' => "int(11) NULL COMMENT '收藏对象id'",
            'status' => "tinyint(1) NULL COMMENT '酒店 公寓 民宿'",
            'template_type' => "tinyint(1) NULL",
            'create_time' => "datetime NULL",
            'update_time' => "datetime NULL",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        /* 索引设置 */
        /* 表数据 */
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_member_collect}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
