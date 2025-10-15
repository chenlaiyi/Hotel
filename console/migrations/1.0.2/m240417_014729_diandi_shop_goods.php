<?php

use yii\db\Migration;

class m240417_014729_diandi_shop_goods extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_shop_goods}}', [
            'goods_id' => "int(11) unsigned NOT NULL AUTO_INCREMENT",
            'store_id' => "int(11) NULL COMMENT '商户id'",
            'bloc_id' => "int(11) NULL COMMENT '公司id'",
            'goods_name' => "varchar(255) NOT NULL DEFAULT '' COMMENT '商品名称'",
            'category_pid' => "int(11) NULL COMMENT '商品父级分类'",
            'stock' => "int(11) NULL DEFAULT '0' COMMENT '库存'",
            'video' => "varchar(255) NULL COMMENT '商品视频'",
            'category_id' => "int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品分类'",
            'spec_type' => "tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启多规格'",
            'deduct_stock_type' => "tinyint(3) unsigned NOT NULL DEFAULT '20' COMMENT '库存减少方式'",
            'thumb' => "varchar(255) NULL COMMENT '商品主图'",
            'line_price' => "decimal(10,2) NULL COMMENT '市场价格'",
            'goods_weight' => "decimal(10,0) NULL COMMENT '商品重量'",
            'goods_price' => "decimal(10,2) NULL COMMENT '商品售价'",
            'content' => "longtext NOT NULL COMMENT '商品介绍'",
            'sales_initial' => "int(11) unsigned NOT NULL DEFAULT '0' COMMENT '虚拟销量'",
            'sales_actual' => "int(11) unsigned NOT NULL DEFAULT '0' COMMENT '真实销量'",
            'goods_sort' => "int(11) unsigned NOT NULL DEFAULT '100' COMMENT '商品排序'",
            'delivery_id' => "int(11) unsigned NOT NULL DEFAULT '0' COMMENT '运费模板'",
            'goods_status' => "tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '商品是否上架0下架1上架'",
            'is_delete' => "tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除'",
            'wxapp_id' => "int(11) unsigned NOT NULL DEFAULT '0'",
            'create_time' => "int(11) unsigned NOT NULL DEFAULT '0'",
            'update_time' => "int(11) unsigned NOT NULL DEFAULT '0'",
            'images' => "text NULL COMMENT '商品相册'",
            'browse' => "int(11) NULL DEFAULT '0'",
            'label' => "varchar(4) NULL",
            'PRIMARY KEY (`goods_id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");
        
        /* 索引设置 */
        $this->createIndex('category_id','{{%diandi_shop_goods}}','category_id',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_shop_goods}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

