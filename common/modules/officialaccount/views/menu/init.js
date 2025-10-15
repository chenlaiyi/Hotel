export const form = {"blocs":{"type":"cascader-store","label":"选择公司"},"id":{"type":"number","label":"粉丝id"},"menuName":{"type":"input","label":"菜单名称"},"parentId":{"type":"number","label":"父级id"},"menuLevel":{"type":"number","label":"菜单等级"},"msgType":{"type":"number","label":"消息类型"},"menuType":{"type":"number","label":"菜单类型"},"menuUrl":{"type":"input","label":"菜单URL"},"menuSort":{"type":"number","label":"菜单排序"},"appid":{"type":"input","label":"小程序appid"},"pagepath":{"type":"input","label":"小程序页面路径"},"media_id":{"type":"number","label":"素材ID"}}

export const order = ["id","bloc_id","store_id","update_time","create_time","menuName","parentId","menuLevel","msgType","menuType","menuUrl","menuSort","appid","pagepath","media_id"];

export const tableColumns = [{"label":"粉丝id","prop":"id"},{"label":"Bloc ID","prop":"bloc_id"},{"label":"Store ID","prop":"store_id"},{"label":"更新时间","prop":"update_time"},{"label":"Create Time","prop":"create_time"},{"label":"菜单名称","prop":"menuName"},{"label":"父级id","prop":"parentId"},{"label":"菜单等级","prop":"menuLevel"},{"label":"消息类型","prop":"msgType"},{"label":"菜单类型","prop":"menuType"},{"label":"菜单URL","prop":"menuUrl"},{"label":"菜单排序","prop":"menuSort"},{"label":"小程序appid","prop":"appid"},{"label":"小程序页面路径","prop":"pagepath"},{"label":"素材ID","prop":"media_id"}]

export const filterInfo = {
    fieldList: {"label":"素材ID","type":"input","value":"OfficialaccountWechatMenu[media_id]"},

}

export const path = {
    index: '',
    update: '',
    create: '',
    api: '/officialaccount/menu'
}

export const rowKey = ''