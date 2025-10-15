<?php

use yii\db\Migration;

class m240417_014729_diandi_cloud_addons extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_cloud_addons}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '模块id'",
            'mid' => "int(11) NULL COMMENT '系统模块ID'",
            'is_nav' => "int(11) NULL COMMENT '是否导航'",
            'identifie' => "varchar(100) NOT NULL COMMENT '英文标识'",
            'type' => "varchar(30) NULL DEFAULT 'base' COMMENT '模块类型'",
            'title' => "varchar(100) NOT NULL COMMENT '名称'",
            'version' => "varchar(15) NOT NULL COMMENT '版本'",
            'ability' => "varchar(500) NOT NULL COMMENT '简介'",
            'description' => "varchar(1000) NOT NULL COMMENT '描述'",
            'author' => "varchar(50) NOT NULL COMMENT '作者'",
            'url' => "varchar(255) NOT NULL COMMENT '社区地址'",
            'settings' => "tinyint(1) NOT NULL DEFAULT '0' COMMENT '配置'",
            'logo' => "varchar(250) NOT NULL COMMENT 'logo'",
            'versions' => "varchar(50) NULL COMMENT '适应的软件版本'",
            'is_install' => "tinyint(1) NULL",
            'parent_mids' => "varchar(250) NULL DEFAULT '0'",
            'cate_id' => "int(11) NOT NULL COMMENT '分类ID'",
            'applets' => "varchar(180) NOT NULL DEFAULT '' COMMENT '小程序二维码'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='扩展模块表'");
        
        /* 索引设置 */
        $this->createIndex('idx_name','{{%diandi_cloud_addons}}','identifie',0);
        
        
        /* 表数据 */
        $this->insert('{{%diandi_cloud_addons}}',['id'=>'1','mid'=>'140','is_nav'=>'1','identifie'=>'diandi_distribution','type'=>'电商','title'=>'多商户分销','version'=>'1.0.0','ability'=>'简介','description'=>'<p>描述</p>','author'=>'我','url'=>'https://www.hopesfire.com/forum.php?mod=forumdisplay&fid=94&filter=typeid&typeid=23','settings'=>'1','logo'=>'202207/15/b0975b5c-f45e-3563-8e66-2211fc03d078.png','versions'=>'1.1.9','is_install'=>'1','parent_mids'=>'1','cate_id'=>'6','applets'=>'202207/11/681bba82-a435-3960-9f37-57f732b2062a.jpg']);
        $this->insert('{{%diandi_cloud_addons}}',['id'=>'2','mid'=>'152','is_nav'=>'1','identifie'=>'diandi_shop','type'=>'电商','title'=>'单商户点单','version'=>'1.0.0','ability'=>'简介','description'=>'<p>具体描述</p>','author'=>'王春生','url'=>'https://www.hopesfire.com/forum.php?mod=forumdisplay&fid=94&filter=typeid&typeid=24','settings'=>'0','logo'=>'202207/15/db84fa41-6d45-3d79-a705-d5d838aa2bcd.png','versions'=>'1.0.0','is_install'=>'1','parent_mids'=>'1','cate_id'=>'6','applets'=>'202207/11/54c752df-76cc-3d06-aa55-9df6dbda9d25.jpg']);
        $this->insert('{{%diandi_cloud_addons}}',['id'=>'3','mid'=>'156','is_nav'=>'1','identifie'=>'diandi_website','type'=>'官网','title'=>'企业官网','version'=>'1.0.0','ability'=>'简介','description'=>'<p>描述</p>','author'=>'王春生','url'=>'https://www.hopesfire.com/forum.php?mod=forumdisplay&fid=94&filter=typeid&typeid=31','settings'=>'0','logo'=>'202207/15/3e4b800e-5d22-30b6-8384-18e7d4cf0f09.png','versions'=>'1.0.0','is_install'=>'1','parent_mids'=>'1','cate_id'=>'7','applets'=>'202207/11/631934b4-217d-3081-bce8-886b2f7dec60.jpg']);
        $this->insert('{{%diandi_cloud_addons}}',['id'=>'4','mid'=>NULL,'is_nav'=>'1','identifie'=>'diandi_integral','type'=>'base','title'=>'积分商城','version'=>'1.0.0','ability'=>'简介','description'=>'<p>说明</p>','author'=>'王春生','url'=>'https://www.hopesfire.com/forum.php?mod=forumdisplay&fid=94&filter=typeid&typeid=32','settings'=>'1','logo'=>'202207/15/1f4010a0-9d21-360f-a7e3-1f6a03048885.png','versions'=>'1.0.0','is_install'=>'1','parent_mids'=>'3','cate_id'=>'6','applets'=>'202207/11/1d2a385d-79b9-3d43-bf87-abd34c2f17f4.jpg']);
        $this->insert('{{%diandi_cloud_addons}}',['id'=>'5','mid'=>'163','is_nav'=>'1','identifie'=>'diandi_party','type'=>'1','title'=>'企业党建','version'=>'1.0.0','ability'=>'简介','description'=>'<p>描述</p>','author'=>'王春生','url'=>'https://www.hopesfire.com/forum.php?mod=forumdisplay&fid=94&filter=typeid&typeid=28','settings'=>'1','logo'=>'202207/15/f20116ca-7081-3bd3-9638-c656a89034ad.png','versions'=>'1.0.0','is_install'=>'1','parent_mids'=>'1','cate_id'=>'7','applets'=>'202207/11/78a8b284-9a2e-3988-bb48-c64e32cdff05.jpg']);
        $this->insert('{{%diandi_cloud_addons}}',['id'=>'6','mid'=>NULL,'is_nav'=>'1','identifie'=>'diandi_tea','type'=>'1','title'=>'无人茶室','version'=>'1.0.0','ability'=>'简介','description'=>'<p>描述</p>','author'=>'王春生','url'=>'https://www.hopesfire.com/forum.php?mod=forumdisplay&fid=94&filter=typeid&typeid=26','settings'=>'1','logo'=>'202207/15/a64f8ec1-58a9-31db-8e07-f9014f41f999.png','versions'=>'1.0.0','is_install'=>'1','parent_mids'=>'1','cate_id'=>'4','applets'=>'202207/11/5a354a95-314a-3227-93f7-ed10260df2bf.jpg']);
        $this->insert('{{%diandi_cloud_addons}}',['id'=>'7','mid'=>'166','is_nav'=>'1','identifie'=>'diandi_farm','type'=>'农业','title'=>'农业认养','version'=>'1.0.0','ability'=>'简介','description'=>'<p>描述</p>','author'=>'王春生','url'=>'https://www.hopesfire.com/forum.php?mod=forumdisplay&fid=94&filter=typeid&typeid=29','settings'=>'1','logo'=>'202207/15/09a3434f-ee9d-35d0-839e-de714a8f6d49.png','versions'=>'1.0.0','is_install'=>'1','parent_mids'=>'1','cate_id'=>'6','applets'=>'202207/11/7d35032e-474a-37b5-a5fd-c9729ebf4278.jpg']);
        $this->insert('{{%diandi_cloud_addons}}',['id'=>'8','mid'=>'167','is_nav'=>'1','identifie'=>'diandi_flower','type'=>'电商','title'=>'花卉电商','version'=>'1.0.0','ability'=>'简介','description'=>'<p>描述</p>','author'=>'王春生','url'=>'https://www.hopesfire.com/forum.php?mod=forumdisplay&fid=94&filter=typeid&typeid=30','settings'=>'1','logo'=>'202207/15/7e574aed-4c70-3f50-a56e-8d6e864564a1.png','versions'=>'1.0.0','is_install'=>'1','parent_mids'=>'1','cate_id'=>'6','applets'=>'202207/11/69448657-1f8a-3633-98e3-af9a17042b7a.jpg']);
        $this->insert('{{%diandi_cloud_addons}}',['id'=>'9','mid'=>'199','is_nav'=>'1','identifie'=>'diandi_hotel','type'=>'base','title'=>'酒店公寓','version'=>'1.0.0','ability'=>'12','description'=>'<p>23</p>','author'=>'王春生','url'=>'12','settings'=>'12','logo'=>'202302/20/270c7adf-17c2-3452-a4c7-5f4e1ff959c8.jpg','versions'=>'1','is_install'=>'1','parent_mids'=>'0','cate_id'=>'4','applets'=>'202302/20/f2ee862b-ae73-339d-9842-b995d48fe229.jpg']);
        $this->insert('{{%diandi_cloud_addons}}',['id'=>'10','mid'=>'200','is_nav'=>'1','identifie'=>'bea_cloud','type'=>'美业','title'=>'美业门店','version'=>'1.0.0','ability'=>'12','description'=>'<p>32</p>','author'=>'1','url'=>'12','settings'=>'23','logo'=>'202302/20/bd06ce82-e543-3a6c-bf0f-40df826cc546.jpg','versions'=>'21','is_install'=>'1','parent_mids'=>'0','cate_id'=>'4','applets'=>'202302/20/91dead81-d9c1-3e82-835e-f0de57745aa9.png']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_cloud_addons}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

