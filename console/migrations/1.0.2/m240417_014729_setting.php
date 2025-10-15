<?php

use yii\db\Migration;

class m240417_014729_setting extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%setting}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'cate_name' => "varchar(255) NULL",
            'type' => "varchar(10) NOT NULL",
            'section' => "varchar(255) NOT NULL",
            'key' => "varchar(255) NOT NULL",
            'store_id' => "int(11) NULL",
            'bloc_id' => "int(11) NULL",
            'value' => "text NOT NULL",
            'status' => "smallint(6) NOT NULL DEFAULT '1'",
            'description' => "varchar(255) NULL",
            'created_at' => "int(11) NOT NULL",
            'updated_at' => "int(11) NOT NULL",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%setting}}',['id'=>'1','cate_name'=>NULL,'type'=>'string','section'=>'Wechat','key'=>'app_id','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'','status'=>'1','description'=>NULL,'created_at'=>'1605602761','updated_at'=>'1707819830']);
        $this->insert('{{%setting}}',['id'=>'2','cate_name'=>NULL,'type'=>'string','section'=>'Wechat','key'=>'secret','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'','status'=>'1','description'=>NULL,'created_at'=>'1605602761','updated_at'=>'1605602761']);
        $this->insert('{{%setting}}',['id'=>'3','cate_name'=>NULL,'type'=>'string','section'=>'Wechat','key'=>'token','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'','status'=>'1','description'=>NULL,'created_at'=>'1605602761','updated_at'=>'1605605618']);
        $this->insert('{{%setting}}',['id'=>'4','cate_name'=>NULL,'type'=>'string','section'=>'Wechat','key'=>'aes_key','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'','status'=>'1','description'=>NULL,'created_at'=>'1605602761','updated_at'=>'1605605056']);
        $this->insert('{{%setting}}',['id'=>'5','cate_name'=>NULL,'type'=>'string','section'=>'Wechat','key'=>'headimg','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'','status'=>'1','description'=>NULL,'created_at'=>'1605602761','updated_at'=>'1605602761']);
        $this->insert('{{%setting}}',['id'=>'6','cate_name'=>NULL,'type'=>'string','section'=>'Wechatpay','key'=>'mch_id','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'','status'=>'1','description'=>NULL,'created_at'=>'1605602798','updated_at'=>'1707819162']);
        $this->insert('{{%setting}}',['id'=>'7','cate_name'=>NULL,'type'=>'string','section'=>'Wechatpay','key'=>'key','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'','status'=>'1','description'=>NULL,'created_at'=>'1605602798','updated_at'=>'1707819611']);
        $this->insert('{{%setting}}',['id'=>'8','cate_name'=>NULL,'type'=>'string','section'=>'Wechatpay','key'=>'app_id','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'','status'=>'1','description'=>NULL,'created_at'=>'1605602808','updated_at'=>'1707819688']);
        $this->insert('{{%setting}}',['id'=>'9','cate_name'=>NULL,'type'=>'string','section'=>'Map','key'=>'baiduApk','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'','status'=>'1','description'=>NULL,'created_at'=>'1605602979','updated_at'=>'1605602979']);
        $this->insert('{{%setting}}',['id'=>'10','cate_name'=>NULL,'type'=>'string','section'=>'Map','key'=>'amapApk','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'','status'=>'1','description'=>NULL,'created_at'=>'1605602979','updated_at'=>'1608147228']);
        $this->insert('{{%setting}}',['id'=>'11','cate_name'=>NULL,'type'=>'string','section'=>'Map','key'=>'tencentApk','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'','status'=>'1','description'=>NULL,'created_at'=>'1605602979','updated_at'=>'1605602979']);
        $this->insert('{{%setting}}',['id'=>'12','cate_name'=>NULL,'type'=>'string','section'=>'Weburl','key'=>'urls','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'','status'=>'1','description'=>NULL,'created_at'=>'1605630454','updated_at'=>'1632475081']);
        $this->insert('{{%setting}}',['id'=>'13','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'themcolor','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'','status'=>'1','description'=>NULL,'created_at'=>'1606656776','updated_at'=>'1606656776']);
        $this->insert('{{%setting}}',['id'=>'14','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'bloc_id','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'','status'=>'1','description'=>NULL,'created_at'=>'1606656776','updated_at'=>'1631462347']);
        $this->insert('{{%setting}}',['id'=>'15','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'store_id','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'','status'=>'1','description'=>NULL,'created_at'=>'1606656776','updated_at'=>'1631462347']);
        $this->insert('{{%setting}}',['id'=>'16','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'flogo','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'202401/12/4deda1f2-4aa2-35f3-b978-de827b86344c.png','status'=>'1','description'=>NULL,'created_at'=>'1614358572','updated_at'=>'1705012603']);
        $this->insert('{{%setting}}',['id'=>'17','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'blogo','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'202401/12/e59d8196-1199-3010-894a-e28a76fb4d19.png','status'=>'1','description'=>NULL,'created_at'=>'1614358572','updated_at'=>'1705012603']);
        $this->insert('{{%setting}}',['id'=>'18','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'name','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'店滴云','status'=>'1','description'=>NULL,'created_at'=>'1614358572','updated_at'=>'1705139383']);
        $this->insert('{{%setting}}',['id'=>'19','cate_name'=>NULL,'type'=>'string','section'=>'Email','key'=>'host','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'smtp.mxhichina.com','status'=>'1','description'=>NULL,'created_at'=>'1615046762','updated_at'=>'1615046762']);
        $this->insert('{{%setting}}',['id'=>'20','cate_name'=>NULL,'type'=>'string','section'=>'Email','key'=>'port','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'25','status'=>'1','description'=>NULL,'created_at'=>'1615046762','updated_at'=>'1615046762']);
        $this->insert('{{%setting}}',['id'=>'21','cate_name'=>NULL,'type'=>'string','section'=>'Email','key'=>'username','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'ai@tuhuokeji.com','status'=>'1','description'=>NULL,'created_at'=>'1615046762','updated_at'=>'1615046762']);
        $this->insert('{{%setting}}',['id'=>'22','cate_name'=>NULL,'type'=>'string','section'=>'Email','key'=>'password','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'!Wang1108','status'=>'1','description'=>NULL,'created_at'=>'1615046762','updated_at'=>'1615046762']);
        $this->insert('{{%setting}}',['id'=>'23','cate_name'=>NULL,'type'=>'string','section'=>'Email','key'=>'title','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'店滴ai','status'=>'1','description'=>NULL,'created_at'=>'1615046762','updated_at'=>'1615046762']);
        $this->insert('{{%setting}}',['id'=>'24','cate_name'=>NULL,'type'=>'string','section'=>'Email','key'=>'encryption','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'tls','status'=>'1','description'=>NULL,'created_at'=>'1615046762','updated_at'=>'1615046762']);
        $this->insert('{{%setting}}',['id'=>'25','cate_name'=>NULL,'type'=>'string','section'=>'Systask','key'=>'BASE_PHP_PATH','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'/www/server/php/73/bin/php','status'=>'1','description'=>NULL,'created_at'=>'1616013219','updated_at'=>'1616013219']);
        $this->insert('{{%setting}}',['id'=>'26','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'menu_type','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'0','status'=>'1','description'=>NULL,'created_at'=>'1618057135','updated_at'=>'1620466444']);
        $this->insert('{{%setting}}',['id'=>'27','cate_name'=>NULL,'type'=>'string','section'=>'Weburl','key'=>'backendurl','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'1','status'=>'1','description'=>NULL,'created_at'=>'1640079886','updated_at'=>'1640079886']);
        $this->insert('{{%setting}}',['id'=>'28','cate_name'=>NULL,'type'=>'string','section'=>'Weburl','key'=>'frendurl','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'2','status'=>'1','description'=>NULL,'created_at'=>'1640079886','updated_at'=>'1640079886']);
        $this->insert('{{%setting}}',['id'=>'29','cate_name'=>NULL,'type'=>'string','section'=>'Weburl','key'=>'apiurl','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'3','status'=>'1','description'=>NULL,'created_at'=>'1640079886','updated_at'=>'1640079886']);
        $this->insert('{{%setting}}',['id'=>'30','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'intro','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'让经营场所更智能','status'=>'1','description'=>NULL,'created_at'=>'1641659464','updated_at'=>'1659508488']);
        $this->insert('{{%setting}}',['id'=>'31','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'is_send_code','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'1','status'=>'1','description'=>NULL,'created_at'=>'1653963031','updated_at'=>'1705142970']);
        $this->insert('{{%setting}}',['id'=>'32','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'site_status','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'0','status'=>'1','description'=>NULL,'created_at'=>'1653963031','updated_at'=>'1653963031']);
        $this->insert('{{%setting}}',['id'=>'33','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'develop_status','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'1','status'=>'1','description'=>NULL,'created_at'=>'1653963031','updated_at'=>'1661946177']);
        $this->insert('{{%setting}}',['id'=>'34','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'keywords','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'店滴云','status'=>'1','description'=>NULL,'created_at'=>'1653963031','updated_at'=>'1705139383']);
        $this->insert('{{%setting}}',['id'=>'35','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'description','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'让经营场所更智能','status'=>'1','description'=>NULL,'created_at'=>'1653963031','updated_at'=>'1659508488']);
        $this->insert('{{%setting}}',['id'=>'36','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'footerleft','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'西安店滴云网络科技有限公司','status'=>'1','description'=>NULL,'created_at'=>'1657539470','updated_at'=>'1657539470']);
        $this->insert('{{%setting}}',['id'=>'37','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'footerright','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'Powered by 店滴云 v1.9.1 © 2013-2022','status'=>'1','description'=>NULL,'created_at'=>'1657539470','updated_at'=>'1657539470']);
        $this->insert('{{%setting}}',['id'=>'38','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'location','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'https://beian.miit.gov.cn/#/Integrated/recordQuery','status'=>'1','description'=>NULL,'created_at'=>'1657539470','updated_at'=>'1657539470']);
        $this->insert('{{%setting}}',['id'=>'39','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'icp','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'陕ICP备2022008115号-1','status'=>'1','description'=>NULL,'created_at'=>'1657539470','updated_at'=>'1657606532']);
        $this->insert('{{%setting}}',['id'=>'40','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'access_key_id','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'1400881024','status'=>'1','description'=>NULL,'created_at'=>'1657592267','updated_at'=>'1704690846']);
        $this->insert('{{%setting}}',['id'=>'41','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'access_key_secret','store_id'=>NULL,'bloc_id'=>NULL,'value'=>' ','status'=>'1','description'=>NULL,'created_at'=>'1657592267','updated_at'=>'1704690846']);
        $this->insert('{{%setting}}',['id'=>'42','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'sign_name','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'西安店滴云科技','status'=>'1','description'=>NULL,'created_at'=>'1657592267','updated_at'=>'1704690846']);
        $this->insert('{{%setting}}',['id'=>'43','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'template_code','store_id'=>NULL,'bloc_id'=>NULL,'value'=>' ','status'=>'1','description'=>NULL,'created_at'=>'1657592267','updated_at'=>'1705142970']);
        $this->insert('{{%setting}}',['id'=>'44','cate_name'=>NULL,'type'=>'string','section'=>'Weburl','key'=>'bloc_id','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'38','status'=>'1','description'=>NULL,'created_at'=>'1681218313','updated_at'=>'1681218313']);
        $this->insert('{{%setting}}',['id'=>'45','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'loginbg','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'202401/13/32a998e2-9576-3a36-82a6-342d595f6168.png','status'=>'1','description'=>NULL,'created_at'=>'1705139860','updated_at'=>'1705139860']);
        $this->insert('{{%setting}}',['id'=>'54','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'qcloud_access_key_id','store_id'=>NULL,'bloc_id'=>NULL,'value'=>' ','status'=>'1','description'=>NULL,'created_at'=>'1705145800','updated_at'=>'1706235002']);
        $this->insert('{{%setting}}',['id'=>'55','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'qcloud_access_key_secret','store_id'=>NULL,'bloc_id'=>NULL,'value'=>' ','status'=>'1','description'=>NULL,'created_at'=>'1705145800','updated_at'=>'1706235002']);
        $this->insert('{{%setting}}',['id'=>'56','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'qcloud_sign_name','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'西安店滴云科技','status'=>'1','description'=>NULL,'created_at'=>'1705145800','updated_at'=>'1706235002']);
        $this->insert('{{%setting}}',['id'=>'57','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'qcloud_template_code','store_id'=>NULL,'bloc_id'=>NULL,'value'=>' ','status'=>'1','description'=>NULL,'created_at'=>'1705145800','updated_at'=>'1706235002']);
        $this->insert('{{%setting}}',['id'=>'58','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'aliyun_access_key_id','store_id'=>NULL,'bloc_id'=>NULL,'value'=>' ','status'=>'1','description'=>NULL,'created_at'=>'1705145800','updated_at'=>'1706235002']);
        $this->insert('{{%setting}}',['id'=>'59','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'aliyun_access_key_secret','store_id'=>NULL,'bloc_id'=>NULL,'value'=>' ','status'=>'1','description'=>NULL,'created_at'=>'1705145800','updated_at'=>'1706235002']);
        $this->insert('{{%setting}}',['id'=>'60','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'aliyun_sign_name','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'店滴云','status'=>'1','description'=>NULL,'created_at'=>'1705145800','updated_at'=>'1706235002']);
        $this->insert('{{%setting}}',['id'=>'61','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'aliyun_template_code','store_id'=>NULL,'bloc_id'=>NULL,'value'=>' ','status'=>'1','description'=>NULL,'created_at'=>'1705145800','updated_at'=>'1706235002']);
        $this->insert('{{%setting}}',['id'=>'62','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'qcloud_secret_key','store_id'=>NULL,'bloc_id'=>NULL,'value'=>' ','status'=>'1','description'=>NULL,'created_at'=>'1706247516','updated_at'=>'1706247516']);
        $this->insert('{{%setting}}',['id'=>'63','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'qcloud_secret_id','store_id'=>NULL,'bloc_id'=>NULL,'value'=>' ','status'=>'1','description'=>NULL,'created_at'=>'1706247516','updated_at'=>'1706247516']);
        $this->insert('{{%setting}}',['id'=>'64','cate_name'=>NULL,'type'=>'string','section'=>'Website','key'=>'qcloud_sdk_app_id','store_id'=>NULL,'bloc_id'=>NULL,'value'=>' ','status'=>'1','description'=>NULL,'created_at'=>'1706247516','updated_at'=>'1706247516']);
        $this->insert('{{%setting}}',['id'=>'65','cate_name'=>NULL,'type'=>'string','section'=>'Baidu','key'=>'APP_ID','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'2','status'=>'1','description'=>NULL,'created_at'=>'1707816771','updated_at'=>'1707816771']);
        $this->insert('{{%setting}}',['id'=>'66','cate_name'=>NULL,'type'=>'string','section'=>'Baidu','key'=>'name','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'2','status'=>'1','description'=>NULL,'created_at'=>'1707816772','updated_at'=>'1707817933']);
        $this->insert('{{%setting}}',['id'=>'67','cate_name'=>NULL,'type'=>'string','section'=>'Baidu','key'=>'API_KEY','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'2','status'=>'1','description'=>NULL,'created_at'=>'1707816772','updated_at'=>'1707816772']);
        $this->insert('{{%setting}}',['id'=>'68','cate_name'=>NULL,'type'=>'string','section'=>'Baidu','key'=>'SECRET_KEY','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'2','status'=>'1','description'=>NULL,'created_at'=>'1707816772','updated_at'=>'1707816772']);
        $this->insert('{{%setting}}',['id'=>'69','cate_name'=>NULL,'type'=>'string','section'=>'Oss','key'=>'remote_type','store_id'=>NULL,'bloc_id'=>NULL,'value'=>'local','status'=>'1','description'=>NULL,'created_at'=>'1707817987','updated_at'=>'1707817987']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%setting}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

