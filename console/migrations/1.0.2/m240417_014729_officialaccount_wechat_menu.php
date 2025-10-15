<?php

use yii\db\Migration;

class m240417_014729_officialaccount_wechat_menu extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%officialaccount_wechat_menu}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '粉丝id'",
            'bloc_id' => "int(11) NULL",
            'store_id' => "int(11) NULL",
            'update_time' => "datetime NULL COMMENT '更新时间'",
            'create_time' => "datetime NULL",
            'menuName' => "varchar(30) NULL COMMENT '菜单名称'",
            'parentId' => "int(11) NULL COMMENT '父级id'",
            'menuLevel' => "int(11) NULL COMMENT '菜单等级'",
            'msgType' => "int(11) NULL COMMENT '消息类型'",
            'menuType' => "int(11) NULL COMMENT '菜单类型'",
            'menuUrl' => "varchar(255) NULL COMMENT '菜单URL'",
            'menuSort' => "int(11) NULL COMMENT '菜单排序'",
            'appid' => "varchar(50) NULL COMMENT '小程序appid'",
            'pagepath' => "varchar(100) NULL COMMENT '小程序页面路径'",
            'media_id' => "int(11) NULL COMMENT '素材ID'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='公众号粉丝表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%officialaccount_wechat_menu}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

