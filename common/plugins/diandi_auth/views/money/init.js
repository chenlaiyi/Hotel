export const form = {"blocs":{"type":"cascader-store","label":"选择公司"},"id":{"type":"number","label":"ID"},"change_start_type":{"type":"input","label":"1物流2服务3订单状态4核销单状态"},"change_end_type":{"type":"input","label":"1物流2服务3订单状态4核销单状态"},"change_start_int":{"type":"number","label":"开始"},"change_end_int":{"type":"number","label":"结束"},"moneyType":{"type":"input","label":"分润方式"},"money":{"type":"number","label":"分润金额"},"templateType":{"type":"number","label":"模板类型"},"member_store_id":{"type":"number","label":"Member Store ID"},"member_product_id":{"type":"number","label":"Member Product ID"}}

export const order = ["id","bloc_id","store_id","change_start_type","change_end_type","change_start_int","change_end_int","moneyType","money","templateType","create_time","update_time","member_store_id","member_product_id"];

export const tableColumns = [{"label":"ID","prop":"id"},{"label":"业务中心","prop":"bloc_id"},{"label":"门店","prop":"store_id"},{"label":"1物流2服务3订单状态4核销单状态","prop":"change_start_type"},{"label":"1物流2服务3订单状态4核销单状态","prop":"change_end_type"},{"label":"开始","prop":"change_start_int"},{"label":"结束","prop":"change_end_int"},{"label":"分润方式","prop":"moneyType"},{"label":"分润金额","prop":"money"},{"label":"模板类型","prop":"templateType"},{"label":"创建时间","prop":"create_time"},{"label":"更新时间","prop":"update_time"},{"label":"Member Store ID","prop":"member_store_id"},{"label":"Member Product ID","prop":"member_product_id"},{"label":"操作","prop":"action","width":"200","slot":"action"}]

export const filterInfo = {
    fieldList: [{"label":"Member Product ID","type":"input","value":"AuthMemberRoleMoney[member_product_id]"}],

}

export const path = {
    index: '',
    update: '',
    create: '',
    api: '/diandi_auth/money'
}

export const rowKey = ''