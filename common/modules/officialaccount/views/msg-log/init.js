export const form = {"blocs":{"type":"cascader-store","label":"选择公司"},"log_id":{"type":"input","label":"ID"},"appid":{"type":"input","label":"appid"},"touser":{"type":"input","label":"用户openid"},"template_id":{"type":"input","label":"templateid"},"data":{"type":"input","label":"消息数据"},"url":{"type":"input","label":"消息链接"},"miniprogram":{"type":"input","label":"小程序信息"},"send_time":{"type":"input","label":"发送时间"},"send_result":{"type":"input","label":"发送结果"}}

export const order = ["log_id","appid","touser","template_id","data","url","miniprogram","send_time","send_result"];

export const tableColumns = [{"label":"ID","prop":"log_id"},{"label":"appid","prop":"appid"},{"label":"用户openid","prop":"touser"},{"label":"templateid","prop":"template_id"},{"label":"消息数据","prop":"data"},{"label":"消息链接","prop":"url"},{"label":"小程序信息","prop":"miniprogram"},{"label":"发送时间","prop":"send_time"},{"label":"发送结果","prop":"send_result"}]

export const filterInfo = {
    fieldList: {"label":"发送结果","type":"input","value":"OfficialaccountTemplateMsgLog[send_result]"},

}

export const path = {
    index: '',
    update: '',
    create: '',
    api: '/officialaccount/msg-log'
}

export const rowKey = ''