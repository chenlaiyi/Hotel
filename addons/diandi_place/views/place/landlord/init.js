export const form = {"blocs":{"type":"cascader-store","label":"选择公司"},"id":{"type":"number","label":"ID"},"member_id":{"type":"number","label":"会员ID"},"realname":{"type":"input","label":"真实姓名"},"language":{"type":"number","label":"房东默认语言"},"desc":{"type":"input","label":"房东简介"},"content":{"type":"textarea","label":"房东描述"},"mobile":{"type":"input","label":"手机号"},"status":{"type":"number","label":"用户状态"},"icard_code":{"type":"input","label":"身份证号码"},"icard_front":{"type":"input","label":"身份证正面"},"icard_back":{"type":"input","label":"身份证反面"}}
export const order = ["id","bloc_id","store_id","member_id","realname","language","desc","content","mobile","status","icard_code","icard_front","icard_back"];
export const tableColumns = [{"label":"ID","prop":"id"},{"label":"公司ID","prop":"bloc_id"},{"label":"商户ID","prop":"store_id"},{"label":"会员ID","prop":"member_id"},{"label":"真实姓名","prop":"realname"},{"label":"房东默认语言","prop":"language"},{"label":"房东简介","prop":"desc"},{"label":"房东描述","prop":"content"},{"label":"手机号","prop":"mobile"},{"label":"用户状态","prop":"status"},{"label":"身份证号码","prop":"icard_code"},{"label":"身份证正面","prop":"icard_front"},{"label":"身份证反面","prop":"icard_back"}]
export const filterInfo = {
    fieldList: {"label":"身份证反面","type":"input","value":"PlaceLandlord[icard_back]"},
}
export const path = {
    index: '',
    update: '',
    create: '',
    api: '/diandi_place/landlord'
}
export const rowKey = ''