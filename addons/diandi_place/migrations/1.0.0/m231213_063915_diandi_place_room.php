<?php
use yii\db\Migration;
class m231213_063915_diandi_place_room extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%diandi_place_room}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL DEFAULT '0' COMMENT '公司ID'",
            'store_id' => "int(11) NULL COMMENT '商户ID'",
            'hotel_id' => "int(11) NULL DEFAULT '0' COMMENT '酒店ID'",
            'type_id' => "int(11) NULL COMMENT '房源类型，酒店，民宿，公寓等'",
            'title' => "varchar(255) NULL DEFAULT '' COMMENT '房间名称'",
            'tier_id' => "int(11) NULL COMMENT '楼层'",
            'unit_id' => "int(11) NULL COMMENT '单元'",
            'thumb' => "varchar(255) NULL DEFAULT '' COMMENT '房间主图'",
            'oprice' => "decimal(11,2) NULL DEFAULT '0.00' COMMENT '原价'",
            'cprice' => "decimal(11,2) NULL DEFAULT '0.00' COMMENT '现价'",
            'mprice' => "decimal(11,2) NULL DEFAULT '0.00' COMMENT '会员价'",
            'thumbs' => "text NULL COMMENT '房间相册'",
            'device' => "text NULL COMMENT '服务设施'",
            'room_pid' => "int(11) NULL DEFAULT '0' COMMENT '主房ID'",
            'is_suite' => "int(11) NULL DEFAULT '0' COMMENT '是否是套房'",
            'area' => "decimal(11,2) NULL COMMENT '面积'",
            'room_num' => "int(11) NULL COMMENT '几室'",
            'toilet_num' => "int(11) NULL COMMENT '几卫'",
            'bed_children' => "int(11) NULL COMMENT '儿童床位数'",
            'bed_adult' => "int(11) NULL COMMENT '成人床位数'",
            'bed' => "int(11) NULL COMMENT '床位数'",
            'cleaning_fee' => "decimal(11,2) NULL DEFAULT '0.00' COMMENT '清洁费'",
            'server_fee' => "decimal(11,2) NULL COMMENT '服务费'",
            'persons' => "int(11) NULL DEFAULT '0' COMMENT '最多容纳人数'",
            'bedadd' => "varchar(30) NULL DEFAULT '' COMMENT '是否可加床'",
            'status' => "int(11) NULL DEFAULT '0' COMMENT '房间状态'",
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
            'checkin_start' => "varchar(30) NULL COMMENT '入住开始时间'",
            'checkin_end' => "varchar(30) NULL COMMENT '入住结束时间'",
            'cancel_start' => "varchar(30) NULL COMMENT '取消开始时间'",
            'cancel_end' => "varchar(30) NULL COMMENT '退房结束时间'",
            'out_time' => "datetime NULL DEFAULT '0000-00-00 00:00:00' COMMENT '最后离店时间'",
            'time_length' => "int(11) NULL COMMENT '租约类型：时间，月，天，年'",
            'time_type' => "int(11) NULL DEFAULT '0' COMMENT '租期:1长租2短租'",
            'lease_type' => "int(11) NULL DEFAULT '0' COMMENT '1整租 2合租'",
            'desc' => "varchar(255) NULL COMMENT '房间介绍'",
            'content' => "text NULL COMMENT '房间详情'",
            'remark' => "text NULL COMMENT '备注'",
            'room_type' => "int(11) NULL COMMENT '全局房型'",
            'room_type_id' => "int(11) NULL DEFAULT '0' COMMENT '房型id'",
            'landlord_id' => "int(11) NULL COMMENT '房东'",
            'comment_num' => "int(11) NULL DEFAULT '0' COMMENT '评价数量'",
            'comment_start' => "decimal(11,2) NULL DEFAULT '5.00' COMMENT '评价星级'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");
        /* 索引设置 */
        $this->createIndex('indx_hotelid','{{%diandi_place_room}}','hotel_id',0);
        /* 表数据 */
        $this->insert('{{%diandi_place_room}}',['id'=>'1','bloc_id'=>'91','store_id'=>'2','hotel_id'=>'106','type_id'=>'23','title'=>'昆仑金','tier_id'=>'218','unit_id'=>NULL,'thumb'=>'202311/19/28670a12-3a72-344f-b04f-46ff70f660a3.jpg','oprice'=>'2.00','cprice'=>'2.00','mprice'=>'2.00','thumbs'=>'202311/19/2b471e2c-b83a-30f1-9885-1c24b5747a0a.jpg','device'=>'','room_pid'=>'0','is_suite'=>'0','area'=>'2.00','room_num'=>'11','toilet_num'=>'1','bed_children'=>'2','bed_adult'=>'2','bed'=>'4','cleaning_fee'=>'0.00','server_fee'=>NULL,'persons'=>'2','bedadd'=>'1','status'=>'1','isshow'=>'1','sales'=>'11','displayorder'=>'1','area_show'=>'1','floor_show'=>'1','smoke_show'=>'1','bed_show'=>'1','persons_show'=>'1','bedadd_show'=>'0','score'=>'1','breakfast'=>'1','language'=>'1','free_cancel'=>'1','checkin_start'=>'14:11:42','checkin_end'=>'15:11:42','cancel_start'=>'14:11:43','cancel_end'=>'15:11:43','out_time'=>'0000-00-00 00:00:00','time_length'=>NULL,'time_type'=>'0','lease_type'=>'0','desc'=>'房间简介','content'=>'<p>房间详情<br/></p>','remark'=>'','room_type'=>'0','room_type_id'=>'1','landlord_id'=>NULL,'comment_num'=>'0','comment_start'=>'5.00']);
        $this->insert('{{%diandi_place_room}}',['id'=>'2','bloc_id'=>'91','store_id'=>'2','hotel_id'=>'106','type_id'=>'23','title'=>'昆仑木','tier_id'=>'218','unit_id'=>NULL,'thumb'=>'202311/19/28670a12-3a72-344f-b04f-46ff70f660a3.jpg','oprice'=>'2.00','cprice'=>'2.00','mprice'=>'2.00','thumbs'=>'202311/19/2b471e2c-b83a-30f1-9885-1c24b5747a0a.jpg','device'=>'','room_pid'=>'0','is_suite'=>'0','area'=>'2.00','room_num'=>'11','toilet_num'=>'1','bed_children'=>'2','bed_adult'=>'2','bed'=>'4','cleaning_fee'=>'0.00','server_fee'=>NULL,'persons'=>'2','bedadd'=>'1','status'=>'1','isshow'=>'1','sales'=>'11','displayorder'=>'1','area_show'=>'1','floor_show'=>'1','smoke_show'=>'1','bed_show'=>'1','persons_show'=>'1','bedadd_show'=>'0','score'=>'1','breakfast'=>'1','language'=>'1','free_cancel'=>'1','checkin_start'=>'14:11:42','checkin_end'=>'15:11:42','cancel_start'=>'14:11:43','cancel_end'=>'15:11:43','out_time'=>'0000-00-00 00:00:00','time_length'=>NULL,'time_type'=>'0','lease_type'=>'0','desc'=>'房间简介','content'=>'<p>房间详情</p>','remark'=>'','room_type'=>'0','room_type_id'=>'1','landlord_id'=>NULL,'comment_num'=>'0','comment_start'=>'5.00']);
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_room}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
