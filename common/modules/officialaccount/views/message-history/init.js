export const form = {"blocs":{"type":"cascader-store","label":"选择公司"},"id":{"type":"number","label":"ID"},"rid":{"type":"number","label":"相应规则ID"},"kid":{"type":"number","label":"所属关键字ID"},"from":{"type":"input","label":"请求用户ID"},"module":{"type":"input","label":"处理模块"},"message":{"type":"input","label":"消息体内容"},"type":{"type":"number","label":"发送类型"}}

export const order = ["id","bloc_id","store_id","rid","kid","from","module","message","type","create_time","update_time"];

export const tableColumns = [{"label":"ID","prop":"id"},{"label":"Bloc ID","prop":"bloc_id"},{"label":"Store ID","prop":"store_id"},{"label":"相应规则ID","prop":"rid"},{"label":"所属关键字ID","prop":"kid"},{"label":"请求用户ID","prop":"from"},{"label":"处理模块","prop":"module"},{"label":"消息体内容","prop":"message"},{"label":"发送类型","prop":"type"},{"label":"Create Time","prop":"create_time"},{"label":"Update Time","prop":"update_time"}]

export const filterInfo = {
    fieldList: {"label":"Update Time","type":"input","value":"OfficialaccountWechatMessageHistory[update_time]"},

}

export const path = {
    index: '',
    update: '',
    create: '',
    api: '/officialaccount/message-history'
}

export const rowKey = ''