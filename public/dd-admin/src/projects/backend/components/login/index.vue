<template>
  <el-container :style="{'background-image':'url('+loginbg+')','padding':0}">
    <el-main style="padding: 0px;height: calc(100vh - 100px);">
      <div class="login-containerw">
        <div class="login-containerw-row">
          <el-row type="flex" justify="center" :gutter="20">
            <el-col :lg="12" :md="12" :xs="0">
              <div class="left_msg">
                <div class="left_msg_top">{{ website.name }}</div>
                <div class="left_line" />
                <div class="left_msg_msg">{{ website.description }}</div>
                <div class="left_msg_msg" />
              </div>
            </el-col>

            <el-col :lg="12" :md="12" :xs="24">
              <div class="login-form">
                <div :class="device === 'mobile' ? 'login-mobile' : 'login-right'">
                  <div class="title-container">
                    <!-- <img src="@/static/img/logo1.png" alt=""> -->
                    <span class="title">{{ website.name }}</span>
                  </div>
                  <div class="login-form-item">
                    <div class="login-qrcode-btn" @click="showQrcodeClick">
                      <svg-icon
                        class="icon sub-el-icon"
                        icon-class="qrcode-login"
                        color="#fff"
                        :size="42"
                      />
                    </div>
                    <Qcord v-if="showQrcode" :show-qrcode="showQrcode" />
                    <account-login v-else class="margin-top-sm" />
                  </div>
                  <el-row v-if="isRegister" :gutter="20" class="padding-lr-sm">
                    <el-col :span="12" :offset="0">
                      <el-button
                        type="text"
                        style="color: #fff; font-size: 16px"
                        @click.native.prevent="handleRegister"
                      >免费注册</el-button>
                    </el-col>
                    <el-col :span="12" :offset="0" class="text-right">
                      <el-button
                        type="text"
                        style="color: #fff; font-size: 16px"
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
import AccountLogin from './components/AccountLogin.vue'
import Qcord from './components/Qcord'
import { mapGetters } from 'vuex'
import projects from '@projectName/utils/projects.js'
export default {
  name: 'Login',
  components: {
    AccountLogin,
    Qcord
  },
  mixins: [projects],
  data() {
    return {
      loginbg: '',
      loginFormType: 'AccountLogin',
      showDialog: false,
      is_send_code: false,
      website: {
        name: '',
        icp: '',
        footerleft: '',
        footerright: ''
      },
      showQrcode: false
    }
  },
  computed: {
    ...mapGetters(['size', 'device', 'isRegister'])
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
    console.log(this.$route)
    this.getConfigWebsite()
  },
  methods: {
    showQrcodeClick() {
      this.showQrcode = !this.showQrcode
      console.log(this.showQrcode)
    },
    getConfigWebsite() {
      this.getConfig({ isImg: 1, 'url': window.location.host }).then((res) => {
        if (res.code === 200) {
          this.is_send_code = Number(res.data.is_send_code)
          this.website = res.data
          this.loginbg = res.data.loginbg
        }
      })
    },
    checkCapslock(e) {
      const { key } = e
      this.capsTooltip = key && key.length === 1 && key >= 'A' && key <= 'Z'
    },
    handleTypeClick(tab, event) {
      console.log(tab, event, this.loginFormType)
    },
    qcordLog() {
      console.log('login')
      this.$router.push({ path: '/qcord' })
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
      this.$store.dispatch('settings/setMenuType', data.module_name)
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
  }
}
</script>
<style lang="scss" scoped>
@import "./style/login.scss";

.svg_st {
  display: flex;

  .svg-icon {
    margin-left: 14px;
    transform: translateY(-1px) translateX(7px);
  }

  .icon {
    transform: translateY(2px) translateX(3px);
  }
}

.el-tabs.el-tabs--top {
  height: 414px;
}
</style>
