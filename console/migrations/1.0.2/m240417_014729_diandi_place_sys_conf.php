<?php

use yii\db\Migration;

class m240417_014729_diandi_place_sys_conf extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_place_sys_conf}}', [
            'id' => "int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '人脸招聘'",
            'bloc_id' => "int(11) NOT NULL COMMENT '人脸库组id'",
            'store_id' => "int(11) NOT NULL",
            'create_time' => "datetime NULL",
            'update_time' => "datetime NULL",
            'index_thumb' => "varchar(255) NULL COMMENT '首页头部图片'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='全局配置表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%diandi_place_sys_conf}}',['id'=>'1','bloc_id'=>'38','store_id'=>'51','create_time'=>NULL,'update_time'=>NULL,'index_thumb'=>'202306/02/05758126-e2a0-34b1-a45e-8f61b99ade75.png']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_sys_conf}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

