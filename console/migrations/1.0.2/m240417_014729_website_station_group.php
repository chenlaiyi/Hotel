<?php

use yii\db\Migration;

class m240417_014729_website_station_group extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%website_station_group}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'flogo' => "varchar(255) NULL COMMENT '前台logo'",
            'blogo' => "varchar(255) NULL COMMENT '后台logo'",
            'domain_url' => "varchar(100) NULL COMMENT '域名'",
            'name' => "varchar(100) NULL COMMENT '站点名称'",
            'intro' => "varchar(255) NULL COMMENT '站点介绍'",
            'loginbg' => "varchar(255) NULL",
            'keywords' => "varchar(255) NULL COMMENT '站点检索词'",
            'description' => "varchar(255) NULL COMMENT '站点描述'",
            'footerleft' => "varchar(255) NULL COMMENT '底部左侧'",
            'footerright' => "varchar(255) NULL COMMENT '底部右侧'",
            'location' => "varchar(255) NULL",
            'icp' => "varchar(255) NULL COMMENT '备案信息'",
            'create_time' => "datetime NULL",
            'update_time' => "datetime NULL",
            'mobile' => "varchar(20) NULL COMMENT '联系电话'",
            'city' => "varchar(10) NULL COMMENT '所在城市'",
            'company_name' => "varchar(100) NULL COMMENT '公司名称'",
            'wechat' => "varchar(50) NULL COMMENT '微信号'",
            'status' => "int(11) NULL DEFAULT '0' COMMENT '0申请，1付款，2已部署'",
            'bloc_id' => "int(11) NULL",
            'store_id' => "int(11) NULL",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%website_station_group}}',['id'=>'2','flogo'=>'202401/28/6050f452-1eb2-3651-8309-604c88873dee.png','blogo'=>'202401/28/20edfded-39d3-3ac1-8a93-1c7e85364f1a.png','domain_url'=>'www.p5g.net','name'=>'微畅物业系统','intro'=>'河北微畅网络科技有限公司','loginbg'=>'202401/28/4ba4075d-c414-35b7-be2a-afc66d329bb7.jpg','keywords'=>'河北微畅网络科技有限公司','description'=>'河北微畅网络科技有限公司','footerleft'=>'微畅物业系统','footerright'=>'微畅物业系统','location'=>'','icp'=>'冀ICP备16019291号-5','create_time'=>'2024-01-28 22:11:00','update_time'=>'2024-04-09 06:28:01','mobile'=>'1','city'=>'河北','company_name'=>'河北微畅网络科技有限公司','wechat'=>'qq512826','status'=>'1','bloc_id'=>'97','store_id'=>'291']);
        $this->insert('{{%website_station_group}}',['id'=>'3','flogo'=>'202401/28/7f142e2b-5f86-3919-9f66-4c08ecca0235.png','blogo'=>'202401/28/083525ea-4b39-38da-9074-e29a81023f32.png','domain_url'=>'www.770504.com','name'=>'南京馨悦芸智慧公寓','intro'=>'南京馨悦芸网络科技','loginbg'=>'202401/28/3dfaf478-b521-3c6b-b72f-2bf394d7f54e.jpg','keywords'=>'南京馨悦芸网络科技','description'=>'南京馨悦芸网络科技','footerleft'=>'1','footerright'=>'2','location'=>'1','icp'=>'南京馨悦芸网络科技','create_time'=>'2024-01-28 22:49:02','update_time'=>'2024-01-29 00:31:30','mobile'=>'1','city'=>'南京','company_name'=>'南京馨悦芸网络科技','wechat'=>'nanjingjyw','status'=>'1','bloc_id'=>'98','store_id'=>'292']);
        $this->insert('{{%website_station_group}}',['id'=>'4','flogo'=>'202402/19/6b113127-d65e-3d99-8e8d-68178bda49aa.png','blogo'=>'202402/19/2428be86-973d-384c-b948-6b61a25c7190.png','domain_url'=>'store.dotnov.com','name'=>'点诺云店','intro'=>'智能茶楼、棋牌室、自习室、酒店、民宿服务提供商','loginbg'=>'202402/19/5daa9838-8155-3ee4-918e-e8305533ef19.jpg','keywords'=>'点诺','description'=>'智能茶楼、棋牌室、自习室、酒店、民宿服务提供商','footerleft'=>'点诺','footerright'=>'点诺','location'=>'1','icp'=>'1','create_time'=>'2024-02-19 19:50:34','update_time'=>'2024-03-04 15:40:10','mobile'=>'17778984690','city'=>'甘肃','company_name'=>'甘肃点诺科技有限公司','wechat'=>'guyin812','status'=>'1','bloc_id'=>'115','store_id'=>'304']);
        $this->insert('{{%website_station_group}}',['id'=>'5','flogo'=>'202402/19/768dde54-b00d-3e10-b548-c7541b204ca3.png','blogo'=>'202402/19/e3b9d673-3027-343d-8426-5e614c886c15.png','domain_url'=>'jd.hy-it.top','name'=>'荟映科技','intro'=>'荟映科技','loginbg'=>'202402/19/ab2ec68a-df39-3262-94a3-e27216802ed2.jpg','keywords'=>'荟映科技','description'=>'荟映科技','footerleft'=>'荟映科技','footerright'=>'荟映科技','location'=>'1','icp'=>'1','create_time'=>'2024-02-19 21:09:06','update_time'=>'2024-02-19 21:09:06','mobile'=>'17778984690','city'=>'1','company_name'=>'荟映科技','wechat'=>'wxid_oumgn3chqcut22','status'=>'0','bloc_id'=>'118','store_id'=>'305']);
        $this->insert('{{%website_station_group}}',['id'=>'6','flogo'=>'202403/04/f61f2424-2d6d-32a0-aa61-e70fc7596c62.png','blogo'=>'202403/04/a019da14-428d-37bc-97b0-cb7127eaff7c.png','domain_url'=>'tg.ddicms.cn','name'=>'途观科技','intro'=>'智能茶楼、棋牌室、自习室、酒店、民宿服务提供商','loginbg'=>'202403/04/684797af-43de-348f-b5ef-ffc0fcb616d2.jpg','keywords'=>'途观','description'=>'智能茶楼、棋牌室、自习室、酒店、民宿服务提供商','footerleft'=>'途观','footerright'=>'途观','location'=>'途观','icp'=>'2','create_time'=>'2024-03-04 16:19:12','update_time'=>'2024-03-04 20:23:32','mobile'=>'17778984690','city'=>'1','company_name'=>'西安店滴云网络科技有限公司','wechat'=>'cmzsch1112','status'=>'1','bloc_id'=>'141','store_id'=>'307']);
        $this->insert('{{%website_station_group}}',['id'=>'7','flogo'=>'202403/14/33921bbc-25b4-3772-a6d0-b793d301150f.png','blogo'=>'202403/14/24235dcf-2756-34e6-ac4b-f36f967d9714.png','domain_url'=>'yunzn.btyit.com','name'=>'四川柏态云科技有限公司','intro'=>'四川柏态云科技有限公司','loginbg'=>'202403/14/4435b243-a9ef-3cb5-96d8-fa2a39600ebf.jpg','keywords'=>'四川柏态云科技有限公司','description'=>'四川柏态云科技有限公司','footerleft'=>'四川柏态云科技有限公司','footerright'=>'四川柏态云科技有限公司','location'=>'1','icp'=>'1','create_time'=>'2024-03-14 10:23:51','update_time'=>'2024-03-14 10:27:37','mobile'=>'17778984690','city'=>'1','company_name'=>'四川柏态云科技有限公司','wechat'=>'j1380800125','status'=>'1','bloc_id'=>'159','store_id'=>'308']);
        $this->insert('{{%website_station_group}}',['id'=>'8','flogo'=>'202403/19/655c4281-4f32-3c6c-868b-87634e4993c4.png','blogo'=>'202403/19/49109da3-ec07-3230-9626-dc80574d8484.png','domain_url'=>'www.dandicloud.cn','name'=>'店滴云','intro'=>'店滴云','loginbg'=>'202403/19/27ec673d-4c16-3c95-a9e8-8ed54e9313d2.jpg','keywords'=>'店滴云','description'=>'店滴云','footerleft'=>'店滴云','footerright'=>'店滴云','location'=>'店滴云','icp'=>'店滴云','create_time'=>'2024-03-19 15:49:50','update_time'=>'2024-03-19 15:49:50','mobile'=>'17778984690','city'=>'西安','company_name'=>'西安店滴云网络科技有限公司','wechat'=>'diandcloud','status'=>'1','bloc_id'=>'38','store_id'=>'175']);
        $this->insert('{{%website_station_group}}',['id'=>'9','flogo'=>'202403/30/8f6412f8-fb56-3133-9ca9-ba549f0ad29a.jpg','blogo'=>'202403/30/5fb78062-cb4a-388a-8339-3750f854da4e.jpg','domain_url'=>'yxd.hy-it.top','name'=>'上云了吗','intro'=>'上云了吗','loginbg'=>'202403/30/c5aea6e5-10b2-32a8-a5c6-5f2e518e37dc.jpg','keywords'=>'四川上云了吗科技发展有限公司','description'=>'四川上云了吗科技发展有限公司','footerleft'=>'','footerright'=>'','location'=>'','icp'=>'','create_time'=>'2024-03-30 19:51:28','update_time'=>'2024-03-31 10:53:05','mobile'=>'','city'=>'四川','company_name'=>'四川上云了吗科技发展有限公司','wechat'=>'ygkbdcxy','status'=>'1','bloc_id'=>'118','store_id'=>'305']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%website_station_group}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

