<?php

use yii\db\Migration;

class m240417_014729_diandi_cloud_addons_cate extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_cloud_addons_cate}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID'",
            'pid' => "int(11) NOT NULL DEFAULT '0' COMMENT '上级 ID'",
            'name' => "varchar(45) NOT NULL COMMENT '分类名称'",
            'sort' => "int(11) NOT NULL DEFAULT '0' COMMENT '排序值'",
            'created_at' => "datetime NOT NULL COMMENT '创建时间'",
            'updated_at' => "datetime NOT NULL COMMENT '更新时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%diandi_cloud_addons_cate}}',['id'=>'1','pid'=>'1','name'=>'分类1','sort'=>'1','created_at'=>'2022-07-08 10:33:00','updated_at'=>'2022-07-08 10:23:17']);
        $this->insert('{{%diandi_cloud_addons_cate}}',['id'=>'2','pid'=>'2','name'=>'1','sort'=>'1','created_at'=>'2022-07-08 10:34:02','updated_at'=>'2022-07-08 10:33:56']);
        $this->insert('{{%diandi_cloud_addons_cate}}',['id'=>'3','pid'=>'3','name'=>'11','sort'=>'1','created_at'=>'2022-07-08 10:35:52','updated_at'=>'2022-07-08 10:35:43']);
        $this->insert('{{%diandi_cloud_addons_cate}}',['id'=>'4','pid'=>'0','name'=>'经营场所','sort'=>'1','created_at'=>'2023-02-20 10:05:10','updated_at'=>'2022-07-08 10:38:54']);
        $this->insert('{{%diandi_cloud_addons_cate}}',['id'=>'5','pid'=>'4','name'=>'12','sort'=>'65151','created_at'=>'2022-07-08 10:40:38','updated_at'=>'2022-07-08 10:40:23']);
        $this->insert('{{%diandi_cloud_addons_cate}}',['id'=>'6','pid'=>'0','name'=>'电商','sort'=>'2','created_at'=>'2023-02-20 10:05:37','updated_at'=>'2023-02-20 10:04:49']);
        $this->insert('{{%diandi_cloud_addons_cate}}',['id'=>'7','pid'=>'0','name'=>'政企服务','sort'=>'3','created_at'=>'2023-02-20 10:05:45','updated_at'=>'2023-02-20 10:05:01']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_cloud_addons_cate}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

