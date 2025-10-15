/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-11-12 11:32:10
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-02-19 00:48:56
 */
export const form = {
  blocs: {
    type: 'cascader-store',
    label: '选择楼栋'
  },
  name: {
    type: 'input',
    label: '卡券名'
  },
  type: {
    type: 'radio',
    label: '卡券类型',
    isOptions: true,
    options: [
      {
        text: '代金券',
        value: 1
      },
      {
        text: '时长卡',
        value: 2
      },
      {
        text: '次卡',
        value: 3
      },
      {
        text: '折扣券',
        value: 4
      },
      {
        text: '体验券',
        value: 5
      }
    ]
  },
  explain: {
    type: 'input',
    label: '卡券说明'
  },
  price: {
    type: 'input',
    label: '卡券金额(元)',
    attrs: {
      type: 'number'
    }
  },
  use_start: {
    type: 'time',
    label: '时间限制-开始时间',
    attrs: {
      pickerOptions: {
        start: '00:30',
        step: '00:30',
        end: '24:00'
      }
    }
  },
  use_end: {
    type: 'time',
    label: '时间限制-结束时间',
    attrs: {
      pickerOptions: {
        start: '00:30',
        step: '00:30',
        end: '24:00'
      }
    }
  },
  enable_start: {
    type: 'datetime',
    label: '有效期开始时间',
    attrs: {
      valueFormat: 'yyyy-MM-dd HH:mm:ss'
    }
  },
  background: {
    label: '卡券背景',
    type: 'image-uploader'// 只需要在这里指定为 image-uploader 即可
  },
  enable_end: {
    type: 'datetime',
    label: '有效期结束时间',
    attrs: {
      valueFormat: 'yyyy-MM-dd HH:mm:ss'
    }
  },
  use_num: {
    type: 'input',
    label: '使用次数',
    attrs: {
      type: 'number'
    }
  },
  max_time: {
    type: 'input',
    label: '消费时长(小时)',
    attrs: {
      type: 'number'
    }
  },
  enable_week: {
    type: 'select',
    label: '适用星期',
    isOptions: true,
    options: [
      {
        text: '星期一',
        value: 1
      },
      {
        text: '星期二',
        value: 2
      },
      {
        text: '星期三',
        value: 3
      },
      {
        text: '星期四',
        value: 4
      },
      {
        text: '星期五',
        value: 5
      },
      {
        text: '星期六',
        value: 6
      },
      {
        text: '星期七',
        value: 7
      }
    ]
  },
  third_party: {
    type: 'input',
    label: '第三方编号'
  },
  all_num: {
    type: 'input',
    label: '总发放量',
    attrs: {
      type: 'number'
    }
  },
  max_num: {
    type: 'input',
    label: '最多可购买数量',
    attrs: {
      type: 'number'
    }
  },
  discount: {
    type: 'input',
    label: ' 折扣券折扣',
    attrs: {
      type: 'number'
    }
  },
  cash: {
    type: 'input',
    label: '代金券金额',
    attrs: {
      type: 'number'
    }
  }
}

const modelSearchName = []

export const order = ['blocs', 'name',
  'type',
  'explain',
  'price',
  'background',
  'use_start',
  'use_end',
  'enable_start',
  'enable_end',
  'use_num',
  'max_time',
  'enable_week',
  'third_party',
  'all_num',
  'max_num',
  'discount',
  'cash'
]

export const formAccount = {
  blocs: {
    type: 'cascader-store',
    label: '选择楼栋'
  },
  name: {
    type: 'input',
    label: '卡券名'
  },
  type: {
    type: 'radio',
    label: '卡券类型',
    isOptions: true,
    options: [
      {
        text: '代金券',
        value: 1
      },
      {
        text: '时长卡',
        value: 2
      },
      {
        text: '次卡',
        value: 3
      },
      {
        text: '折扣券',
        value: 4
      },
      {
        text: '体验券',
        value: 5
      }
    ]
  },
  explain: {
    type: 'input',
    label: '卡券说明'
  },
  price: {
    type: 'input',
    label: '卡券金额(元)',
    attrs: {
      type: 'number'
    }
  },
  use_start: {
    type: 'time',
    label: '时间限制-开始时间',
    attrs: {
      pickerOptions: {
        start: '00:30',
        step: '00:30',
        end: '24:00'
      }
    }
  },
  use_end: {
    type: 'time',
    label: '时间限制-结束时间',
    attrs: {
      pickerOptions: {
        start: '00:30',
        step: '00:30',
        end: '24:00'
      }
    }
  },
  enable_start: {
    type: 'datetime',
    label: '有效期开始时间',
    attrs: {
      valueFormat: 'yyyy-MM-dd HH:mm:ss'
    }
  },
  background: {
    label: '卡券背景',
    type: 'image-uploader'// 只需要在这里指定为 image-uploader 即可
  },
  enable_end: {
    type: 'datetime',
    label: '有效期结束时间',
    attrs: {
      valueFormat: 'yyyy-MM-dd HH:mm:ss'
    }
  },
  use_num: {
    type: 'input',
    label: '使用次数',
    attrs: {
      type: 'number'
    }
  },
  max_time: {
    type: 'input',
    label: '消费时长(小时)',
    attrs: {
      type: 'number'
    }
  },
  enable_week: {
    type: 'select',
    label: '适用星期',
    isOptions: true,
    options: [
      {
        text: '星期一',
        value: 1
      },
      {
        text: '星期二',
        value: 2
      },
      {
        text: '星期三',
        value: 3
      },
      {
        text: '星期四',
        value: 4
      },
      {
        text: '星期五',
        value: 5
      },
      {
        text: '星期六',
        value: 6
      },
      {
        text: '星期七',
        value: 7
      }
    ]
  },
  third_party: {
    type: 'input',
    label: '第三方编号'
  },
  all_num: {
    type: 'input',
    label: '总发放量',
    attrs: {
      type: 'number'
    }
  },
  max_num: {
    type: 'input',
    label: '最多可购买数量',
    attrs: {
      type: 'number'
    }
  },
  discount: {
    type: 'input',
    label: ' 折扣券折扣',
    attrs: {
      type: 'number'
    }
  },
  cash: {
    type: 'input',
    label: '代金券金额',
    attrs: {
      type: 'number'
    }
  }
}

export const orderAccount = ['blocs', 'name',
  'type',
  'explain',
  'price',
  'background',
  'use_start',
  'use_end',
  'enable_start',
  'enable_end',
  'use_num',
  'max_time',
  'enable_week',
  'third_party',
  'all_num',
  'max_num',
  'discount',
  'cash'
]

export const formPassword = {
  blocs: {
    type: 'cascader-store',
    label: '选择楼栋'
  },
  name: {
    type: 'input',
    label: '卡券名'
  },
  type: {
    type: 'radio',
    label: '卡券类型',
    isOptions: true,
    options: [
      {
        text: '代金券',
        value: 1
      },
      {
        text: '时长卡',
        value: 2
      },
      {
        text: '次卡',
        value: 3
      },
      {
        text: '折扣券',
        value: 4
      },
      {
        text: '体验券',
        value: 5
      }
    ]
  },
  explain: {
    type: 'input',
    label: '卡券说明'
  },
  price: {
    type: 'input',
    label: '卡券金额(元)',
    attrs: {
      type: 'number'
    }
  },
  use_start: {
    type: 'time',
    label: '时间限制-开始时间',
    attrs: {
      pickerOptions: {
        start: '00:30',
        step: '00:30',
        end: '24:00'
      }
    }
  },
  use_end: {
    type: 'time',
    label: '时间限制-结束时间',
    attrs: {
      pickerOptions: {
        start: '00:30',
        step: '00:30',
        end: '24:00'
      }
    }
  },
  enable_start: {
    type: 'datetime',
    label: '有效期开始时间',
    attrs: {
      valueFormat: 'yyyy-MM-dd HH:mm:ss'
    }
  },
  background: {
    label: '卡券背景',
    type: 'image-uploader'// 只需要在这里指定为 image-uploader 即可
  },
  enable_end: {
    type: 'datetime',
    label: '有效期结束时间',
    attrs: {
      valueFormat: 'yyyy-MM-dd HH:mm:ss'
    }
  },
  use_num: {
    type: 'input',
    label: '使用次数',
    attrs: {
      type: 'number'
    }
  },
  max_time: {
    type: 'input',
    label: '消费时长(小时)',
    attrs: {
      type: 'number'
    }
  },
  enable_week: {
    type: 'select',
    label: '适用星期',
    isOptions: true,
    options: [
      {
        text: '星期一',
        value: 1
      },
      {
        text: '星期二',
        value: 2
      },
      {
        text: '星期三',
        value: 3
      },
      {
        text: '星期四',
        value: 4
      },
      {
        text: '星期五',
        value: 5
      },
      {
        text: '星期六',
        value: 6
      },
      {
        text: '星期七',
        value: 7
      }
    ]
  },
  third_party: {
    type: 'input',
    label: '第三方编号'
  },
  all_num: {
    type: 'input',
    label: '总发放量',
    attrs: {
      type: 'number'
    }
  },
  max_num: {
    type: 'input',
    label: '最多可购买数量',
    attrs: {
      type: 'number'
    }
  },
  discount: {
    type: 'input',
    label: ' 折扣券折扣',
    attrs: {
      type: 'number'
    }
  },
  cash: {
    type: 'input',
    label: '代金券金额',
    attrs: {
      type: 'number'
    }
  }
}

export const orderPassword = ['blocs', 'name',
  'type',
  'explain',
  'price',
  'background',
  'use_start',
  'use_end',
  'enable_start',
  'enable_end',
  'use_num',
  'max_time',
  'enable_week',
  'third_party',
  'all_num',
  'max_num',
  'discount',
  'cash'
]

