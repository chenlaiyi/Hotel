export const form = {"blocs":{"type":"cascader-store","label":"选择公司"},"id":{"type":"number","label":"ID"},"user_id":{"type":"number","label":"User ID"},"member_id":{"type":"number","label":"Member ID"},"type":{"type":"input","label":"Type"},"extra":{"type":"number","label":"Extra"},"qrcid":{"type":"input","label":"Qrcid"},"scene_str":{"type":"input","label":"Scene Str"},"name":{"type":"input","label":"Name"},"keyword":{"type":"input","label":"Keyword"},"model":{"type":"input","label":"Model"},"ticket":{"type":"input","label":"Ticket"},"url":{"type":"input","label":"Url"},"expire":{"type":"number","label":"Expire"},"subnum":{"type":"number","label":"Subnum"},"status":{"type":"input","label":"Status"}}

export const order = ["id","store_id","bloc_id","user_id","member_id","type","extra","qrcid","scene_str","name","keyword","model","ticket","url","expire","subnum","update_time","create_time","status"];

export const tableColumns = [{"label":"ID","prop":"id"},{"label":"Store ID","prop":"store_id"},{"label":"Bloc ID","prop":"bloc_id"},{"label":"User ID","prop":"user_id"},{"label":"Member ID","prop":"member_id"},{"label":"Type","prop":"type"},{"label":"Extra","prop":"extra"},{"label":"Qrcid","prop":"qrcid"},{"label":"Scene Str","prop":"scene_str"},{"label":"Name","prop":"name"},{"label":"Keyword","prop":"keyword"},{"label":"Model","prop":"model"},{"label":"Ticket","prop":"ticket"},{"label":"Url","prop":"url"},{"label":"Expire","prop":"expire"},{"label":"Subnum","prop":"subnum"},{"label":"Update Time","prop":"update_time"},{"label":"Create Time","prop":"create_time"},{"label":"Status","prop":"status"}]

export const filterInfo = {
    fieldList: {"label":"Status","type":"input","value":"OfficialaccountQrcode[status]"},

}

export const path = {
    index: '',
    update: '',
    create: '',
    api: '/officialaccount/qrcode'
}

export const rowKey = ''