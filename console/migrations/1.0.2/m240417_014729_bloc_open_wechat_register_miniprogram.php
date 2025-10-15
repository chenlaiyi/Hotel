<?php

use yii\db\Migration;

class m240417_014729_bloc_open_wechat_register_miniprogram extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%bloc_open_wechat_register_miniprogram}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(10) unsigned NULL",
            'name' => "varchar(255) NULL COMMENT '企业名（需与工商部门登记信息一致）'",
            'code' => "varchar(255) NULL COMMENT '企业代码'",
            'code_type' => "int(11) NULL COMMENT '企业代码类型'",
            'legal_persona_wechat' => "varchar(100) NULL COMMENT '法人微信号'",
            'legal_persona_name' => "varchar(255) NULL COMMENT '法人姓名'",
            'component_phone' => "varchar(50) NULL COMMENT '第三方联系电话'",
            'errcode' => "int(11) NULL COMMENT '错误码'",
            'errmsg' => "varchar(100) NULL COMMENT '错误信息'",
            'status' => "int(11) NULL COMMENT '状态'",
            'update_time' => "datetime NULL",
            'create_time' => "datetime NULL",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='快速注册微信小程序'");
        
        /* 索引设置 */
        $this->createIndex('bloc_id','{{%bloc_open_wechat_register_miniprogram}}','bloc_id, errcode, errmsg',1);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%bloc_open_wechat_register_miniprogram}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

