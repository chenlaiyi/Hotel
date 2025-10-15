<?php
use yii\db\Migration;
class m231213_063915_diandi_place_list extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%diandi_place_list}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL DEFAULT '0' COMMENT '公司ID'",
            'store_id' => "int(11) NULL DEFAULT '0' COMMENT '商家ID'",
            'name' => "varchar(255) NULL DEFAULT '' COMMENT '酒店名称'",
            'lng' => "decimal(10,2) NULL COMMENT '经度'",
            'lat' => "decimal(10,2) NULL COMMENT '维度'",
            'is_show' => "int(11) NULL COMMENT '是否公开'",
            'address_show' => "int(11) NULL COMMENT '是否公开地址'",
            'type' => "int(11) NULL COMMENT '房源类型'",
            'address' => "varchar(255) NULL DEFAULT '' COMMENT '具体地址'",
            'location_p' => "int(11) NULL DEFAULT '0' COMMENT '省份'",
            'location_c' => "int(11) NULL DEFAULT '0' COMMENT '城市'",
            'location_a' => "int(11) NULL DEFAULT '0' COMMENT '区县'",
            'roomcount' => "int(11) NULL DEFAULT '0' COMMENT '房间总量'",
            'status' => "int(11) NULL DEFAULT '0' COMMENT '酒店状态'",
            'phone' => "varchar(255) NULL DEFAULT '' COMMENT '联系电话'",
            'mail' => "varchar(255) NULL DEFAULT '' COMMENT '联系邮箱'",
            'thumb' => "varchar(255) NULL DEFAULT '' COMMENT '酒店图片'",
            'description' => "text NULL COMMENT '酒店简介'",
            'content' => "text NULL COMMENT '酒店介绍'",
            'traffic' => "text NULL COMMENT '周边交通'",
            'thumbs' => "text NULL COMMENT '酒店相册'",
            'sales' => "text NULL",
            'displayorder' => "int(11) NULL DEFAULT '0' COMMENT '排序'",
            'comment_num' => "int(11) NULL DEFAULT '0' COMMENT '评价数量'",
            'comment_start' => "decimal(11,2) NULL COMMENT '评价星级'",
            'level' => "int(11) NULL DEFAULT '0' COMMENT '酒店星级'",
            'device' => "text NULL COMMENT '服务设施'",
            'brandid' => "int(11) NULL DEFAULT '0' COMMENT '所属品牌'",
            'language' => "tinyint(4) NOT NULL DEFAULT '0' COMMENT '语言类型标志/默认中文0'",
            'landlord_id' => "int(11) NULL DEFAULT '0' COMMENT '房东ID'",
            'create_time' => "datetime NULL DEFAULT '0000-00-00 00:00:00'",
            'update_time' => "datetime NULL DEFAULT '0000-00-00 00:00:00'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='酒店主表'");
        /* 索引设置 */
        /* 表数据 */
        $this->insert('{{%diandi_place_list}}',['id'=>'106','bloc_id'=>'38','store_id'=>'2','name'=>'昆仑巢','lng'=>NULL,'lat'=>NULL,'is_show'=>NULL,'address_show'=>NULL,'type'=>'23','address'=>'北京市第十二中学','location_p'=>'0','location_c'=>'0','location_a'=>'0','roomcount'=>'15','status'=>'1','phone'=>'','mail'=>'12','thumb'=>'202311/19/bec2edc9-d90b-3be5-80ac-059292dbe4ff.jpg','description'=>'','content'=>'','traffic'=>'12','thumbs'=>'a:1:{i:0;s:50:"202311/19/9610290c-8d8e-3e58-abdf-8ab2d5606a36.jpg";}','sales'=>'55','displayorder'=>'12','comment_num'=>'0','comment_start'=>NULL,'level'=>'12','device'=>'12','brandid'=>'23','language'=>'0','landlord_id'=>'0','create_time'=>'2023-11-19 13:59:35','update_time'=>'2023-11-19 20:23:45']);
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_list}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
