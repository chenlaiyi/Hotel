export const form = {"blocs":{"type":"cascader-store","label":"选择公司"},"mid":{"type":"number","label":"系统模块ID"},"is_nav":{"type":"number","label":"是否导航"},"identifie":{"type":"input","label":"英文标识"},"type":{"type":"input","label":"模块类型"},"title":{"type":"input","label":"名称"},"version":{"type":"input","label":"版本"},"ability":{"type":"input","label":"简介"},"description":{"type":"input","label":"描述"},"author":{"type":"input","label":"作者"},"url":{"type":"input","label":"社区地址"},"settings":{"type":"input","label":"配置"},"logo":{"type":"input","label":"logo"},"versions":{"type":"input","label":"适应的软件版本"},"is_install":{"type":"input","label":"Is Install"},"parent_mids":{"type":"input","label":"Parent Mids"},"cate_id":{"type":"number","label":"分类ID"},"applets":{"type":"input","label":"小程序二维码"}}

export const order = ["mid","is_nav","identifie","type","title","version","ability","description","author","url","settings","logo","versions","is_install","parent_mids","cate_id","applets"];

export const tableColumns = [{"label":"模块id","prop":"id"},{"label":"系统模块ID","prop":"mid"},{"label":"是否导航","prop":"is_nav"},{"label":"英文标识","prop":"identifie"},{"label":"模块类型","prop":"type"},{"label":"名称","prop":"title"},{"label":"版本","prop":"version"},{"label":"简介","prop":"ability"},{"label":"描述","prop":"description"},{"label":"作者","prop":"author"},{"label":"社区地址","prop":"url"},{"label":"配置","prop":"settings"},{"label":"logo","prop":"logo"},{"label":"适应的软件版本","prop":"versions"},{"label":"Is Install","prop":"is_install"},{"label":"Parent Mids","prop":"parent_mids"},{"label":"分类ID","prop":"cate_id"},{"label":"小程序二维码","prop":"applets"},{"label":"操作","prop":"action","width":"200","slot":"action"}]

export const filterInfo = {
    fieldList: [{"label":"小程序二维码","type":"input","value":"DiandiAuthAddons[applets]"}],

}

export const path = {
    index: '',
    update: '',
    create: '',
    api: '/diandi_auth/cloud/addons'
}

export const rowKey = 'id'