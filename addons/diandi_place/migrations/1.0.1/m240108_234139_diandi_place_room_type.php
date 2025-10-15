<?php
use yii\db\Migration;
class m240108_234139_diandi_place_room_type extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%diandi_place_room_type}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL DEFAULT '0' COMMENT '公司ID'",
            'store_id' => "int(11) NULL COMMENT '商户ID'",
            'hotel_id' => "int(11) NULL COMMENT '酒店ID'",
            'type_id' => "int(11) NULL COMMENT '房源ID'",
            'cate_id' => "int(11) NULL COMMENT '全局房型分类'",
            'title' => "varchar(255) NULL DEFAULT '' COMMENT '房型名称'",
            'thumb' => "varchar(255) NULL DEFAULT '' COMMENT '房间主图'",
            'oprice' => "decimal(11,2) NULL DEFAULT '0.00' COMMENT '原价'",
            'cprice' => "decimal(11,2) NULL DEFAULT '0.00' COMMENT '现价'",
            'mprice' => "decimal(11,2) NULL DEFAULT '0.00' COMMENT '会员价'",
            'thumbs' => "text NULL COMMENT '房间相册'",
            'device' => "text NULL COMMENT '服务设施'",
            'is_suite' => "int(11) NULL DEFAULT '0' COMMENT '是否是套房'",
            'area' => "varchar(255) NULL DEFAULT '' COMMENT '面积'",
            'room_num' => "int(11) NULL COMMENT '几室'",
            'toilet_num' => "int(11) NULL COMMENT '几卫'",
            'floor' => "varchar(255) NULL DEFAULT '' COMMENT '楼层'",
            'out_time' => "datetime NULL",
            'bed_children' => "int(11) NULL COMMENT '儿童床位数'",
            'bed_adult' => "int(11) NULL COMMENT '成人床位数'",
            'bed_guest' => "int(11) NULL DEFAULT '0' COMMENT '客人床位数'",
            'bed' => "int(11) NULL COMMENT '床位数'",
            'cleaning_fee' => "decimal(11,2) NULL DEFAULT '0.00' COMMENT '清洁费'",
            'server_fee' => "decimal(11,2) NULL COMMENT '服务费'",
            'persons' => "int(11) NULL DEFAULT '0' COMMENT '最多容纳人数'",
            'bedadd' => "varchar(30) NULL DEFAULT '' COMMENT '是否可加床'",
            'isshow' => "int(11) NULL DEFAULT '0' COMMENT '是否显示'",
            'sales' => "text NULL COMMENT '销售数量'",
            'displayorder' => "int(11) NULL DEFAULT '0' COMMENT '排序'",
            'area_show' => "int(11) NULL DEFAULT '0' COMMENT '是否显示具体位置'",
            'floor_show' => "int(11) NULL DEFAULT '0' COMMENT '是否显示楼层'",
            'smoke_show' => "int(11) NULL DEFAULT '0' COMMENT '是否显示抽烟'",
            'bed_show' => "int(11) NULL DEFAULT '0' COMMENT '是否添加床位'",
            'persons_show' => "int(11) NULL COMMENT '是否显示添加人数'",
            'bedadd_show' => "int(11) NULL DEFAULT '0' COMMENT '是否显示添加床位'",
            'score' => "int(11) NULL DEFAULT '0' COMMENT '订房积分'",
            'breakfast' => "tinyint(3) NULL DEFAULT '0' COMMENT '0无早 1单早 2双早'",
            'language' => "tinyint(4) NOT NULL DEFAULT '0' COMMENT '语言类型标志/默认中文0'",
            'free_cancel' => "tinyint(4) NULL DEFAULT '0' COMMENT '是否免费取下1是0否'",
            'checkIn_start' => "varchar(30) NULL COMMENT '入住开始时间'",
            'checkIn_end' => "varchar(30) NULL COMMENT '入住结束时间'",
            'cancel_start' => "varchar(30) NULL COMMENT '取消开始时间'",
            'cancel_end' => "varchar(30) NULL COMMENT '退房结束时间'",
            'remark' => "text NULL COMMENT '备注'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8");
        /* 索引设置 */
        /* 表数据 */
        $this->insert('{{%diandi_place_room_type}}',['id'=>'1','bloc_id'=>'91','store_id'=>'2','hotel_id'=>NULL,'type_id'=>'6','cate_id'=>'0','title'=>'茶室','thumb'=>'202311/19/51fe4f4d-714d-31e0-9208-fa5764453bba.jpg','oprice'=>'2.00','cprice'=>'2.00','mprice'=>'2.00','thumbs'=>'202311/19/0a444258-919a-3fcd-95a9-ba4c9bfd2d42.jpg','device'=>NULL,'is_suite'=>'0','area'=>'2','room_num'=>'1','toilet_num'=>'11','floor'=>'','out_time'=>NULL,'bed_children'=>NULL,'bed_adult'=>NULL,'bed_guest'=>'0','bed'=>'2','cleaning_fee'=>'0.00','server_fee'=>NULL,'persons'=>'12','bedadd'=>'1','isshow'=>'1','sales'=>'1','displayorder'=>'1','area_show'=>'1','floor_show'=>'1','smoke_show'=>'1','bed_show'=>'1','persons_show'=>'1','bedadd_show'=>'0','score'=>'11','breakfast'=>'1','language'=>'1','free_cancel'=>'1','checkIn_start'=>NULL,'checkIn_end'=>NULL,'cancel_start'=>'14:10:38','cancel_end'=>'15:10:38','remark'=>NULL]);
        $this->insert('{{%diandi_place_room_type}}',['id'=>'3','bloc_id'=>'91','store_id'=>'153','hotel_id'=>NULL,'type_id'=>'15','cate_id'=>'18','title'=>'一室一厅一卫','thumb'=>'','oprice'=>'188.00','cprice'=>'0.01','mprice'=>'0.01','thumbs'=>'a:0:{}','device'=>NULL,'is_suite'=>'0','area'=>NULL,'room_num'=>NULL,'toilet_num'=>NULL,'floor'=>NULL,'out_time'=>NULL,'bed_children'=>NULL,'bed_adult'=>NULL,'bed_guest'=>NULL,'bed'=>NULL,'cleaning_fee'=>NULL,'server_fee'=>NULL,'persons'=>'3','bedadd'=>NULL,'isshow'=>'0','sales'=>'0','displayorder'=>'1','area_show'=>'0','floor_show'=>'0','smoke_show'=>'0','bed_show'=>'0','persons_show'=>'1','bedadd_show'=>'0','score'=>'0','breakfast'=>'0','language'=>'0','free_cancel'=>'1','checkIn_start'=>'00:00:00','checkIn_end'=>'00:00:00','cancel_start'=>'12:00:00','cancel_end'=>'13:00:00','remark'=>'']);
        $this->insert('{{%diandi_place_room_type}}',['id'=>'4','bloc_id'=>'91','store_id'=>'153','hotel_id'=>NULL,'type_id'=>'24','cate_id'=>'26','title'=>'棋牌室房型001','thumb'=>'202312/29/aee9dd7b-f3e2-3d3f-8f0b-3f5b54e3d7f9.png','oprice'=>'1.00','cprice'=>'2.00','mprice'=>'4.00','thumbs'=>'a:1:{i:0;s:50:"202312/29/ade57935-a4fa-357f-8ec8-fd7fc0231174.png";}','device'=>NULL,'is_suite'=>'0','area'=>NULL,'room_num'=>'0','toilet_num'=>NULL,'floor'=>NULL,'out_time'=>NULL,'bed_children'=>NULL,'bed_adult'=>NULL,'bed_guest'=>NULL,'bed'=>NULL,'cleaning_fee'=>NULL,'server_fee'=>NULL,'persons'=>'5','bedadd'=>NULL,'isshow'=>'0','sales'=>'1','displayorder'=>'5','area_show'=>'0','floor_show'=>'0','smoke_show'=>'0','bed_show'=>'0','persons_show'=>'0','bedadd_show'=>'0','score'=>'2','breakfast'=>'0','language'=>'0','free_cancel'=>'0','checkIn_start'=>'05:28:00','checkIn_end'=>'05:28:00','cancel_start'=>'05:28:00','cancel_end'=>'05:28:00','remark'=>'2']);
        $this->insert('{{%diandi_place_room_type}}',['id'=>'5','bloc_id'=>'91','store_id'=>'153','hotel_id'=>NULL,'type_id'=>'22','cate_id'=>NULL,'title'=>'公寓房型','thumb'=>'','oprice'=>'1.00','cprice'=>'2.00','mprice'=>'3.00','thumbs'=>'a:0:{}','device'=>NULL,'is_suite'=>'0','area'=>NULL,'room_num'=>NULL,'toilet_num'=>NULL,'floor'=>NULL,'out_time'=>NULL,'bed_children'=>NULL,'bed_adult'=>NULL,'bed_guest'=>NULL,'bed'=>NULL,'cleaning_fee'=>NULL,'server_fee'=>NULL,'persons'=>'1','bedadd'=>NULL,'isshow'=>'0','sales'=>'1','displayorder'=>'2','area_show'=>'0','floor_show'=>'0','smoke_show'=>'0','bed_show'=>'0','persons_show'=>'0','bedadd_show'=>'0','score'=>'3','breakfast'=>'0','language'=>'0','free_cancel'=>'0','checkIn_start'=>'05:28:00','checkIn_end'=>'05:28:00','cancel_start'=>'05:28:00','cancel_end'=>'05:28:00','remark'=>'212']);
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_room_type}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
