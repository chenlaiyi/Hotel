<?php

use yii\db\Migration;

class m240417_014729_officialaccount_msg_template extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%officialaccount_msg_template}}', [
            'id' => "bigint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id'",
            'bloc_id' => "int(11) NULL",
            'template_id' => "varchar(100) NOT NULL COMMENT '公众号模板ID'",
            'title' => "varchar(20) NULL COMMENT '标题'",
            'content' => "text NULL COMMENT '模板内容'",
            'example' => "varchar(255) NULL COMMENT '消息内容'",
            'status' => "tinyint(1) unsigned NOT NULL COMMENT '是否有效'",
            'create_time' => "varchar(30) NULL",
            'update_time' => "varchar(30) NULL",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='消息模板'");
        
        /* 索引设置 */
        $this->createIndex('idx_status','{{%officialaccount_msg_template}}','status',0);
        
        
        /* 表数据 */
        $this->insert('{{%officialaccount_msg_template}}',['id'=>'56','bloc_id'=>'0','template_id'=>'S-xuAGmX_mXZMhQb3uvyv9Me890Z96npdaklo0ngWKQ','title'=>'订阅模板消息','content'=>'[{\"name\":\"\",\"field\":\"content\",\"value\":\"\",\"color\":\"\"}]','example'=>'','status'=>'0','create_time'=>'2024-03-18 23:26:52','update_time'=>'2024-03-18 23:26:52']);
        $this->insert('{{%officialaccount_msg_template}}',['id'=>'57','bloc_id'=>'0','template_id'=>'r1Md6RPAMXvJDWBevoTgirsnM_n6ibjpJbh3X0-zEeo','title'=>'登录成功通知','content'=>'[{\"name\":\"官网入口\",\"field\":\"character_string2\",\"value\":\"\",\"color\":\"\"},{\"name\":\"登录时间\",\"field\":\"time1\",\"value\":\"\",\"color\":\"\"}]','example'=>'官网入口:https://cephalon.cloud
登录时间:2023年9月1日
','status'=>'0','create_time'=>'2024-03-18 23:26:52','update_time'=>'2024-03-18 23:26:52']);
        $this->insert('{{%officialaccount_msg_template}}',['id'=>'58','bloc_id'=>'0','template_id'=>'Nf03nIQJNDmTj9DtWc3_TCsRpd6J8rdU1jvEcVNnEZg','title'=>'登录成功通知','content'=>'[{\"name\":\"用户名\",\"field\":\"thing17\",\"value\":\"\",\"color\":\"\"},{\"name\":\"手机号\",\"field\":\"character_string16\",\"value\":\"\",\"color\":\"\"},{\"name\":\"登录身份\",\"field\":\"thing11\",\"value\":\"\",\"color\":\"\"},{\"name\":\"登录时间\",\"field\":\"time3\",\"value\":\"\",\"color\":\"\"},{\"name\":\"到期时间\",\"field\":\"time13\",\"value\":\"\",\"color\":\"\"}]','example'=>'用户名:张三
手机号:15900000000
登录身份:管理员
登录时间:2023-0213 16:33:03
到期时间:2023年8月15日 18:25
','status'=>'0','create_time'=>'2024-03-18 23:26:52','update_time'=>'2024-03-18 23:26:52']);
        $this->insert('{{%officialaccount_msg_template}}',['id'=>'59','bloc_id'=>'0','template_id'=>'e4fMTIeWuo6x34OOZ3axUEDJd1eN4opLNHfNAc8oPsI','title'=>'账号申请成功通知','content'=>'[{\"name\":\"用户名\",\"field\":\"character_string2\",\"value\":\"\",\"color\":\"\"},{\"name\":\"密码\",\"field\":\"character_string3\",\"value\":\"\",\"color\":\"\"}]','example'=>'用户名:15030184988
密码:72jfKLSf32
','status'=>'0','create_time'=>'2024-03-18 23:26:52','update_time'=>'2024-03-18 23:26:52']);
        $this->insert('{{%officialaccount_msg_template}}',['id'=>'60','bloc_id'=>'0','template_id'=>'3oop1wjOlNpxpgwBnL_CmndQGNPeSs9Iofi1OwqlNR8','title'=>'用户解除绑定通知','content'=>'[{\"name\":\"用户名称\",\"field\":\"thing1\",\"value\":\"\",\"color\":\"\"},{\"name\":\"解除时间\",\"field\":\"time2\",\"value\":\"\",\"color\":\"\"}]','example'=>'用户名称:郭老师
解除时间:2023-05-16  18:20:12
','status'=>'0','create_time'=>'2024-03-18 23:26:52','update_time'=>'2024-03-18 23:26:52']);
        $this->insert('{{%officialaccount_msg_template}}',['id'=>'62','bloc_id'=>'0','template_id'=>'DfaNjZQ_8JFg5YOBaoSiSiJa2wktY8HPOm_uTr1FC0U','title'=>'试用申请审核通过通知','content'=>'[{\"name\":\"试用版本\",\"field\":\"phrase3\",\"value\":\"\",\"color\":\"\"},{\"name\":\"到期时间\",\"field\":\"time4\",\"value\":\"\",\"color\":\"\"}]','example'=>'试用版本:团队版
到期时间:2022年12月23日
','status'=>'0','create_time'=>'2024-03-19 09:52:31','update_time'=>'2024-03-19 09:52:31']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%officialaccount_msg_template}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

