export const form = {"blocs":{"type":"cascader-store","label":"选择公司"},"rule_id":{"type":"number","label":"Rule ID"},"appid":{"type":"input","label":"appid"},"rule_name":{"type":"input","label":"规则名称"},"match_value":{"type":"input","label":"匹配的关键词、事件等"},"exact_match":{"type":"input","label":"是否精确匹配"},"reply_type":{"type":"input","label":"回复消息类型"},"reply_content":{"type":"input","label":"回复消息内容"},"status":{"type":"input","label":"规则是否有效"},"desc":{"type":"input","label":"备注说明"},"effect_time_start":{"type":"input","label":"Effect Time Start"},"effect_time_end":{"type":"input","label":"Effect Time End"},"priority":{"type":"number","label":"规则优先级"}}

export const order = ["rule_id","appid","rule_name","match_value","exact_match","reply_type","reply_content","status","desc","effect_time_start","effect_time_end","priority","create_time","update_time"];

export const tableColumns = [{"label":"Rule ID","prop":"rule_id"},{"label":"appid","prop":"appid"},{"label":"规则名称","prop":"rule_name"},{"label":"匹配的关键词、事件等","prop":"match_value"},{"label":"是否精确匹配","prop":"exact_match"},{"label":"回复消息类型","prop":"reply_type"},{"label":"回复消息内容","prop":"reply_content"},{"label":"规则是否有效","prop":"status"},{"label":"备注说明","prop":"desc"},{"label":"Effect Time Start","prop":"effect_time_start"},{"label":"Effect Time End","prop":"effect_time_end"},{"label":"规则优先级","prop":"priority"},{"label":"Create Time","prop":"create_time"},{"label":"Update Time","prop":"update_time"}]

export const filterInfo = {
    fieldList: {"label":"Update Time","type":"input","value":"OfficialaccountMsgReplyRule[update_time]"},

}

export const path = {
    index: '',
    update: '',
    create: '',
    api: '/officialaccount/msg-reply-rule'
}

export const rowKey = ''