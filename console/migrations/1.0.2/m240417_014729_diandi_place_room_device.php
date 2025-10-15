<?php

use yii\db\Migration;

class m240417_014729_diandi_place_room_device extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_place_room_device}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL",
            'store_id' => "int(11) NULL",
            'type_id' => "int(11) NULL COMMENT '设备细分类型'",
            'hotel_type' => "int(11) NULL COMMENT '楼栋类型'",
            'hotel_id' => "int(11) NULL COMMENT '楼栋ID'",
            'tier_id' => "int(11) NULL COMMENT '楼层'",
            'room_id' => "int(11) NULL COMMENT '房间ID'",
            'title' => "varchar(50) NULL COMMENT '名称'",
            'project_id' => "int(11) NULL COMMENT '项目ID'",
            'mac' => "varchar(50) NULL COMMENT 'mac标识'",
            'unit_id' => "int(11) NULL COMMENT '单位标识'",
            'device_id' => "varchar(255) NULL COMMENT '设备编号'",
            'lock_type' => "tinyint(11) NULL COMMENT '门锁类型'",
            'device_type' => "tinyint(1) NULL COMMENT '设备类型'",
            'manufactor_id' => "int(11) NULL COMMENT '厂家'",
            'displayorder' => "int(11) NULL COMMENT '排序'",
            'device_status' => "text NULL COMMENT '设备实时状态'",
            'create_time' => "datetime NULL",
            'update_time' => "datetime NULL",
            'status' => "tinyint(4) NULL COMMENT '状态 1已绑定 2未绑定'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='房间设备'");
        
        /* 索引设置 */
        $this->createIndex('project_id','{{%diandi_place_room_device}}','project_id',0);
        
        
        /* 表数据 */
        $this->insert('{{%diandi_place_room_device}}',['id'=>'2','bloc_id'=>'0','store_id'=>'0','type_id'=>NULL,'hotel_type'=>'15','hotel_id'=>'108','tier_id'=>'220','room_id'=>'3','title'=>'主房间','project_id'=>'1','mac'=>'6194300db128','unit_id'=>NULL,'device_id'=>'78','lock_type'=>NULL,'device_type'=>'81','manufactor_id'=>'1','displayorder'=>NULL,'device_status'=>NULL,'create_time'=>'2024-01-15 18:07:10','update_time'=>'2024-01-15 18:07:10','status'=>'1']);
        $this->insert('{{%diandi_place_room_device}}',['id'=>'6','bloc_id'=>'0','store_id'=>'0','type_id'=>NULL,'hotel_type'=>'15','hotel_id'=>'108','tier_id'=>'220','room_id'=>'3','title'=>'家用控制','project_id'=>'1','mac'=>'0EEC63019642','unit_id'=>NULL,'device_id'=>'35','lock_type'=>NULL,'device_type'=>'82','manufactor_id'=>'1','displayorder'=>NULL,'device_status'=>'{\"id\":16,\"device_id\":35,\"mac\":\"0EEC63019642\",\"project_id\":1,\"title\":\"\\u52b3\\u6a21\",\"device_type\":82,\"number\":\"0EE26E92\",\"auth_number\":65274714,\"table_num\":63019642,\"status\":\"SEND_FAIL_OFFLINE\",\"residual_degree\":0,\"buy_num\":0,\"buy_total\":0,\"use_total\":0.17,\"unit_number\":\"00D1\",\"start_number\":10,\"charge_type\":0,\"uni_price\":1.01,\"create_time\":\"2023-10-24 15:44:34\",\"update_time\":\"2024-04-17 08:58:00\"}','create_time'=>'2024-01-16 08:04:18','update_time'=>'2024-04-17 08:58:00','status'=>'1']);
        $this->insert('{{%diandi_place_room_device}}',['id'=>'7','bloc_id'=>'0','store_id'=>'0','type_id'=>NULL,'hotel_type'=>'15','hotel_id'=>'108','tier_id'=>'220','room_id'=>'3','title'=>'家里','project_id'=>'1','mac'=>'A0764EAEED3E','unit_id'=>NULL,'device_id'=>'77','lock_type'=>NULL,'device_type'=>'48','manufactor_id'=>'1','displayorder'=>NULL,'device_status'=>'{\"id\":6,\"create_time\":\"2023-12-25 23:45:03\",\"update_time\":\"2024-03-12 15:42:59\",\"member_id\":0,\"mac\":\"A0764EAEED3E\",\"report_state_log_id\":182,\"log_id\":180,\"device_id\":77,\"device_type\":48,\"project_id\":1,\"electric_A0\":24216,\"electric_A1\":0,\"electric_E2\":2,\"electric_A3\":4998,\"electric_84\":3,\"electric_E5\":0,\"electric_86\":0,\"electric_86_02\":1,\"electric_E7\":\"\",\"electric_E8\":\"\",\"select_num\":2,\"wifiMac\":\"88c397dcc158\",\"power_on_num\":144}','create_time'=>'2024-01-20 17:47:51','update_time'=>'2024-03-12 15:42:59','status'=>'1']);
        $this->insert('{{%diandi_place_room_device}}',['id'=>'8','bloc_id'=>'0','store_id'=>'0','type_id'=>NULL,'hotel_type'=>'15','hotel_id'=>'108','tier_id'=>'220','room_id'=>'3','title'=>'主卧','project_id'=>'1','mac'=>'6e702e09599a','unit_id'=>NULL,'device_id'=>'85','lock_type'=>NULL,'device_type'=>'81','manufactor_id'=>'1','displayorder'=>NULL,'device_status'=>NULL,'create_time'=>'2024-01-24 20:07:05','update_time'=>'2024-01-24 20:07:05','status'=>'1']);
        $this->insert('{{%diandi_place_room_device}}',['id'=>'12','bloc_id'=>'0','store_id'=>'0','type_id'=>NULL,'hotel_type'=>'15','hotel_id'=>'108','tier_id'=>'220','room_id'=>'0','title'=>'测试','project_id'=>'1','mac'=>'083a8d97a480','unit_id'=>NULL,'device_id'=>'79','lock_type'=>NULL,'device_type'=>'80','manufactor_id'=>'1','displayorder'=>NULL,'device_status'=>NULL,'create_time'=>'2024-01-25 13:06:42','update_time'=>'2024-01-25 13:06:42','status'=>'1']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_room_device}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

