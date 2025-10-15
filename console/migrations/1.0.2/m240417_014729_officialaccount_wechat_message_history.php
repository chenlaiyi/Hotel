<?php

use yii\db\Migration;

class m240417_014729_officialaccount_wechat_message_history extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%officialaccount_wechat_message_history}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL",
            'store_id' => "int(11) NULL",
            'rid' => "int(11) NULL COMMENT '相应规则ID'",
            'kid' => "int(11) NULL COMMENT '所属关键字ID'",
            'from' => "varchar(100) NULL COMMENT '请求用户ID'",
            'module' => "varchar(50) NULL COMMENT '处理模块'",
            'message' => "varchar(255) NULL COMMENT '消息体内容'",
            'type' => "int(11) NULL COMMENT '发送类型'",
            'create_time' => "datetime NULL",
            'update_time' => "datetime NULL",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='消息发送记录'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%officialaccount_wechat_message_history}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

