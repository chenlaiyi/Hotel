export const form = {"blocs":{"type":"cascader-store","label":"选择公司"},"id":{"type":"input","label":"id"},"appid":{"type":"input","label":"appid"},"template_id":{"type":"input","label":"公众号模板ID"},"name":{"type":"input","label":"模版名称"},"title":{"type":"input","label":"标题"},"content":{"type":"textarea","label":"模板内容"},"data":{"type":"input","label":"消息内容"},"url":{"type":"input","label":"链接"},"miniprogram":{"type":"input","label":"小程序信息"},"status":{"type":"input","label":"是否有效"}}

export const order = ["id","appid","template_id","name","title","content","data","url","miniprogram","status","create_time","update_time"];

export const tableColumns = [{"label":"id","prop":"id"},{"label":"appid","prop":"appid"},{"label":"公众号模板ID","prop":"template_id"},{"label":"模版名称","prop":"name"},{"label":"标题","prop":"title"},{"label":"模板内容","prop":"content"},{"label":"消息内容","prop":"data"},{"label":"链接","prop":"url"},{"label":"小程序信息","prop":"miniprogram"},{"label":"是否有效","prop":"status"},{"label":"Create Time","prop":"create_time"},{"label":"Update Time","prop":"update_time"}]

export const filterInfo = {
    fieldList: {"label":"Update Time","type":"input","value":"OfficialaccountMsgTemplate[update_time]"},

}

export const path = {
    index: '',
    update: '',
    create: '',
    api: '/officialaccount/msg-template'
}

export const rowKey = ''