export const form = {"blocs":{"type":"cascader-store","label":"选择公司"},"id":{"type":"input","label":"主键"},"appid":{"type":"input","label":"appid"},"openid":{"type":"input","label":"微信用户ID"},"in_out":{"type":"input","label":"消息方向"},"msg_type":{"type":"input","label":"消息类型"},"detail":{"type":"input","label":"消息详情"}}

export const order = ["id","appid","openid","in_out","msg_type","detail","create_time","update_time"];

export const tableColumns = [{"label":"主键","prop":"id"},{"label":"appid","prop":"appid"},{"label":"微信用户ID","prop":"openid"},{"label":"消息方向","prop":"in_out"},{"label":"消息类型","prop":"msg_type"},{"label":"消息详情","prop":"detail"},{"label":"Create Time","prop":"create_time"},{"label":"Update Time","prop":"update_time"}]

export const filterInfo = {
    fieldList: {"label":"Update Time","type":"input","value":"OfficialaccountMsg[update_time]"},

}

export const path = {
    index: '',
    update: '',
    create: '',
    api: '/officialaccount/msg'
}

export const rowKey = ''