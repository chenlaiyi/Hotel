<?php
use yii\db\Migration;
class m240108_234139_diandi_place_member_view extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%diandi_place_member_view}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL DEFAULT '0' COMMENT '公司ID'",
            'store_id' => "int(11) NULL DEFAULT '0' COMMENT '商户ID'",
            'member_id' => "int(11) NULL COMMENT '会员ID'",
            'hotel_id' => "int(11) NULL DEFAULT '0' COMMENT '酒店ID'",
            'room_id' => "int(11) NULL DEFAULT '0' COMMENT '房间ID'",
            'num' => "int(11) NULL DEFAULT '0' COMMENT '浏览次数'",
            'create_time' => "datetime NULL COMMENT '创建时间'",
            'update_time' => "datetime NULL COMMENT '更新时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8");
        /* 索引设置 */
        $this->createIndex('indx_weid','{{%diandi_place_member_view}}','store_id',0);
        /* 表数据 */
        $this->insert('{{%diandi_place_member_view}}',['id'=>'8','bloc_id'=>'38','store_id'=>'51','member_id'=>'1','hotel_id'=>'28','room_id'=>'0','num'=>'16','create_time'=>'2023-06-05 00:09:08','update_time'=>'2023-06-19 15:09:36']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'9','bloc_id'=>'38','store_id'=>'51','member_id'=>'1','hotel_id'=>'1','room_id'=>'0','num'=>'46','create_time'=>'2023-06-05 00:36:01','update_time'=>'2023-06-19 15:18:53']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'10','bloc_id'=>'38','store_id'=>'51','member_id'=>'1','hotel_id'=>'0','room_id'=>'0','num'=>'6','create_time'=>'2023-06-05 15:31:38','update_time'=>'2023-06-08 17:59:34']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'11','bloc_id'=>'38','store_id'=>'51','member_id'=>'278','hotel_id'=>'1','room_id'=>'0','num'=>'393','create_time'=>'2023-06-06 09:03:01','update_time'=>'2023-06-25 16:42:38']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'12','bloc_id'=>'38','store_id'=>'138','member_id'=>'278','hotel_id'=>'36','room_id'=>'0','num'=>'105','create_time'=>'2023-06-07 10:55:36','update_time'=>'2023-06-09 09:06:27']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'13','bloc_id'=>'38','store_id'=>'138','member_id'=>'278','hotel_id'=>'35','room_id'=>'0','num'=>'5','create_time'=>'2023-06-07 13:37:46','update_time'=>'2023-06-08 14:19:29']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'14','bloc_id'=>'38','store_id'=>'51','member_id'=>'278','hotel_id'=>'0','room_id'=>'0','num'=>'14','create_time'=>'2023-06-07 17:07:45','update_time'=>'2023-06-25 09:31:04']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'15','bloc_id'=>'38','store_id'=>'138','member_id'=>'278','hotel_id'=>'5','room_id'=>'0','num'=>'5','create_time'=>'2023-06-07 17:13:25','update_time'=>'2023-06-08 13:59:32']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'16','bloc_id'=>'38','store_id'=>'138','member_id'=>'278','hotel_id'=>'22','room_id'=>'0','num'=>'5','create_time'=>'2023-06-08 13:50:43','update_time'=>'2023-06-12 14:36:00']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'17','bloc_id'=>'38','store_id'=>'138','member_id'=>'278','hotel_id'=>'34','room_id'=>'0','num'=>'1','create_time'=>'2023-06-08 14:19:38','update_time'=>'2023-06-08 14:19:38']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'18','bloc_id'=>'38','store_id'=>'138','member_id'=>'278','hotel_id'=>'30','room_id'=>'0','num'=>'11','create_time'=>'2023-06-08 14:19:48','update_time'=>'2023-06-19 18:16:24']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'19','bloc_id'=>'38','store_id'=>'138','member_id'=>'278','hotel_id'=>'28','room_id'=>'0','num'=>'17','create_time'=>'2023-06-08 14:20:10','update_time'=>'2023-06-19 18:16:21']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'20','bloc_id'=>'38','store_id'=>'138','member_id'=>'278','hotel_id'=>'21','room_id'=>'0','num'=>'2','create_time'=>'2023-06-08 14:20:41','update_time'=>'2023-06-12 14:35:57']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'21','bloc_id'=>'38','store_id'=>'138','member_id'=>'278','hotel_id'=>'19','room_id'=>'0','num'=>'9','create_time'=>'2023-06-08 14:21:07','update_time'=>'2023-06-19 18:16:29']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'22','bloc_id'=>'38','store_id'=>'138','member_id'=>'278','hotel_id'=>'6','room_id'=>'0','num'=>'1','create_time'=>'2023-06-08 14:21:21','update_time'=>'2023-06-08 14:21:21']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'23','bloc_id'=>'38','store_id'=>'138','member_id'=>'278','hotel_id'=>'17','room_id'=>'0','num'=>'4','create_time'=>'2023-06-09 11:38:59','update_time'=>'2023-06-12 14:35:49']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'24','bloc_id'=>'38','store_id'=>'138','member_id'=>'270','hotel_id'=>'28','room_id'=>'0','num'=>'2','create_time'=>'2023-06-10 21:37:33','update_time'=>'2023-06-20 09:02:16']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'25','bloc_id'=>'38','store_id'=>'138','member_id'=>'293','hotel_id'=>'1','room_id'=>'0','num'=>'5','create_time'=>'2023-06-12 09:18:40','update_time'=>'2023-06-12 10:41:48']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'26','bloc_id'=>'38','store_id'=>'138','member_id'=>'293','hotel_id'=>'5','room_id'=>'0','num'=>'1','create_time'=>'2023-06-12 09:18:57','update_time'=>'2023-06-12 09:18:57']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'27','bloc_id'=>'38','store_id'=>'138','member_id'=>'293','hotel_id'=>'19','room_id'=>'0','num'=>'1','create_time'=>'2023-06-12 09:19:03','update_time'=>'2023-06-12 09:19:03']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'28','bloc_id'=>'38','store_id'=>'138','member_id'=>'293','hotel_id'=>'36','room_id'=>'0','num'=>'1','create_time'=>'2023-06-12 09:20:21','update_time'=>'2023-06-12 09:20:21']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'29','bloc_id'=>'38','store_id'=>'138','member_id'=>'293','hotel_id'=>'28','room_id'=>'0','num'=>'1','create_time'=>'2023-06-12 10:35:29','update_time'=>'2023-06-12 10:35:29']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'30','bloc_id'=>'38','store_id'=>'138','member_id'=>'293','hotel_id'=>'37','room_id'=>'0','num'=>'1','create_time'=>'2023-06-12 10:38:28','update_time'=>'2023-06-12 10:38:28']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'31','bloc_id'=>'38','store_id'=>'138','member_id'=>'278','hotel_id'=>'37','room_id'=>'0','num'=>'5','create_time'=>'2023-06-12 14:34:35','update_time'=>'2023-06-12 14:43:44']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'32','bloc_id'=>'38','store_id'=>'138','member_id'=>'1','hotel_id'=>'17','room_id'=>'0','num'=>'1','create_time'=>'2023-06-13 07:28:54','update_time'=>'2023-06-13 07:28:54']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'33','bloc_id'=>'38','store_id'=>'138','member_id'=>'1','hotel_id'=>'37','room_id'=>'0','num'=>'2','create_time'=>'2023-06-13 07:29:01','update_time'=>'2023-06-16 11:06:55']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'34','bloc_id'=>'38','store_id'=>'138','member_id'=>'1','hotel_id'=>'39','room_id'=>'0','num'=>'2','create_time'=>'2023-06-16 11:06:51','update_time'=>'2023-06-19 15:07:59']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'35','bloc_id'=>'38','store_id'=>'138','member_id'=>'278','hotel_id'=>'40','room_id'=>'0','num'=>'1','create_time'=>'2023-06-16 13:33:20','update_time'=>'2023-06-16 13:33:20']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'36','bloc_id'=>'38','store_id'=>'138','member_id'=>'1','hotel_id'=>'30','room_id'=>'0','num'=>'4','create_time'=>'2023-06-19 13:37:12','update_time'=>'2023-06-19 15:18:48']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'37','bloc_id'=>'38','store_id'=>'138','member_id'=>'1','hotel_id'=>'19','room_id'=>'0','num'=>'7','create_time'=>'2023-06-19 13:37:43','update_time'=>'2023-06-19 13:58:27']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'38','bloc_id'=>'38','store_id'=>'138','member_id'=>'1','hotel_id'=>'41','room_id'=>'0','num'=>'4','create_time'=>'2023-06-19 15:08:02','update_time'=>'2023-06-19 15:13:32']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'39','bloc_id'=>'38','store_id'=>'138','member_id'=>'1','hotel_id'=>'40','room_id'=>'0','num'=>'1','create_time'=>'2023-06-19 15:09:44','update_time'=>'2023-06-19 15:09:44']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'40','bloc_id'=>'38','store_id'=>'138','member_id'=>'270','hotel_id'=>'19','room_id'=>'0','num'=>'1','create_time'=>'2023-06-20 09:02:13','update_time'=>'2023-06-20 09:02:13']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'41','bloc_id'=>'38','store_id'=>'138','member_id'=>'270','hotel_id'=>'1','room_id'=>'0','num'=>'5','create_time'=>'2023-06-20 09:12:45','update_time'=>'2023-06-20 09:25:30']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'42','bloc_id'=>'91','store_id'=>'153','member_id'=>'1','hotel_id'=>'69','room_id'=>'0','num'=>'222','create_time'=>'2023-06-20 12:34:11','update_time'=>'2023-10-16 21:54:03']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'43','bloc_id'=>'91','store_id'=>'153','member_id'=>'278','hotel_id'=>'69','room_id'=>'0','num'=>'141','create_time'=>'2023-06-20 12:54:39','update_time'=>'2023-07-03 11:27:02']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'44','bloc_id'=>'91','store_id'=>'153','member_id'=>'270','hotel_id'=>'69','room_id'=>'0','num'=>'11','create_time'=>'2023-06-20 14:26:44','update_time'=>'2023-06-29 15:58:38']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'45','bloc_id'=>'91','store_id'=>'153','member_id'=>'1','hotel_id'=>'71','room_id'=>'0','num'=>'16','create_time'=>'2023-06-21 10:10:01','update_time'=>'2023-10-12 21:46:32']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'46','bloc_id'=>'91','store_id'=>'153','member_id'=>'278','hotel_id'=>'71','room_id'=>'0','num'=>'5','create_time'=>'2023-06-25 09:17:50','update_time'=>'2023-06-28 14:46:59']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'47','bloc_id'=>'91','store_id'=>'153','member_id'=>'278','hotel_id'=>'80','room_id'=>'0','num'=>'3','create_time'=>'2023-06-25 09:20:26','update_time'=>'2023-06-25 17:31:30']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'48','bloc_id'=>'91','store_id'=>'153','member_id'=>'278','hotel_id'=>'74','room_id'=>'0','num'=>'1','create_time'=>'2023-06-25 09:20:39','update_time'=>'2023-06-25 09:20:39']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'49','bloc_id'=>'91','store_id'=>'153','member_id'=>'278','hotel_id'=>'76','room_id'=>'0','num'=>'1','create_time'=>'2023-06-25 09:20:41','update_time'=>'2023-06-25 09:20:41']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'50','bloc_id'=>'91','store_id'=>'153','member_id'=>'278','hotel_id'=>'77','room_id'=>'0','num'=>'2','create_time'=>'2023-06-25 09:20:44','update_time'=>'2023-06-26 09:26:27']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'51','bloc_id'=>'91','store_id'=>'153','member_id'=>'278','hotel_id'=>'79','room_id'=>'0','num'=>'1','create_time'=>'2023-06-25 09:20:46','update_time'=>'2023-06-25 09:20:46']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'52','bloc_id'=>'91','store_id'=>'153','member_id'=>'1','hotel_id'=>'80','room_id'=>'0','num'=>'1','create_time'=>'2023-06-25 16:26:26','update_time'=>'2023-06-25 16:26:26']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'53','bloc_id'=>'38','store_id'=>'51','member_id'=>'278','hotel_id'=>'69','room_id'=>'0','num'=>'9','create_time'=>'2023-06-25 16:43:21','update_time'=>'2023-06-28 10:32:34']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'54','bloc_id'=>'91','store_id'=>'153','member_id'=>'1','hotel_id'=>'81','room_id'=>'0','num'=>'23','create_time'=>'2023-06-25 17:07:01','update_time'=>'2023-10-16 02:25:18']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'55','bloc_id'=>'91','store_id'=>'153','member_id'=>'278','hotel_id'=>'81','room_id'=>'0','num'=>'16','create_time'=>'2023-06-25 18:09:34','update_time'=>'2023-06-29 10:08:42']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'56','bloc_id'=>'91','store_id'=>'153','member_id'=>'278','hotel_id'=>'88','room_id'=>'0','num'=>'2','create_time'=>'2023-06-27 09:40:20','update_time'=>'2023-06-27 10:16:34']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'57','bloc_id'=>'91','store_id'=>'153','member_id'=>'278','hotel_id'=>'87','room_id'=>'0','num'=>'1','create_time'=>'2023-06-27 09:40:27','update_time'=>'2023-06-27 09:40:27']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'58','bloc_id'=>'91','store_id'=>'153','member_id'=>'278','hotel_id'=>'86','room_id'=>'0','num'=>'1','create_time'=>'2023-06-27 09:40:32','update_time'=>'2023-06-27 09:40:32']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'59','bloc_id'=>'91','store_id'=>'153','member_id'=>'278','hotel_id'=>'89','room_id'=>'0','num'=>'1','create_time'=>'2023-06-27 09:45:00','update_time'=>'2023-06-27 09:45:00']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'60','bloc_id'=>'91','store_id'=>'153','member_id'=>'278','hotel_id'=>'92','room_id'=>'0','num'=>'4','create_time'=>'2023-06-27 10:16:29','update_time'=>'2023-06-28 15:00:49']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'61','bloc_id'=>'91','store_id'=>'153','member_id'=>'278','hotel_id'=>'97','room_id'=>'0','num'=>'2','create_time'=>'2023-06-27 13:30:55','update_time'=>'2023-06-27 13:38:02']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'62','bloc_id'=>'38','store_id'=>'51','member_id'=>'270','hotel_id'=>'153','room_id'=>'0','num'=>'1','create_time'=>'2023-06-27 17:24:35','update_time'=>'2023-06-27 17:24:35']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'63','bloc_id'=>'91','store_id'=>'153','member_id'=>'293','hotel_id'=>'69','room_id'=>'0','num'=>'13','create_time'=>'2023-06-28 09:10:20','update_time'=>'2023-07-03 11:34:22']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'64','bloc_id'=>'91','store_id'=>'153','member_id'=>'293','hotel_id'=>'102','room_id'=>'0','num'=>'8','create_time'=>'2023-06-28 09:14:10','update_time'=>'2023-07-03 11:34:46']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'65','bloc_id'=>'91','store_id'=>'153','member_id'=>'293','hotel_id'=>'71','room_id'=>'0','num'=>'2','create_time'=>'2023-06-28 09:36:25','update_time'=>'2023-07-03 15:37:25']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'66','bloc_id'=>'91','store_id'=>'153','member_id'=>'1','hotel_id'=>'102','room_id'=>'0','num'=>'14','create_time'=>'2023-06-28 09:48:04','update_time'=>'2023-07-11 09:28:10']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'67','bloc_id'=>'91','store_id'=>'153','member_id'=>'1','hotel_id'=>'98','room_id'=>'0','num'=>'2','create_time'=>'2023-06-28 10:01:09','update_time'=>'2023-06-28 10:11:06']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'68','bloc_id'=>'38','store_id'=>'51','member_id'=>'278','hotel_id'=>'153','room_id'=>'0','num'=>'6','create_time'=>'2023-06-28 10:22:54','update_time'=>'2023-06-28 10:32:23']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'69','bloc_id'=>'91','store_id'=>'153','member_id'=>'278','hotel_id'=>'0','room_id'=>'0','num'=>'5','create_time'=>'2023-06-28 11:43:06','update_time'=>'2023-06-28 11:45:41']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'70','bloc_id'=>'91','store_id'=>'153','member_id'=>'293','hotel_id'=>'81','room_id'=>'0','num'=>'1','create_time'=>'2023-06-28 14:30:24','update_time'=>'2023-06-28 14:30:24']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'71','bloc_id'=>'91','store_id'=>'153','member_id'=>'293','hotel_id'=>'92','room_id'=>'0','num'=>'11','create_time'=>'2023-06-28 14:34:25','update_time'=>'2023-06-28 15:13:05']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'72','bloc_id'=>'91','store_id'=>'153','member_id'=>'278','hotel_id'=>'99','room_id'=>'0','num'=>'4','create_time'=>'2023-06-28 14:58:26','update_time'=>'2023-06-28 15:00:44']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'73','bloc_id'=>'91','store_id'=>'153','member_id'=>'278','hotel_id'=>'84','room_id'=>'0','num'=>'1','create_time'=>'2023-06-28 15:00:57','update_time'=>'2023-06-28 15:00:57']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'74','bloc_id'=>'91','store_id'=>'153','member_id'=>'293','hotel_id'=>'103','room_id'=>'0','num'=>'13','create_time'=>'2023-06-28 15:04:11','update_time'=>'2023-07-03 15:37:46']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'75','bloc_id'=>'91','store_id'=>'153','member_id'=>'278','hotel_id'=>'103','room_id'=>'0','num'=>'9','create_time'=>'2023-06-28 17:39:52','update_time'=>'2023-06-30 16:22:55']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'76','bloc_id'=>'91','store_id'=>'153','member_id'=>'270','hotel_id'=>'103','room_id'=>'0','num'=>'6','create_time'=>'2023-06-29 15:59:10','update_time'=>'2023-06-30 17:21:20']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'77','bloc_id'=>'91','store_id'=>'153','member_id'=>'1','hotel_id'=>'99','room_id'=>'0','num'=>'5','create_time'=>'2023-06-30 14:23:51','update_time'=>'2023-10-15 00:08:13']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'78','bloc_id'=>'91','store_id'=>'153','member_id'=>'1','hotel_id'=>'103','room_id'=>'0','num'=>'1','create_time'=>'2023-06-30 14:48:31','update_time'=>'2023-06-30 14:48:31']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'79','bloc_id'=>'91','store_id'=>'153','member_id'=>'306','hotel_id'=>'69','room_id'=>'0','num'=>'3','create_time'=>'2023-07-03 15:45:07','update_time'=>'2023-07-07 15:31:12']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'80','bloc_id'=>'91','store_id'=>'153','member_id'=>'306','hotel_id'=>'71','room_id'=>'0','num'=>'1','create_time'=>'2023-07-07 15:31:18','update_time'=>'2023-07-07 15:31:18']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'81','bloc_id'=>'91','store_id'=>'153','member_id'=>'306','hotel_id'=>'81','room_id'=>'0','num'=>'4','create_time'=>'2023-07-07 15:31:27','update_time'=>'2023-07-10 15:58:17']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'82','bloc_id'=>'91','store_id'=>'153','member_id'=>'306','hotel_id'=>'103','room_id'=>'0','num'=>'2','create_time'=>'2023-07-07 15:31:48','update_time'=>'2023-07-10 15:33:04']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'83','bloc_id'=>'91','store_id'=>'153','member_id'=>'309','hotel_id'=>'84','room_id'=>'0','num'=>'1','create_time'=>'2023-07-09 14:09:44','update_time'=>'2023-07-09 14:09:44']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'84','bloc_id'=>'91','store_id'=>'153','member_id'=>'309','hotel_id'=>'69','room_id'=>'0','num'=>'2','create_time'=>'2023-07-09 15:06:59','update_time'=>'2023-07-11 16:46:21']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'85','bloc_id'=>'91','store_id'=>'153','member_id'=>'306','hotel_id'=>'102','room_id'=>'0','num'=>'3','create_time'=>'2023-07-10 15:33:33','update_time'=>'2023-07-10 15:38:51']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'86','bloc_id'=>'91','store_id'=>'153','member_id'=>'306','hotel_id'=>'84','room_id'=>'0','num'=>'1','create_time'=>'2023-07-10 15:33:41','update_time'=>'2023-07-10 15:33:41']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'87','bloc_id'=>'91','store_id'=>'153','member_id'=>'309','hotel_id'=>'98','room_id'=>'0','num'=>'2','create_time'=>'2023-07-10 15:34:05','update_time'=>'2023-07-11 15:18:25']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'88','bloc_id'=>'91','store_id'=>'153','member_id'=>'306','hotel_id'=>'98','room_id'=>'0','num'=>'1','create_time'=>'2023-07-10 15:34:16','update_time'=>'2023-07-10 15:34:16']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'89','bloc_id'=>'91','store_id'=>'153','member_id'=>'306','hotel_id'=>'99','room_id'=>'0','num'=>'2','create_time'=>'2023-07-10 15:36:04','update_time'=>'2023-07-10 15:38:54']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'90','bloc_id'=>'91','store_id'=>'153','member_id'=>'309','hotel_id'=>'71','room_id'=>'0','num'=>'1','create_time'=>'2023-07-11 16:44:36','update_time'=>'2023-07-11 16:44:36']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'91','bloc_id'=>'91','store_id'=>'153','member_id'=>'133','hotel_id'=>'69','room_id'=>'0','num'=>'1','create_time'=>'2023-07-11 17:16:35','update_time'=>'2023-07-11 17:16:35']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'92','bloc_id'=>'91','store_id'=>'153','member_id'=>'310','hotel_id'=>'103','room_id'=>'0','num'=>'2','create_time'=>'2023-07-11 17:33:04','update_time'=>'2023-07-11 17:39:17']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'93','bloc_id'=>'91','store_id'=>'153','member_id'=>'310','hotel_id'=>'69','room_id'=>'0','num'=>'2','create_time'=>'2023-07-11 17:55:20','update_time'=>'2023-07-11 17:55:27']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'94','bloc_id'=>'91','store_id'=>'153','member_id'=>'314','hotel_id'=>'69','room_id'=>'0','num'=>'25','create_time'=>'2023-07-21 14:12:25','update_time'=>'2023-10-27 17:32:37']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'95','bloc_id'=>'91','store_id'=>'153','member_id'=>'315','hotel_id'=>'69','room_id'=>'0','num'=>'1','create_time'=>'2023-07-21 14:13:31','update_time'=>'2023-07-21 14:13:31']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'96','bloc_id'=>'91','store_id'=>'153','member_id'=>'317','hotel_id'=>'69','room_id'=>'0','num'=>'1','create_time'=>'2023-07-24 09:53:03','update_time'=>'2023-07-24 09:53:03']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'97','bloc_id'=>'91','store_id'=>'153','member_id'=>'318','hotel_id'=>'69','room_id'=>'0','num'=>'1','create_time'=>'2023-07-24 10:09:31','update_time'=>'2023-07-24 10:09:31']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'98','bloc_id'=>'91','store_id'=>'153','member_id'=>'319','hotel_id'=>'103','room_id'=>'0','num'=>'1','create_time'=>'2023-07-25 08:27:56','update_time'=>'2023-07-25 08:27:56']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'99','bloc_id'=>'91','store_id'=>'153','member_id'=>'320','hotel_id'=>'69','room_id'=>'0','num'=>'1','create_time'=>'2023-07-25 08:55:40','update_time'=>'2023-07-25 08:55:40']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'100','bloc_id'=>'91','store_id'=>'153','member_id'=>'305','hotel_id'=>'69','room_id'=>'0','num'=>'93','create_time'=>'2023-07-25 22:16:41','update_time'=>'2023-10-28 21:51:31']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'101','bloc_id'=>'91','store_id'=>'153','member_id'=>'305','hotel_id'=>'71','room_id'=>'0','num'=>'7','create_time'=>'2023-07-25 22:18:53','update_time'=>'2023-10-15 23:53:19']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'102','bloc_id'=>'91','store_id'=>'153','member_id'=>'305','hotel_id'=>'103','room_id'=>'0','num'=>'3','create_time'=>'2023-07-25 22:35:12','update_time'=>'2023-10-08 00:10:54']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'103','bloc_id'=>'91','store_id'=>'153','member_id'=>'322','hotel_id'=>'69','room_id'=>'0','num'=>'1','create_time'=>'2023-07-26 14:47:43','update_time'=>'2023-07-26 14:47:43']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'104','bloc_id'=>'91','store_id'=>'153','member_id'=>'314','hotel_id'=>'81','room_id'=>'0','num'=>'2','create_time'=>'2023-07-28 15:58:38','update_time'=>'2023-08-17 13:22:13']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'105','bloc_id'=>'91','store_id'=>'153','member_id'=>'314','hotel_id'=>'71','room_id'=>'0','num'=>'4','create_time'=>'2023-08-21 19:04:01','update_time'=>'2023-10-16 03:36:03']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'106','bloc_id'=>'91','store_id'=>'153','member_id'=>'324','hotel_id'=>'99','room_id'=>'0','num'=>'1','create_time'=>'2023-08-22 16:08:53','update_time'=>'2023-08-22 16:08:53']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'107','bloc_id'=>'91','store_id'=>'153','member_id'=>'314','hotel_id'=>'102','room_id'=>'0','num'=>'1','create_time'=>'2023-08-22 17:59:51','update_time'=>'2023-08-22 17:59:51']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'108','bloc_id'=>'91','store_id'=>'153','member_id'=>'329','hotel_id'=>'69','room_id'=>'0','num'=>'1','create_time'=>'2023-08-26 10:06:32','update_time'=>'2023-08-26 10:06:32']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'109','bloc_id'=>'91','store_id'=>'153','member_id'=>'330','hotel_id'=>'69','room_id'=>'0','num'=>'1','create_time'=>'2023-08-29 13:15:32','update_time'=>'2023-08-29 13:15:32']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'110','bloc_id'=>'91','store_id'=>'153','member_id'=>'305','hotel_id'=>'81','room_id'=>'0','num'=>'39','create_time'=>'2023-09-01 10:05:41','update_time'=>'2023-10-28 12:16:30']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'111','bloc_id'=>'91','store_id'=>'153','member_id'=>'314','hotel_id'=>'99','room_id'=>'0','num'=>'7','create_time'=>'2023-10-07 23:06:47','update_time'=>'2023-10-16 03:38:41']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'112','bloc_id'=>'91','store_id'=>'153','member_id'=>'305','hotel_id'=>'99','room_id'=>'0','num'=>'3','create_time'=>'2023-10-07 23:44:28','update_time'=>'2023-10-28 12:16:12']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'113','bloc_id'=>'91','store_id'=>'153','member_id'=>'459','hotel_id'=>'103','room_id'=>'0','num'=>'1','create_time'=>'2023-10-08 15:43:50','update_time'=>'2023-10-08 15:43:50']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'114','bloc_id'=>'91','store_id'=>'153','member_id'=>'464','hotel_id'=>'102','room_id'=>'0','num'=>'1','create_time'=>'2023-10-15 01:23:13','update_time'=>'2023-10-15 01:23:13']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'115','bloc_id'=>'91','store_id'=>'153','member_id'=>'466','hotel_id'=>'102','room_id'=>'0','num'=>'1','create_time'=>'2023-10-15 01:32:03','update_time'=>'2023-10-15 01:32:03']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'116','bloc_id'=>'91','store_id'=>'153','member_id'=>'467','hotel_id'=>'102','room_id'=>'0','num'=>'1','create_time'=>'2023-10-15 10:35:39','update_time'=>'2023-10-15 10:35:39']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'117','bloc_id'=>'91','store_id'=>'153','member_id'=>'469','hotel_id'=>'99','room_id'=>'0','num'=>'2','create_time'=>'2023-10-15 14:05:15','update_time'=>'2023-10-16 09:23:33']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'118','bloc_id'=>'91','store_id'=>'153','member_id'=>'470','hotel_id'=>'103','room_id'=>'0','num'=>'1','create_time'=>'2023-10-15 17:22:59','update_time'=>'2023-10-15 17:22:59']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'119','bloc_id'=>'91','store_id'=>'153','member_id'=>'471','hotel_id'=>'99','room_id'=>'0','num'=>'1','create_time'=>'2023-10-15 17:36:20','update_time'=>'2023-10-15 17:36:20']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'120','bloc_id'=>'91','store_id'=>'153','member_id'=>'469','hotel_id'=>'69','room_id'=>'0','num'=>'1','create_time'=>'2023-10-16 09:23:12','update_time'=>'2023-10-16 09:23:12']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'121','bloc_id'=>'91','store_id'=>'153','member_id'=>'469','hotel_id'=>'81','room_id'=>'0','num'=>'1','create_time'=>'2023-10-16 09:23:30','update_time'=>'2023-10-16 09:23:30']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'122','bloc_id'=>'91','store_id'=>'153','member_id'=>'473','hotel_id'=>'102','room_id'=>'0','num'=>'1','create_time'=>'2023-10-16 14:57:19','update_time'=>'2023-10-16 14:57:19']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'123','bloc_id'=>'91','store_id'=>'153','member_id'=>'477','hotel_id'=>'69','room_id'=>'0','num'=>'1','create_time'=>'2023-10-16 17:14:26','update_time'=>'2023-10-16 17:14:26']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'124','bloc_id'=>'91','store_id'=>'153','member_id'=>'478','hotel_id'=>'81','room_id'=>'0','num'=>'1','create_time'=>'2023-10-16 22:41:13','update_time'=>'2023-10-16 22:41:13']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'125','bloc_id'=>'91','store_id'=>'153','member_id'=>'482','hotel_id'=>'103','room_id'=>'0','num'=>'1','create_time'=>'2023-10-20 13:01:12','update_time'=>'2023-10-20 13:01:12']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'126','bloc_id'=>'91','store_id'=>'153','member_id'=>'484','hotel_id'=>'69','room_id'=>'0','num'=>'1','create_time'=>'2023-10-21 00:00:19','update_time'=>'2023-10-21 00:00:19']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'127','bloc_id'=>'91','store_id'=>'153','member_id'=>'491','hotel_id'=>'69','room_id'=>'0','num'=>'1','create_time'=>'2023-10-25 11:31:23','update_time'=>'2023-10-25 11:31:23']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'128','bloc_id'=>'91','store_id'=>'153','member_id'=>'496','hotel_id'=>'69','room_id'=>'0','num'=>'1','create_time'=>'2023-10-28 15:07:29','update_time'=>'2023-10-28 15:07:29']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'129','bloc_id'=>'91','store_id'=>'153','member_id'=>'501','hotel_id'=>'102','room_id'=>'0','num'=>'1','create_time'=>'2023-10-31 20:35:05','update_time'=>'2023-10-31 20:35:05']);
        $this->insert('{{%diandi_place_member_view}}',['id'=>'130','bloc_id'=>'91','store_id'=>'153','member_id'=>'501','hotel_id'=>'71','room_id'=>'0','num'=>'1','create_time'=>'2023-10-31 20:35:50','update_time'=>'2023-10-31 20:35:50']);
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_member_view}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
