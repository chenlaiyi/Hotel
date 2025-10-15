/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2024-01-28 13:50:17
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-03-14 10:20:23
 */
export const form = {
  'blocs': {
    'type': 'cascader-store',
    'label': '选择公司'
  },
  'flogo': {
    'label': '前台logo',
    'type': 'image-uploader'
  },
  'blogo': {
    'label': '后台logo',
    'type': 'image-uploader'
  },
  'loginbg': {
    'label': '登录页背景',
    'type': 'image-uploader'
  },
  'domain_url': {
    'type': 'input',
    'label': '域名'
  },
  'name': {
    'type': 'input',
    'label': '站点名称'
  },
  'intro': {
    'type': 'textarea',
    'label': '站点介绍'
  },
  'keywords': {
    'type': 'input',
    'label': '站点检索词'
  },
  'description': {
    'type': 'textarea',
    'label': '站点描述'
  },
  'footerleft': {
    'type': 'input',
    'label': '底部左侧'
  },
  'footerright': {
    'type': 'input',
    'label': '底部右侧'
  },
  'location': {
    'type': 'input',
    'label': 'Location'
  },
  'icp': {
    'type': 'input',
    'label': '备案信息'
  },
  'mobile': {
    'type': 'input',
    'label': '联系电话'
  },
  'city': {
    'type': 'input',
    'label': '所在城市'
  },
  'company_name': {
    'type': 'input',
    'label': '公司名称'
  },
  'wechat': {
    'type': 'input',
    'label': '微信号'
  },
  'status': {
    type: 'radio',
    label: '状态',
    isOptions: true,
    options: [{
      'text': '申请',
      'value': 0
    },
    {
      'text': '已付款',
      'value': 1
    },
    {
      'text': '已部署',
      'value': 2
    }
    ]
  }
}

export const order = ['blocs', 'flogo', 'blogo', 'domain_url', 'name', 'intro', 'keywords', 'description', 'footerleft', 'footerright', 'location', 'icp', 'mobile', 'city', 'company_name', 'wechat', 'status']

export const tableColumns = [{
  'label': 'ID',
  'prop': 'id'
}, {
  'label': '前台logo',
  'prop': 'flogo',
  'slot': 'flogo'
}, {
  'label': '后台logo',
  'prop': 'blogo',
  'slot': 'blogo'
}, {
  'label': '域名',
  'prop': 'domain_url'
}, {
  'label': '站点名称',
  'prop': 'name'
}, {
  'label': '站点介绍',
  'prop': 'intro'
}, {
  'label': '站点检索词',
  'prop': 'keywords'
}, {
  'label': '站点描述',
  'prop': 'description'
}, {
  'label': '底部左侧',
  'prop': 'footerleft'
}, {
  'label': '底部右侧',
  'prop': 'footerright'
}, {
  'label': 'Location',
  'prop': 'location'
}, {
  'label': '备案信息',
  'prop': 'icp'
}, {
  'label': 'Create Time',
  'prop': 'create_time'
}, {
  'label': '联系电话',
  'prop': 'mobile'
}, {
  'label': '所在城市',
  'prop': 'city'
}, {
  'label': '公司名称',
  'prop': 'company_name'
}, {
  'label': '微信号',
  'prop': 'wechat'
}, {
  'label': '0申请，1付款，2已部署',
  'prop': 'status'
}, {
  'label': 'Bloc ID',
  'prop': 'bloc_id'
}, {
  'label': 'Store ID',
  'prop': 'store_id'
}, {
  'label': '操作',
  'width': 150,
  'prop': 'action',
  'slot': 'action'
}]

export const filterInfo = {
  fieldList: {
    'label': 'Store ID',
    'type': 'input',
    'value': 'WebsiteStationGroup[store_id]'
  }

}

export const path = {
  index: 'system-website-station-index',
  update: 'system-website-station-update',
  create: 'system-website-station-create',
  api: '/website/station'
}

export const rowKey = 'id'
