<template>

  <el-container :style="{'background-image':'url('+loginbg+')','padding':0}">
    <el-main style="padding: 0px;height: calc(100vh - 100px);">
      <div class="login-containerw">
        <div class="login-containerw-row">
          <el-row type="flex" justify="center" :gutter="20">
            <el-col :lg="12" :md="12" :xs="0">
              <div class="login-left" />
            </el-col>

            <el-col :lg="12" :md="12" :xs="24">
              <div class="login-form">
                <div :class="device === 'mobile' ? 'login-mobile' : 'login-right'">
                  <!-- <div class="title-container">
              <h3 class="title">{{ sitaName }}</h3>
            </div> -->
                  <div class="margin-top-xl login-form-item">
                    <register />
                  </div>
                  <el-row :gutter="20" class="padding-lr-sm">
                    <el-col :span="12" :offset="0">
                      <el-button
                        type="text"
                        style="color: #fff; font-size: 16px"
                        class="text-white"
                        @click.native.prevent="handleLogin"
                      >已有账户</el-button>
                    </el-col>
                    <el-col :span="12" :offset="0" class="text-right">
                      <el-button
                        type="text"
                        style="color: #fff; font-size: 16px"
                        class="text-white"
                        @click.native.prevent="handleForget"
                      >忘记密码</el-button>
                    </el-col>
                  </el-row>
                </div>
              </div>
            </el-col>
          </el-row>
        </div>
      </div>
    </el-main>
    <el-footer style="padding: 0">
      <fier-footer background-color="#fff" :name="website.name" :icp="website.icp" :footerleft="website.footerleft" :footerright="website.footerright" />
    </el-footer>
  </el-container>
</template>

<script>
import projects from '@projectName/utils/projects'
import Register from './components/Register'
import { mapGetters } from 'vuex'

export default {
  name: 'Login',
  components: {
    Register
  },
  mixins: [projects],
  data() {
    return {
      loginbg: '',
      loginFormType: 'AccountLogin',
      showDialog: false,
      website: {}
    }
  },
  computed: {
    ...mapGetters(['size', 'device'])
  },
  watch: {
    $route: {
      handler: function(route) {
        const query = route.query
        if (query) {
          this.redirect = query.redirect
          this.otherQuery = this.getOtherQuery(query)
        }
      },
      immediate: true
    }
  },
  created() {
    this.getConfigWebsite()
    // window.addEventListener('storage', this.afterQRScan)
  },
  destroyed() {
    // window.removeEventListener('storage', this.afterQRScan)
  },
  methods: {
    getConfigWebsite() {
      this.getConfig({ isImg: 1, 'url': window.location.host }).then((res) => {
        if (res.code === 200) {
          this.is_send_code = Number(res.data.is_send_code)
          this.website = res.data
          this.loginbg = res.data.loginbg
          console.log('getConfigWebsite', res)
        }
      })
    },
    checkCapslock(e) {
      const { key } = e
      this.capsTooltip = key && key.length === 1 && key >= 'A' && key <= 'Z'
    },
    handleLogin() {
      this.$router.push({ path: '/login' })
    },
    handleTypeClick(tab, event) {
      // if (this.loginFormType === 'AccountLogin') {
      // }
      console.log(tab, event, this.loginFormType)
    },
    handleRegister() {
      this.$router.push({ path: '/register' })
    },
    handleForget() {
      this.$router.push({ path: '/forget' })
    },
    getOtherQuery(query) {
      return Object.keys(query).reduce((acc, cur) => {
        if (cur !== 'redirect') {
          acc[cur] = query[cur]
        }
        return acc
      }, {})
    },
    initStoreList: function(data) {
      const that = this
      console.log('登录成功initStoreList')
      this.$store.dispatch('permission/setMenuType', data.module_name)
      if (data.store_id) {
        this.getView(data.store_id).then((res) => {
          that.$store.dispatch('app/setBlocs', res.data)
          that.$store.dispatch('elForm/changeSetting', {
            key: 'attachmentUrl',
            value: res.data.config.attachmentUrl
          })
        })
      }
    }
    // afterQRScan() {
    //   if (e.key === 'x-admin-oauth-code') {
    //     const code = getQueryObject(e.newValue)
    //     const codeMap = {
    //       wechat: 'code',
    //       tencent: 'code'
    //     }
    //     const type = codeMap[this.auth_type]
    //     const codeName = code[type]
    //     if (codeName) {
    //       this.$store.dispatch('LoginByThirdparty', codeName).then(() => {
    //         this.$router.push({ path: this.redirect || '/' })
    //       })
    //     } else {
    //       alert('第三方登录失败')
    //     }
    //   }
    // }
  }
}
</script>
<style lang="scss" scoped>
@import "./style/login.scss";
</style>
