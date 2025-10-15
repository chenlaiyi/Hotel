<template>
  <div>
    <el-tabs v-model="activeName" type="border-card" @tab-click="handleClick">
      <el-tab-pane label="域名设置" name="first">
        <el-form :label-position="labelPosition" label-width="80px" :model="domainSet">
          <el-form-item label="后台地址">
            <el-input v-model="domainSet.qaddress" />
          </el-form-item>
          <el-form-item label="前台地址">
            <el-input v-model="domainSet.haddress" />
          </el-form-item>
          <el-form-item label="接口地址">
            <el-input v-model="domainSet.jaddress" />
          </el-form-item>
          <el-form-item label="跨域域名">
            <el-input v-model="domainSet.domain" />
          </el-form-item>
        </el-form>
        <el-button type="primary">保存</el-button>
      </el-tab-pane>
      <el-tab-pane label="百度SDK参数" name="second">
        <el-form :label-position="labelPosition" label-width="80px" :model="baiduSdk">
          <el-form-item label="应用名称">
            <el-input v-model="baiduSdk.name" />
          </el-form-item>
          <el-form-item label="APP_ID">
            <el-input v-model="baiduSdk.appid" />
          </el-form-item>
          <el-form-item label="API_KEY">
            <el-input v-model="baiduSdk.apikey" />
          </el-form-item>
          <el-form-item label="SECRET_KEY">
            <el-input v-model="baiduSdk.secretkey" />
          </el-form-item>
        </el-form>
        <el-button type="primary">保存</el-button>
      </el-tab-pane>
      <el-tab-pane label="小程序设置" name="third">
        <el-form :label-position="labelPosition" label-width="80px" :model="program">
          <el-form-item label="小程序名称">
            <el-input v-model="program.name" />
          </el-form-item>
          <el-form-item label="小程序描述">
            <el-input v-model="program.describe" />
          </el-form-item>
          <el-form-item label="原始id">
            <el-input v-model="program.id" />
          </el-form-item>
          <el-form-item label="Appid">
            <el-input v-model="program.appid" />
          </el-form-item>
          <el-form-item label="AppSecret">
            <el-input v-model="program.appsecret" />
          </el-form-item>
          <el-form-item label="普通二维码链接">
            <el-input v-model="program.code" />
          </el-form-item>
        </el-form>
        <el-button type="primary">保存</el-button>
      </el-tab-pane>
      <el-tab-pane label="公众号设置" name="fiveth">
        <el-form :label-position="labelPosition" label-width="80px" :model="accounts ">
          <el-form-item label="app_id">
            <el-input v-model="accounts.appid" />
          </el-form-item>
          <el-form-item label="token">
            <el-input v-model="accounts.token" />
          </el-form-item>
          <el-form-item label="aes_key">
            <el-input v-model="accounts.aeskey" />
          </el-form-item>
          <el-form-item label="secret">
            <el-input v-model="accounts.secret" />
          </el-form-item>
          <el-form-item label="Headimg">
            <el-input v-model="accounts.url">
              <el-button slot="append" @click="upLoad">
                点击上传
              </el-button>
            </el-input>
          </el-form-item>

          <ele-upload-image
            ref="uploadImage"
            v-model="accounts.imag"
            :action="uploudUrl"
            :headers="headers"
            :response-fn="handleResponse"
            :before-remove="beforeRemove"
          />
        </el-form>
        <el-button type="primary" style="margin-top: 20px;">保存</el-button>
      </el-tab-pane>
      <el-tab-pane label="微信支付" name="seventh">
        <el-form :label-position="labelPosition" label-width="80px" :model="payment ">
          <el-form-item label="支付商户号">
            <el-input v-model="payment.num" />
          </el-form-item>
          <el-form-item label="AppId">
            <el-input v-model="payment.appid" />
          </el-form-item>
          <el-form-item label="秘钥">
            <el-input v-model="payment.private " />
          </el-form-item>
          <el-form-item label="回调地址">
            <el-input v-model="payment.address" />
          </el-form-item>
        </el-form>
        <el-button type="primary">保存</el-button>
      </el-tab-pane>
      <el-tab-pane label="短信设置" name="eiegth">
        <el-form :label-position="labelPosition" label-width="80px" :model="shotnode ">
          <el-form-item label="AccessKey ID">
            <el-input v-model="shotnode.accesskey" />
          </el-form-item>
          <el-form-item label="Access Key Secret">
            <el-input v-model="shotnode.secret" />
          </el-form-item>
          <el-form-item label="签名">
            <el-input v-model="shotnode.signature" />
          </el-form-item>
          <el-form-item label="模板code">
            <el-input v-model="shotnode.template" />
          </el-form-item>
        </el-form>
        <el-button type="primary">保存</el-button>
      </el-tab-pane>
      <el-tab-pane label="邮箱服务器" name="nineth">
        <el-form :label-position="labelPosition" label-width="80px" :model="emailServer">
          <el-form-item label="smtp地址">
            <el-input v-model="emailServer.adddress" />
          </el-form-item>
          <el-form-item label="端口">
            <el-input v-model="emailServer.port" />
          </el-form-item>
          <el-form-item label="邮箱账号">
            <el-input v-model="emailServer.email" />
          </el-form-item>
          <el-form-item label="邮箱密码">
            <el-input v-model="emailServer.password" />
          </el-form-item>
          <el-form-item label="发送者名称">
            <el-input v-model="emailServer.names" />
          </el-form-item>
          <el-form-item label="Encryption">
            <el-radio-group v-model="radio" @change="cutRadio">
              <el-radio :label="1">tls</el-radio>
              <el-radio :label="2">ssl</el-radio>
            </el-radio-group></el-form-item>
          </el-radio-group>
        </el-form>
        <el-button type="primary">保存</el-button>
      </el-tab-pane>
      <el-tab-pane label="地图设置" name="tenth">
        <el-form :label-position="labelPosition" label-width="80px" :model="maps">
          <el-form-item label="百度地图APK">
            <el-input v-model="maps.baidu" />
          </el-form-item>
          <el-form-item label="高德地图APK">
            <el-input v-model="maps.autoNavi" />
          </el-form-item>
          <el-form-item label="腾讯地图APK">
            <el-input v-model="maps.tencent" />
          </el-form-item>
          <el-button type="primary">保存</el-button>
        </el-form></el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import EleUploadImage from 'diandi-ele-upload-image'
export default {
  name: '',
  components: {
    EleUploadImage
  },
  data() {
    return {
      imgDatas: [],
      activeName: 'first',
      labelPosition: 'top',
      domainSet: {
        qaddress: '',
        haddress: '',
        jaddress: '',
        domain: ''
      },
      baiduSdk: {
        name: '',
        appid: '',
        apikey: '',
        secretkey: ''
      },
      program: {
        name: '',
        describe: '',
        id: '',
        appid: '',
        appsecret: '',
        code: ''
      },
      accounts: {
        appid: '',
        token: '',
        aeskey: '',
        secret: '',
        url: '',
        imag: ''
      },
      payment: {
        num: '',
        appid: '',
        private: '',
        address: ''
      },
      shotnode: {
        accesskey: '',
        secret: '',
        signature: '',
        template: ''
      },
      emailServer: {
        adddress: '',
        port: '',
        email: '',
        password: '',
        names: '',
        encryption: 0
      },
      maps: {
        baidu: '',
        autoNavi: '',
        tencent: ''
      },
      uploudUrl: '',
      headers: {},
      radio: 1
    }
  },
  created() {
    const that = this
    that.uploudUrl = that.apiUrl + 'upload/images'
    console.log(that.$store.state.user)
    that.$set(that.headers, 'access-token', that.$store.state.user.access_token)
  },
  methods: {
    handleClick(tab, event) {
      console.log(tab, event)
    },
    handleResponse(response, file, fileList) {
      console.log('response', response)
      // 根据响应结果, 设置 URL
      this.accounts.url = response.attachment
      this.accounts.imag = response.url
      return response.url
    },
    upLoad() {
      // 触发上传图片按钮
      console.log('点击上传按钮')
      this.$refs['uploadImage'].$refs['upload'].$refs['upload-inner'].handleClick()
    },
    beforeRemove(index) {
      console.log(index, this.$refs.uploadImage)
      this.accounts.url = ''
    },
    cutRadio() {
      this.encryption = this.radio
      console.log(this.encryption)
    }
  }
}
</script>

<styles scoped>
</style>
