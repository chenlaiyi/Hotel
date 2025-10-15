<?php
use yii\db\Migration;
class m240108_234139_diandi_place_room_device extends Migration
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
            'unit_id' => "int(11) NULL COMMENT '单位ID'",
            'project_id' => "int(11) NULL COMMENT '项目ID'",
            'mac' => "varchar(50) NULL COMMENT 'mac标识'",
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
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='房间设备'");
        /* 索引设置 */
        $this->createIndex('project_id','{{%diandi_place_room_device}}','project_id',0);
        /* 表数据 */
        $this->insert('{{%diandi_place_room_device}}',['id'=>'2','bloc_id'=>'0','store_id'=>'0','type_id'=>NULL,'hotel_type'=>'23','hotel_id'=>'109','tier_id'=>'221','room_id'=>'9','title'=>'家里测试','unit_id'=>'0','project_id'=>'1','mac'=>'A0764EAEED3E','device_id'=>'77','lock_type'=>NULL,'device_type'=>'48','manufactor_id'=>'1','displayorder'=>NULL,'device_status'=>'{\"id\":6,\"create_time\":\"2023-12-25 23:45:03\",\"update_time\":\"2024-01-08 22:13:25\",\"member_id\":0,\"mac\":\"A0764EAEED3E\",\"log_id\":111,\"device_id\":77,\"device_type\":48,\"project_id\":1,\"electric_A0\":24413,\"electric_A1\":0,\"electric_E2\":0,\"electric_A3\":4996,\"electric_84\":0,\"electric_E5\":3,\"electric_86\":0,\"electric_86_02\":1,\"electric_E7\":\"\",\"electric_E8\":\"\",\"select_num\":2,\"wifiMac\":\"88c397dcc158\",\"power_on_num\":133}','create_time'=>'2023-12-25 23:54:55','update_time'=>'2024-01-08 22:13:25','status'=>'1']);
        $this->insert('{{%diandi_place_room_device}}',['id'=>'3','bloc_id'=>'0','store_id'=>'0','type_id'=>NULL,'hotel_type'=>'23','hotel_id'=>'109','tier_id'=>'221','room_id'=>'10','title'=>'家里测试电表','unit_id'=>'0','project_id'=>'1','mac'=>'0EEC63019642','device_id'=>'35','lock_type'=>NULL,'device_type'=>'82','manufactor_id'=>'1','displayorder'=>NULL,'device_status'=>'{\"id\":16,\"device_id\":35,\"mac\":\"0EEC63019642\",\"project_id\":1,\"title\":\"\\u52b3\\u6a21\",\"device_type\":82,\"number\":\"0EE26E92\",\"auth_number\":65274714,\"table_num\":63019642,\"status\":\"SEND_FAIL_OFFLINE\",\"residual_degree\":239.32,\"buy_num\":5,\"buy_total\":240.05,\"use_total\":0.73,\"unit_number\":\"00D1\",\"start_number\":10,\"charge_type\":0,\"uni_price\":1.01,\"create_time\":\"2023-10-24 15:44:34\",\"update_time\":\"2024-01-08 22:13:00\"}','create_time'=>'2023-12-26 01:09:20','update_time'=>'2024-01-08 22:13:00','status'=>'1']);
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
