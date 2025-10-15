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
import { edituserinfo } from '@projectName/api/profile/profile.js'
export default {
  props: {
    userinfo: {
      type: Object,
      default: () => {
        return {}
      }
    }
  },
  data() {
    return {
      formData: {
        username: '',
        mobile: '',
        email: '',
        avatar: ''
      },
      formConfig: {
        formSpan: 24,
        labelPosition: 'top',
        formDesc: {
          username: {
            type: 'input',
            label: '用户名'
          },
          mobile: {
            type: 'input',
            label: '手机号'
          },
          email: {
            type: 'input',
            label: '邮箱地址'
          },
          avatar: {
            label: '照片',
            type: 'image-uploader' // 只需要在这里指定为 image-uploader 即可
          }
        },
        order: ['username', 'avatar', 'mobile', 'email']
      }
    }
  },
  watch: {
    userinfo(val) {
      const that = this
      that.formData.username = that.userinfo.username
      that.formData.mobile = that.userinfo.mobile
      that.formData.email = that.userinfo.email
      that.formData.avatar = that.userinfo.avatar
    }
  },
  methods: {
    handleRequest(data) {
      edituserinfo(data).then((response) => {
        if (response.code === 200) {
          this.$message.success(response.message)
        } else {
          this.$message.error(response.message)
        }
      })
      return Promise.resolve()
    },
    handleRequestSuccess() {}
  }
}
</script>
