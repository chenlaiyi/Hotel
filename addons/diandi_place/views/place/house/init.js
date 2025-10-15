export const form = {"blocs":{"type":"cascader-store","label":"选择公司"},"id":{"type":"number","label":"ID"},"place_type_id":{"type":"number","label":"业务类型"},"title":{"type":"input","label":"房型名称"}}
export const order = ["id","place_type_id","title","bloc_id","store_id","create_time","update_time"];
export const tableColumns = [{"label":"ID","prop":"id"},{"label":"业务类型","prop":"place_type_id"},{"label":"房型名称","prop":"title"},{"label":"Bloc ID","prop":"bloc_id"},{"label":"Store ID","prop":"store_id"},{"label":"Create Time","prop":"create_time"},{"label":"Update Time","prop":"update_time"}]
export const filterInfo = {
    fieldList: {"label":"Update Time","type":"input","value":"PlaceTypeList[update_time]"},
}
export const path = {
    index: '',
    update: '',
    create: '',
    api: '/diandi_place/house'
}
export const rowKey = ''