<template>
  <div class="app-container">
    <ele-form
      v-model="formData"
      v-bind="formConfig"
      :request-fn="handleRequest"
      :is-show-back-btn="false"
      @request-success="handleRequestSuccess"
    >
      <template v-if="is_send_code === 1" v-slot:form-footer>
        <el-col :span="24">
          <el-form-item label="验证码">
            <el-input
              v-model.trim="formData.code"
              placeholder="请输入验证码"
            >
              <template slot="append">
                <el-button
                  type="primary"
                  size="mini"
                  @click="getcode"
                >获取验证码</el-button>
              </template>
            </el-input>
          </el-form-item>
        </el-col>
      </template>
    </ele-form>
  </div>
</template>

<script>
import {
  getcode,
  setpassword,
  getuserinfo
} from '@projectName/api/profile/profile.js'
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
      is_send_code: 0,
      formData: {
        code: '',
        mobile: '',
        password_reset_token: ''
      },
      formConfig: {
        formSpan: 24,
        labelPosition: 'top',
        formDesc: {
          mobile: {
            type: 'input',
            label: '手机号',
            attrs: {
              disabled: true
            }
          },
          newpassword: {
            type: 'input',
            label: '密码'
          }
        },
        order: ['mobile', 'newpassword']
      }
    }
  },
  watch: {
    userinfo(val) {
      const that = this
      that.formData.mobile = val.mobile
      that.formData.password_reset_token = val.password_reset_token
    }
  },
  created() {
    this.getConfigWebsite()
  },
  methods: {
    getConfigWebsite() {
      getConfig({}).then((res) => {
        if (res.code === 200) {
          this.is_send_code = Number(res.data.is_send_code)
        }
      })
    },
    // 获取验证码
    getcode() {
      const that = this
      if (!that.formData.mobile) {
        that.$message({
          message: '请输入手机号码',
          type: 'warning'
        })
        return false
      }
      const data = {
        mobile: that.formData.mobile,
        type: 'forgetpass'
      }
      getcode(data).then((res) => {
        that.$message({
          message: res.message,
          type: 'success'
        })
      })
    },
    handleRequest(data) {
      const that = this
      console.log(that.userinfo)
      setpassword(data).then((res) => {
        that.$message({
          message: res.message,
          type: 'success'
        })
      })
    },
    handleRequestSuccess() {}
  }
}
</script>
