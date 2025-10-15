export const form = {"blocs":{"type":"cascader-store","label":"选择公司"},"name":{"type":"input","label":"分类名称"},"sort":{"type":"number","label":"排序值"},"identifies":{"type":"input","label":"插件标识集合"},"created_at":{"type":"datetime","label":"创建时间"},"updated_at":{"type":"datetime","label":"更新时间"}}

export const order = ["name","sort","identifies","created_at","updated_at"];

export const tableColumns = [{"label":"ID","prop":"id"},{"label":"分类名称","prop":"name"},{"label":"排序值","prop":"sort"},{"label":"插件标识集合","prop":"identifies"},{"label":"创建时间","prop":"created_at"},{"label":"更新时间","prop":"updated_at"},{"label":"操作","prop":"action","width":"200","slot":"action"}]

export const filterInfo = {
    fieldList: [{"label":"更新时间","type":"input","value":"DiandiAuthAddonsCate[updated_at]"}],

}

export const path = {
    index: '',
    update: '',
    create: '',
    api: '/diandi_auth/cloud/addonscate'
}

export const rowKey = 'id'