<?php

use yii\db\Migration;

class m240417_014729_diandi_place_brand extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_place_brand}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL DEFAULT '0' COMMENT '公司ID'",
            'title' => "varchar(255) NULL DEFAULT '' COMMENT '品牌名称'",
            'displayorder' => "int(11) NULL DEFAULT '0' COMMENT '排序'",
            'status' => "int(11) NULL DEFAULT '0' COMMENT '状态1启用0未启用'",
            'create_time' => "datetime NULL",
            'update_time' => "datetime NULL",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='酒店品牌'");
        
        /* 索引设置 */
        $this->createIndex('indx_displayorder','{{%diandi_place_brand}}','displayorder',0);
        
        
        /* 表数据 */
        $this->insert('{{%diandi_place_brand}}',['id'=>'1','bloc_id'=>'38','title'=>'店滴云','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'2','bloc_id'=>'91','title'=>'马来西亚1','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'3','bloc_id'=>'92','title'=>'无人茶室','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'4','bloc_id'=>'93','title'=>'昆仑茶室','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'5','bloc_id'=>'95','title'=>'乐享棋牌室','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'6','bloc_id'=>'98','title'=>'南京馨悦芸网络科技','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'7','bloc_id'=>'103','title'=>'方办','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'8','bloc_id'=>'104','title'=>'您的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'9','bloc_id'=>'105','title'=>'您的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'10','bloc_id'=>'106','title'=>'河北1','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'11','bloc_id'=>'107','title'=>'Andata','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-02-16 10:45:32']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'12','bloc_id'=>'108','title'=>'li公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'13','bloc_id'=>'109','title'=>'liyue的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'14','bloc_id'=>'110','title'=>'liyue的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'15','bloc_id'=>'111','title'=>'liyue的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'16','bloc_id'=>'112','title'=>'18090001304的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'17','bloc_id'=>'113','title'=>'eupos的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'18','bloc_id'=>'114','title'=>'店滴云茶室','displayorder'=>'12','status'=>'1','create_time'=>'2024-02-19 09:57:51','update_time'=>'2024-02-19 09:57:51']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'19','bloc_id'=>'115','title'=>'甘肃点诺科技有限公司','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-15 17:24:58']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'20','bloc_id'=>'116','title'=>'15029073086的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'21','bloc_id'=>'117','title'=>'dotnov','displayorder'=>'1','status'=>'1','create_time'=>'2024-02-19 18:59:48','update_time'=>'2024-02-19 18:59:48']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'22','bloc_id'=>'118','title'=>'hykj的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'23','bloc_id'=>'119','title'=>'dwolfs的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'24','bloc_id'=>'120','title'=>'gz-1688的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'25','bloc_id'=>'121','title'=>'13739225713的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'26','bloc_id'=>'122','title'=>'xuke4758的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'27','bloc_id'=>'123','title'=>'adminjy的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'28','bloc_id'=>'124','title'=>'不想起名的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'29','bloc_id'=>'125','title'=>'111的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'30','bloc_id'=>'126','title'=>'wangzhen8888的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'31','bloc_id'=>'127','title'=>'time的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'32','bloc_id'=>'128','title'=>'18075571460的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'33','bloc_id'=>'129','title'=>'diditest的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'34','bloc_id'=>'130','title'=>'菩提鱼的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'35','bloc_id'=>'131','title'=>'13432066565的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'36','bloc_id'=>'132','title'=>'18334744888的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'37','bloc_id'=>'133','title'=>'liuachong的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'38','bloc_id'=>'134','title'=>'longshao的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'39','bloc_id'=>'135','title'=>'一点钟方向的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'40','bloc_id'=>'136','title'=>'王生的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'41','bloc_id'=>'137','title'=>'lsh的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'42','bloc_id'=>'138','title'=>'cgk的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'43','bloc_id'=>'139','title'=>'piaoyi0960的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'44','bloc_id'=>'140','title'=>'17898189368的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'45','bloc_id'=>'141','title'=>'途观科技','displayorder'=>'1','status'=>'1','create_time'=>'2024-03-04 16:04:46','update_time'=>'2024-03-04 16:04:46']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'46','bloc_id'=>'142','title'=>'水中焉的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'47','bloc_id'=>'143','title'=>'842767732的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'48','bloc_id'=>'144','title'=>'gs089597的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'49','bloc_id'=>'145','title'=>'18811788428的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'50','bloc_id'=>'146','title'=>'tanxiansheng的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'51','bloc_id'=>'147','title'=>'275489431的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'52','bloc_id'=>'148','title'=>'159的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'53','bloc_id'=>'149','title'=>'161的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'54','bloc_id'=>'150','title'=>'tom123的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'55','bloc_id'=>'151','title'=>'17365984999的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'56','bloc_id'=>'152','title'=>'164的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'57','bloc_id'=>'153','title'=>'165的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'58','bloc_id'=>'154','title'=>'166的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'59','bloc_id'=>'155','title'=>'167的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'60','bloc_id'=>'156','title'=>'168的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'61','bloc_id'=>'157','title'=>'169的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'62','bloc_id'=>'158','title'=>'170的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'63','bloc_id'=>'159','title'=>'171的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'64','bloc_id'=>'160','title'=>'172的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'65','bloc_id'=>'161','title'=>'173的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'66','bloc_id'=>'162','title'=>'174的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'67','bloc_id'=>'163','title'=>'175的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'68','bloc_id'=>'164','title'=>'xuanyanmeng的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'69','bloc_id'=>'165','title'=>'177的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'70','bloc_id'=>'166','title'=>'bxsdhx的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'71','bloc_id'=>'167','title'=>'179的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'72','bloc_id'=>'168','title'=>'adminj的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'73','bloc_id'=>'169','title'=>'百通达','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'74','bloc_id'=>'170','title'=>'182的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'75','bloc_id'=>'171','title'=>'183的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'76','bloc_id'=>'172','title'=>'184的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'77','bloc_id'=>'173','title'=>'185的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'78','bloc_id'=>'174','title'=>'186的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'79','bloc_id'=>'175','title'=>'187的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'80','bloc_id'=>'176','title'=>'chennan的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'81','bloc_id'=>'177','title'=>'nongye的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'82','bloc_id'=>'178','title'=>'qq8191150的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'83','bloc_id'=>'179','title'=>'191的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'84','bloc_id'=>'180','title'=>'192的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'85','bloc_id'=>'181','title'=>'992591723@qq.com的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'86','bloc_id'=>'182','title'=>'194的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'87','bloc_id'=>'183','title'=>'195的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'88','bloc_id'=>'184','title'=>'196的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'89','bloc_id'=>'185','title'=>'197的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'90','bloc_id'=>'186','title'=>'198的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'91','bloc_id'=>'187','title'=>'199的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'92','bloc_id'=>'188','title'=>'200的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'93','bloc_id'=>'189','title'=>'zxgsg520的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'94','bloc_id'=>'190','title'=>'202的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'95','bloc_id'=>'191','title'=>'15889781738的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'96','bloc_id'=>'192','title'=>'204的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'97','bloc_id'=>'193','title'=>'sunxianjin的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'98','bloc_id'=>'194','title'=>'123的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'99','bloc_id'=>'195','title'=>'mj的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'100','bloc_id'=>'196','title'=>'240的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'101','bloc_id'=>'197','title'=>'241的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'102','bloc_id'=>'198','title'=>'242的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'103','bloc_id'=>'199','title'=>'newman的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'104','bloc_id'=>'200','title'=>'244的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'105','bloc_id'=>'201','title'=>'jiuzhou的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'106','bloc_id'=>'202','title'=>'18611184253的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'107','bloc_id'=>'203','title'=>'xmflyingfish的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'108','bloc_id'=>'204','title'=>'chenml的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        $this->insert('{{%diandi_place_brand}}',['id'=>'109','bloc_id'=>'205','title'=>'dmtzhb的公司名称','displayorder'=>'0','status'=>'1','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_brand}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

