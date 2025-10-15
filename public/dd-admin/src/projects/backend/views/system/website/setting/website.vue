<template>
  <div class="app-container">
    <ele-form
      v-model="formData"
      v-bind="formConfig"
      :request-fn="handleRequest"
      :is-show-back-btn="false"
      @request-success="handleRequestSuccess"
    />
  </div>
</template>

<script>
import { setConfig, getConfig } from '@projectName/views/system/api/system/website'
export default {
  data() {
    return {
      formData: {
        'name': '',
        'intro': '',
        'keywords': '',
        'description': '',
        'icp': '',
        'location': '',
        'footerleft': '',
        'footerright': '',
        'statcode': '',
        'develop_status': 0,
        'is_send_code': 0,
        'site_status': 0,
        'reason': ''
      },
      formConfig: {
        formDesc: {
          'name': {
            type: 'input',
            label: '站点名称'
          },
          'keywords': {
            type: 'input',
            label: '站点关键词'
          },
          'intro': {
            type: 'input',
            label: '站点描述'
          },
          'description': {
            type: 'textarea',
            label: '站点介绍',
            attrs: {
              autosizeType: 'switch',
              autosize: false
            }
          },
          'icp': {
            type: 'input',
            label: 'ICP备案'
          },
          'location': {
            type: 'input',
            label: '备案地址'
          },
          'footerleft': {
            type: 'input',
            label: '底部左侧'
          },
          'footerright': {
            type: 'input',
            label: '底部右侧'
          },
          'statcode': {
            type: 'textarea',
            label: '第三方统计代码',
            attrs: {
              autosizeType: 'switch',
              autosize: false
            }
          },
          'site_status': {
            type: 'radio',
            label: '关闭站点',
            isOptions: true,
            options: [
              {
                text: '是',
                value: '1'
              },
              {
                text: '否',
                value: '0'
              }
            ]
          },
          'register_type': {
            type: 'radio',
            label: '运营方式',
            isOptions: true,
            options: [
              {
                text: 'saas模式',
                value: '1'
              },
              {
                text: '平台模式',
                value: '0'
              }
            ]
          },
          'develop_status': {
            type: 'radio',
            label: '调试',
            isOptions: true,
            options: [
              {
                text: '是',
                value: '1'
              },
              {
                text: '否',
                value: '0'
              }
            ]
          },
          'reason': {
            type: 'textarea',
            label: '关闭站点的原因',
            attrs: {
              autosizeType: 'switch',
              autosize: false
            }
          },
          'is_send_code': {
            type: 'radio',
            label: '短信验证',
            isOptions: true,
            options: [
              {
                text: '是',
                value: '1'
              },
              {
                text: '否',
                value: '0'
              }
            ]
          },
          'qcloud_sdk_app_id': {
            type: 'input',
            label: '腾讯短信应用SDK AppID',
            isOptions: true,
            vif: (data) => data.is_send_code === '1',
            // 这里必须制定 optionsLinkageFields 做为关联字段，当次字段值发生变化时，会重新出发请求
            optionsLinkageFields: ['is_send_code']
          },
          'qcloud_secret_id': {
            type: 'input',
            label: '腾讯secret id',
            isOptions: true,
            vif: (data) => data.is_send_code === '1',
            // 这里必须制定 optionsLinkageFields 做为关联字段，当次字段值发生变化时，会重新出发请求
            optionsLinkageFields: ['is_send_code']
          },
          'qcloud_secret_key': {
            type: 'input',
            label: '腾讯secret key',
            isOptions: true,
            vif: (data) => data.is_send_code === '1',
            // 这里必须制定 optionsLinkageFields 做为关联字段，当次字段值发生变化时，会重新出发请求
            optionsLinkageFields: ['is_send_code']
          },
          'qcloud_sign_name': {
            type: 'input',
            label: '腾讯签名',
            isOptions: true,
            vif: (data) => data.is_send_code === '1',
            // 这里必须制定 optionsLinkageFields 做为关联字段，当次字段值发生变化时，会重新出发请求
            optionsLinkageFields: ['is_send_code']
          },
          'qcloud_template_code': {
            type: 'input',
            label: '腾讯模板code',
            isOptions: true,
            vif: (data) => data.is_send_code === '1',
            // 这里必须制定 optionsLinkageFields 做为关联字段，当次字段值发生变化时，会重新出发请求
            optionsLinkageFields: ['is_send_code']
          },
          'aliyun_access_key_id': {
            type: 'input',
            label: '阿里AccessKey ID',
            isOptions: true,
            vif: (data) => data.is_send_code === '1',
            // 这里必须制定 optionsLinkageFields 做为关联字段，当次字段值发生变化时，会重新出发请求
            optionsLinkageFields: ['is_send_code']
          },
          'aliyun_access_key_secret': {
            type: 'input',
            label: '阿里Access Key Secret',
            isOptions: true,
            vif: (data) => data.is_send_code === '1',
            // 这里必须制定 optionsLinkageFields 做为关联字段，当次字段值发生变化时，会重新出发请求
            optionsLinkageFields: ['is_send_code']
          },
          'aliyun_sign_name': {
            type: 'input',
            label: '阿里签名',
            isOptions: true,
            vif: (data) => data.is_send_code === '1',
            // 这里必须制定 optionsLinkageFields 做为关联字段，当次字段值发生变化时，会重新出发请求
            optionsLinkageFields: ['is_send_code']
          },
          'aliyun_template_code': {
            type: 'input',
            label: '阿里模板code',
            isOptions: true,
            vif: (data) => data.is_send_code === '1',
            // 这里必须制定 optionsLinkageFields 做为关联字段，当次字段值发生变化时，会重新出发请求
            optionsLinkageFields: ['is_send_code']
          },
          flogo: {
            label: '前台logo',
            type: 'image-uploader' // 只需要在这里指定为 image-uploader 即可
          },
          blogo: {
            label: '后台logo',
            type: 'image-uploader' // 只需要在这里指定为 image-uploader 即可
          },
          loginbg: {
            label: '登录页背景',
            type: 'image-uploader' // 只需要在这里指定为 image-uploader 即可
          }
        },
        order: [
          'name',
          'intro',
          'keywords',
          'description',
          'flogo',
          'blogo',
          'icp',
          'location',
          'footerleft',
          'footerright',
          'statcode',
          'develop_status',
          'is_send_code',
          'qcloud_sdk_app_id',
          'qcloud_secret_id',
          'qcloud_secret_key',
          'qcloud_sign_name',
          'qcloud_template_code',
          'aliyun_access_key_id',
          'aliyun_access_key_secret',
          'aliyun_sign_name',
          'aliyun_template_code',
          'site_status',
          'reason'

        ]
      }
    }
  },
  created() {
    this.getConfigView()
  },
  methods: {
    getConfigView() {
      getConfig({}).then(res => {
        if (res.code === 200) {
          this.formData = res.data
          console.log(res)
        }
      })
    },
    handleRequest(data) {
      setConfig({ Website: data }).then(res => {
        if (res.code === 200) {
          this.$message.success('设置成功')
        }
      })
      return Promise.resolve()
    },
    handleRequestSuccess() {
    }
  }
}
</script>
