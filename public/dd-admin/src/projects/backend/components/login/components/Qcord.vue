<template>
  <div class="qcord">
    <!-- <div class="qcord-login text-pointer" @click="qcordLog">用户登录</div> -->
    <!-- <div class="qcord-img">微信扫码登录</div> -->
    <div id="login_container" class="qcord-border">
      <el-image
        style="width: 200px; height: 200px"
        :src="qrcodeUrl"
      >
        <div slot="placeholder" class="image-slot">
          生成中<span class="dot">...</span>
        </div>
        <div slot="error" class="image-slot" @click="get_wx_qrcode">
          <i class="el-icon-refresh" style="font-size: xxx-large;" />
          <span>二维码已失效</span>
        </div>
      </el-image>
      <div class="qrcode-spec">微信扫一扫</div>
    </div>
    <!-- <div class="back-qcord" @click="handleRegister">微信扫码登录点滴云</div> -->
    <!-- <div class="back-reg" @click="handleRegister">没有账号，立即注册</div> -->
  </div>
</template>
<script>
import projects from '@projectName/utils/projects'
export default {
  name: 'Qcord',
  mixins: [projects],
  props: {
    showQrcode: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      qrcodeUrl: '',
      ticket: '',
      otherQuery: {},
      count: 0,
      checkQrcodeTime: null
    }
  },
  watch: {
    showQrcode(val) {
      if (val) {
        this.get_wx_qrcode()
      } else {
        clearInterval(this.checkQrcodeTime)
        this.qrcodeUrl = null
      }
    },
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
    this.get_wx_qrcode()
  },
  beforeDestroy() {
    clearInterval(this.checkQrcodeTime)
  },
  methods: {
    getOtherQuery(query) {
      return Object.keys(query).reduce((acc, cur) => {
        if (cur !== 'redirect') {
          acc[cur] = query[cur]
        }
        return acc
      }, {})
    },
    get_wx_qrcode() {
      this.getQrcode({
        'time_type': 0,
        'scene': 'login',
        'expireSeconds': 7200
      }).then(res => {
        console.log(res)
        this.qrcodeUrl = res.data.url
        this.ticket = res.data.ticket
        this.count = 0
        this.checkQrcodeLogin()
      })
    },
    checkQrcodeLogin() {
      const that = this
      that.checkQrcodeTime = setInterval(async() => {
        const response = await this.checkLogin({
          ticket: this.ticket
        })
        that.count++
        if (that.count > (60 * 1000) / 1500) {
          clearInterval(that.checkQrcodeTime)
          this.qrcodeUrl = null
        }
        console.log('登录处理', that.count, (60 * 1000) / 1500)
        if (response.data.res === 'success') {
          console.log('用户', response.data.userinfo)
          await that.$store.dispatch('user/autoLogin', response.data.userinfo)
          clearInterval(that.checkQrcodeTime)
          console.log('登录+跳转', response.data)
          let redirectPath = '/dashboard'
          if (that.redirect === '/' && !response.data.userinfo.addons) {
            console.log('情况1')
            that.$store.dispatch('settings/setMenuType', 'system')
            redirectPath = '/dashboard'
          } else if ((that.redirect === '/' || that.redirect === '/dashboard') && response.data.addons) {
            console.log('情况1')
            redirectPath = `/${response.data.userinfo.addons.module_name}/default/index.vue`
            that.initStoreList(response.data.userinfo.addons)
          } else if (that.redirect === '/ceshi') {
            console.log('情况1')
            that.$store.dispatch('settings/setMenuType', 'system')
            redirectPath = '/dashboard'
          }
          console.log('情况最终', redirectPath)
          that.$router.push({
            path: redirectPath,
            query: that.otherQuery
          })
        }
      }, 1500)
    },
    initStoreList: function(data) {
      const that = this
      this.$store.dispatch('settings/setMenuType', data.module_name)
      if (data.module_info) {
        that.$store.dispatch('settings/setPlugins', data.module_info)
      }
    },
    clearIntervalTime() {
      this.checkQrcodeTime = null
      // this.ticket = ''
      clearInterval(this.checkQrcodeTime)
    },
    handleRegister() {
      this.$router.push({ path: '/register' })
    },
    qcordLog() {
      this.$router.push({ path: '/login' })
    }
  }
}
</script>

<style lang="scss" scoped>
.back-reg {
  text-align: center;
  color: #3e6bd4;
  font-size: 7px;
  /* margin-top: 20px; */
}
.image-slot {
  background: #FFFFFF;
  opacity: 0.5;
  display: grid;
  place-items: center;
  height: 100%;
  width: 100%;
  i {
    height: 0;
    color: rgb(18, 169, 164);
    cursor: pointer;
  }
  span {
    color: rgb(18, 169, 164);
  }
}
.qrcode-spec {
    color: #FFFFFF;
    margin-top: 10px;
  }
.qcord-img {
  font-size: 15px;
  font-weight: bold;
  color: #3e6bd4;
  text-align: center;
  padding-top: 60px;
}
.back-qcord {
  font-size: 9px;
  font-weight: bold;
  color: #afafb4;
  text-align: center;
  margin-top: -40px;
}
.qcord-border {
  /* width: 300px; */
  /* padding: 7px; */
  /* border-top: 1px solid; */
  /* border: 1px solid #e1e1e1; */
  /* margin: 0 auto; */
}
.qcord-login {
  margin-right: 20px;
}
</style>
