export const form = {"blocs":{"type":"cascader-store","label":"选择公司"},"id":{"type":"number","label":"ID"},"title":{"type":"input","label":"Title"}}
export const order = ["id","title","bloc_id","store_id","create_time","update_time"];
export const tableColumns = [{"label":"ID","prop":"id"},{"label":"Title","prop":"title"},{"label":"Bloc ID","prop":"bloc_id"},{"label":"Store ID","prop":"store_id"},{"label":"Create Time","prop":"create_time"},{"label":"Update Time","prop":"update_time"},{"label":"操作","prop":"action","width":"200","slot":"action"}]
export const filterInfo = {
    fieldList: [{"label":"Update Time","type":"input","value":"PlaceType[update_time]"}],
}
export const path = {
    index: '',
    update: '',
    create: '',
    api: '/diandi_place/place-type'
}
export const rowKey = ''