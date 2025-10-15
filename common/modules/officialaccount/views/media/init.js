export const form = {"blocs":{"type":"cascader-store","label":"选择公司"},"id":{"type":"number","label":"ID"},"filename":{"type":"input","label":"文件名称"},"result":{"type":"input","label":"响应内容"},"type":{"type":"number","label":"媒体类型"},"material":{"type":"number","label":"素材类别"}}

export const order = ["id","bloc_id","store_id","filename","result","type","material"];

export const tableColumns = [{"label":"ID","prop":"id"},{"label":"Bloc ID","prop":"bloc_id"},{"label":"Store ID","prop":"store_id"},{"label":"文件名称","prop":"filename"},{"label":"响应内容","prop":"result"},{"label":"媒体类型","prop":"type"},{"label":"素材类别","prop":"material"}]

export const filterInfo = {
    fieldList: {"label":"素材类别","type":"input","value":"OfficialaccountWechatMedia[material]"},

}

export const path = {
    index: '',
    update: '',
    create: '',
    api: '/officialaccount/media'
}

export const rowKey = ''