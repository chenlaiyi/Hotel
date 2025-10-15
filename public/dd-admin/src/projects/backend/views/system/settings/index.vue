<template>
  <div class="system-set">
    <el-tabs v-model="activeName" type="border-card" @tab-click="handleClick">
      <el-tab-pane label="百度SDK参数" name="baiduSdk">
        <el-form
          :label-position="labelPosition"
          label-width="80px"
          :model="baiduSdk"
        >
          <el-form-item label="应用名称">
            <el-input v-model="baiduSdk.name" />
          </el-form-item>
          <el-form-item label="APP_ID">
            <el-input v-model="baiduSdk.APP_ID" />
          </el-form-item>
          <el-form-item label="API_KEY">
            <el-input v-model="baiduSdk.API_KEY" />
          </el-form-item>
          <el-form-item label="SECRET_KEY">
            <el-input v-model="baiduSdk.SECRET_KEY" />
          </el-form-item>
        </el-form>
        <el-row :gutter="20">
          <el-col :span="24" :offset="12">
            <el-button
              class="save"
              type="primary"
              @click="submitbaiduSdk"
            >保存</el-button>
          </el-col>
        </el-row>
      </el-tab-pane>
      <el-tab-pane label="小程序设置" name="Wxapp">
        <el-form
          :label-position="labelPosition"
          label-width="80px"
          :model="Wxapp"
        >
          <el-form-item label="小程序名称">
            <el-input v-model="Wxapp.name" />
          </el-form-item>
          <el-form-item label="小程序描述">
            <el-input v-model="Wxapp.description" />
          </el-form-item>
          <el-form-item label="原始id">
            <el-input v-model="Wxapp.original" />
          </el-form-item>
          <el-form-item label="Appid">
            <el-input v-model="Wxapp.AppId" />
          </el-form-item>
          <el-form-item label="AppSecret">
            <el-input v-model="Wxapp.AppSecret" />
          </el-form-item>
          <el-form-item label="普通二维码链接">
            <el-input v-model="Wxapp.codeUrl" />
          </el-form-item>
          <el-form-item label="二维码">
            <image-uploader v-model="Wxapp.headimg" />
          </el-form-item>
          <el-form-item label="分享标题">
            <el-input v-model="Wxapp.share_title" />
          </el-form-item>
          <el-form-item label="分享页面">
            <el-input v-model="Wxapp.share_path" />
          </el-form-item>
          <el-form-item label="分享图片">
            <image-uploader v-model="Wxapp.share_image" />
          </el-form-item>
        </el-form>
        <el-row :gutter="20">
          <el-col :span="24" :offset="12">
            <el-button
              class="save"
              type="primary"
              @click="submitWxapp"
            >保存</el-button>
          </el-col>
        </el-row>
      </el-tab-pane>
      <el-tab-pane label="公众号设置" name="Wechat">
        <el-form
          :label-position="labelPosition"
          label-width="80px"
          :model="Wechat"
        >
          <el-form-item label="app_id">
            <el-input v-model="Wechat.app_id" />
          </el-form-item>
          <el-form-item label="token">
            <el-input v-model="Wechat.token" />
          </el-form-item>
          <el-form-item label="aes_key">
            <el-input v-model="Wechat.aes_key" />
          </el-form-item>
          <el-form-item label="secret">
            <el-input v-model="Wechat.secret" />
          </el-form-item>
          <el-form-item label="Headimg">
            <image-uploader v-model="Wxapp.headimg" />
          </el-form-item>
        </el-form>
        <el-row :gutter="20">
          <el-col :span="24" :offset="12">
            <el-button
              class="save"
              type="primary"
              style="margin-top: 20px"
              @click="submitWechat"
            >保存</el-button>
          </el-col>
        </el-row>
      </el-tab-pane>
      <el-tab-pane label="微信支付" name="Wechatpay">
        <el-form
          :label-position="labelPosition"
          label-width="80px"
          :model="Wechatpay"
        >
          <el-form-item label="支付商户号">
            <el-input v-model="Wechatpay.mch_id" />
          </el-form-item>
          <el-form-item label="AppId">
            <el-input v-model="Wechatpay.app_id" />
          </el-form-item>
          <el-form-item label="秘钥">
            <el-input v-model="Wechatpay.key" />
          </el-form-item>
          <!-- <el-form-item label="回调地址">
            <el-input v-model="Wechatpay.notify_url" />
          </el-form-item> -->
          <el-form-item label="cert证书">
            <el-input v-model="Wechatpay.apiclient_cert" type="textarea" />
          </el-form-item>
          <el-form-item label="key证书">
            <el-input v-model="Wechatpay.apiclient_key" type="textarea" />
          </el-form-item>
        </el-form>
        <el-row :gutter="20">
          <el-col :span="24" :offset="12">
            <el-button
              class="save"
              type="primary"
              @click="submitWechatpay"
            >保存</el-button>
          </el-col>
        </el-row>
      </el-tab-pane>
      <el-tab-pane label="短信设置" name="eiegth">
        <el-form
          :label-position="labelPosition"
          label-width="80px"
          :model="Sms"
        >
          <el-form-item label="AccessKey ID">
            <el-input v-model="Sms.access_key_id" />
          </el-form-item>
          <el-form-item label="Access Key Secret">
            <el-input v-model="Sms.access_key_secret" />
          </el-form-item>
          <el-form-item label="签名">
            <el-input v-model="Sms.sign_name" />
          </el-form-item>
          <el-form-item label="模板code">
            <el-input v-model="Sms.template_code" />
          </el-form-item>
        </el-form>
        <el-row :gutter="20">
          <el-col :span="24" :offset="12">
            <el-button
              class="save"
              type="primary"
              @click="submitSms"
            >保存</el-button>
          </el-col>
        </el-row>
      </el-tab-pane>
      <el-tab-pane label="邮箱服务器" name="nineth">
        <el-form
          :label-position="labelPosition"
          label-width="80px"
          :model="Email"
        >
          <el-form-item label="smtp地址">
            <el-input v-model="Email.host" />
          </el-form-item>
          <el-form-item label="端口">
            <el-input v-model="Email.port" />
          </el-form-item>
          <el-form-item label="邮箱账号">
            <el-input v-model="Email.username" />
          </el-form-item>
          <el-form-item label="邮箱密码">
            <el-input v-model="Email.password" />
          </el-form-item>
          <el-form-item label="发送者名称">
            <el-input v-model="Email.title" />
          </el-form-item>
          <el-form-item label="Encryption">
            <el-radio-group v-model="Email.encryption" @change="cutRadio">
              <el-radio label="tls">tls</el-radio>
              <el-radio label="ssl">ssl</el-radio>
            </el-radio-group>
          </el-form-item>
        </el-form>
        <el-row :gutter="20">
          <el-col :span="24" :offset="12">
            <el-button
              class="save"
              type="primary"
              @click="submitEmail"
            >保存</el-button>
          </el-col>
        </el-row>
      </el-tab-pane>
      <el-tab-pane label="地图设置" name="Map">
        <el-form
          :label-position="labelPosition"
          label-width="80px"
          :model="Maps"
        >
          <el-form-item label="百度地图APK">
            <el-input v-model="Maps.baiduApk" />
          </el-form-item>
          <el-form-item label="高德地图APK">
            <el-input v-model="Maps.amapApk" />
          </el-form-item>
          <el-form-item label="腾讯地图APK">
            <el-input v-model="Maps.tencentApk" />
          </el-form-item>
          <el-row :gutter="20">
            <el-col :span="24" :offset="12">
              <el-button
                class="save"
                type="primary"
                @click="submitMaps"
              >保存</el-button>
            </el-col>
          </el-row>
        </el-form>
      </el-tab-pane>
      <el-tab-pane label="对象存储" name="Oss">
        <el-form
          :label-position="labelPosition"
          label-width="80px"
          :model="Oss"
        >
          <el-form-item label="存储类型" prop="resource">
            <el-radio-group v-model="Oss.remote_type">
              <el-radio label="local">本地</el-radio>
              <el-radio label="alioss">阿里云</el-radio>
              <el-radio label="qiniu">七牛</el-radio>
              <el-radio label="cos">腾讯云</el-radio>
            </el-radio-group>
          </el-form-item>

          <el-card v-if="Oss.remote_type === 'alioss'" shadow="never">
            <div slot="header" class="clearfix">
              <span>阿里云</span>
            </div>
            <el-form-item label="accessKeyId">
              <el-input v-model="Oss.Aliyunoss_accessKeyId" />
              <span>Access Key
                ID是您访问阿里云API的密钥，具有该账户完全的权限，请您妥善保管。
              </span>
            </el-form-item>

            <el-form-item label="存储桶所在地">
              <el-select v-model="Oss.Aliyunoss_endPoint" placeholder="请选择">
                <el-option
                  v-for="(item, index) in end_point"
                  :key="index"
                  :label="item.name"
                  :value="item.key"
                >
                  {{ item.name }}
                </el-option>
              </el-select>
            </el-form-item>

            <el-form-item label="内网上传" prop="Aliyunoss_resource">
              <el-radio-group v-model="Oss.Aliyunoss_resource">
                <el-radio label="1">是</el-radio>
                <el-radio label="0">否</el-radio>
              </el-radio-group>
              <el-row>
                <span>如果此站点使用的是阿里云ecs服务器，并且服务器与bucket在同一地区（如：同在华北一区），您可以选择通过内网上传的方式上传附件，以加快上传速度、节省带宽。</span>
              </el-row>
            </el-form-item>
            <el-form-item label="accessKeySecret">
              <el-input v-model="Oss.Aliyunoss_accessKeySecret" />
              <span>Access Key
                Secret是您访问阿里云API的密钥，具有该账户完全的权限，请您妥善保管。(填写完Access
                Key ID 和 Access Key Secret 后请选择bucket)</span>
            </el-form-item>

            <el-form-item label="存储桶">
              <el-input v-model="Oss.Aliyunoss_bucket" />
            </el-form-item>
            <el-form-item label="自定义URL">
              <el-input v-model="Oss.Aliyunoss_url" />
              <span>阿里云oss支持用户自定义访问域名，如果自定义了URL则用自定义的URL，如果未自定义，则用系统生成出来的URL。注：自定义url开头加http://或https://结尾不加
                ‘/’例：http://abc.com</span>
            </el-form-item>
          </el-card>

          <el-card
            v-if="Oss.remote_type === 'cos'"
            class="margin-tb-xs"
            shadow="never"
          >
            <div slot="header" class="clearfix">
              <span>腾讯云</span>
            </div>
            <el-form-item label="APPID">
              <el-input v-model="Oss.Tengxunoss_APPID" />
              <span>APPID 是您项目的唯一ID</span>
            </el-form-item>
            <el-form-item label="SecretID">
              <el-input v-model="Oss.Tengxunoss_SecretID" />
              <span>SecretID
                是您项目的安全密钥，具有该账户完全的权限，请妥善保管</span>
            </el-form-item>
            <el-form-item label="SecretKEY">
              <el-input v-model="Oss.Tengxunoss_SecretKEY" />
              <span>SecretKEY
                是您项目的安全密钥，具有该账户完全的权限，请妥善保管</span>
            </el-form-item>

            <el-form-item label="Bucket">
              <el-input v-model="Oss.Tengxunoss_Bucket" />
              <span>请保证bucket为可公共读取的</span>
            </el-form-item>

            <el-form-item label="选择bucket对应的区域，如果没有选择无">
              <el-input v-model="Oss.Tengxunoss_area" />
              <span>选择bucket对应的区域，如果没有选择无</span>
            </el-form-item>
            <el-form-item label="url">
              <el-input v-model="Oss.Tengxunoss_url" />
              <span>腾讯云支持用户自定义访问域名。注：url开头加http://或https://结尾不加
                ‘/’例：http://abc.com</span>
            </el-form-item>
          </el-card>
          <el-card
            v-if="Oss.remote_type === 'qiniu'"
            class="margin-bottom-xs"
            shadow="never"
          >
            <div slot="header" class="clearfix">
              <span>七牛云</span>
            </div>
            <el-form-item label="Accesskey">
              <el-input v-model="Oss.Qiniuoss_Accesskey" />
              <span>用于签名的公钥</span>
            </el-form-item>
            <el-form-item label="Secretkey">
              <el-input v-model="Oss.Qiniuoss_Secretkey" />
              <span>用于签名的私钥</span>
            </el-form-item>
            <el-form-item label="Bucket">
              <el-input v-model="Oss.Qiniuoss_Bucket" />
              <span>请保证bucket为可公共读取的</span>
            </el-form-item>
            <el-form-item label="url">
              <el-input v-model="Oss.Qiniuoss_url" />
              <span>七牛支持用户自定义访问域名。注：url开头加http://或https://结尾不加
                ‘/’例：http://abc.com</span>
            </el-form-item>
          </el-card>
          <el-row :gutter="20">
            <el-col :span="24" :offset="12">
              <el-button
                class="save"
                type="primary"
                @click="submitOss"
              >保存</el-button>
            </el-col>
          </el-row>
        </el-form>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import EleUploadImage from 'diandi-ele-upload-image'
import EleUploadFile from 'diandi-ele-upload-file'

import {
  saveWeburl,
  saveBaidu,
  saveWechatpay,
  saveSms,
  saveEmail,
  saveWxapp,
  saveWechat,
  saveMicroapp,
  saveApp,
  saveMap,
  saveOss,
  getWeburl,
  getBaidu,
  getWechatpay,
  getSms,
  getEmail,
  getWxapp,
  getWechat,
  getMicroapp,
  getApp,
  getMap,
  getOss
} from '@projectName/views/system/api/system/config.js'
import {
  mapGetters
} from 'vuex'

export default {
  name: '',
  components: {
    EleUploadImage,
    EleUploadFile
  },
  data() {
    return {
      // 阿里云地域列表
      end_point: [
        { key: 'oss-cn-hangzhou', name: '华东1（杭州）' },
        { key: 'oss-cn-shanghai', name: '华东2（上海）' },
        { key: 'oss-cn-qingdao', name: '华北1（青岛）' },
        { key: 'oss-cn-beijing', name: '华北2（北京）' },
        { key: 'oss-cn-zhangjiakou', name: '华北3（张家口）' },
        { key: 'oss-cn-huhehaote', name: '华北5（呼和浩特）' },
        { key: 'oss-cn-wulanchabu', name: '华北6（乌兰察布）' },
        { key: 'oss-cn-shenzhen', name: '华南1（深圳）' },
        { key: 'oss-cn-heyuan', name: '华南2（河源）' },
        { key: 'oss-cn-guangzhou', name: '华南3（广州）' },
        { key: 'oss-cn-chengdu', name: '西南1（成都）' },
        { key: 'oss-cn-hongkong', name: '中国（香港）' },
        { key: 'oss-us-west-1', name: '美国（硅谷）*' },
        { key: 'oss-us-east-1', name: '美国（弗吉尼亚）*' },
        { key: 'oss-ap-southeast-1', name: '新加坡*' },
        { key: 'oss-ap-southeast-2', name: '澳大利亚（悉尼）*' },
        { key: 'oss-ap-southeast-3', name: '马来西亚（吉隆坡）*' },
        { key: 'oss-ap-southeast-5', name: '印度尼西亚（雅加达）*' },
        { key: 'oss-ap-northeast-1', name: '日本（东京）*' },
        { key: 'oss-ap-south-1', name: '印度（孟买）*' },
        { key: 'oss-eu-central-1', name: '德国（法兰克福）*' },
        { key: 'oss-eu-west-1', name: '英国（伦敦）' },
        { key: 'oss-me-east-1', name: '阿联酋（迪拜）*' },
        { key: 'oss-ap-southeast-6', name: '菲律宾（马尼拉）' }
      ],
      imgDatas: [],
      activeName: 'baiduSdk',
      labelPosition: 'top',
      Weburl: {
        backendurl: '',
        frendurl: '',
        apiurl: '',
        urls: ''
      },
      baiduSdk: {
        name: '',
        APP_ID: '',
        API_KEY: '',
        SECRET_KEY: ''
      },
      Wxapp: {
        name: '',
        description: '',
        original: '',
        AppId: '',
        AppSecret: '',
        codeUrl: '',
        headimg: ''
      },
      Wechat: {
        app_id: '',
        token: '',
        aes_key: '',
        secret: '',
        headimg: ''
      },
      Wechatpay: {
        mch_id: '',
        app_id: '',
        key: '',
        notify_url: '',
        apiclient_cert: '',
        apiclient_key: ''
      },
      Sms: {
        access_key_id: '',
        access_key_secret: '',
        sign_name: '',
        template_code: ''
      },
      Email: {
        encryption: '',
        title: '',
        password: '',
        username: '',
        port: '',
        host: ''
      },
      Maps: {
        baiduApk: '',
        amapApk: '',
        tencentApk: ''
      },
      Oss: {
        remote_type: 'local',
        Aliyunoss_endPoint: '',
        Aliyunoss_bucket: '',
        Aliyunoss_accessKeyId: '',
        Aliyunoss_resource: '0',
        Aliyunoss_accessKeySecret: '',
        Aliyunoss_url: '',
        Tengxunoss_APPID: '',
        Tengxunoss_SecretID: '',
        Tengxunoss_SecretKEY: '',
        Tengxunoss_Bucket: '',
        Tengxunoss_area: '',
        Tengxunoss_url: '',
        Qiniuoss_Accesskey: '',
        Qiniuoss_Secretkey: '',
        Qiniuoss_Bucket: '',
        Qiniuoss_url: ''
      },
      uploudUrl: '',
      headers: {},
      radio: 1,
      bloc_id: 0
    }
  },
  computed: {
    ...mapGetters(['elForm'])
  },
  created() {
    const that = this
    that.uploudUrl = that.apiUrl + 'upload/images'
    console.log(that.$store.state.user)
    that.bloc_id = that.$route.params.bloc_id
    this.initPage()
  },
  methods: {
    initPage() {
      getWeburl({ bloc_id: this.bloc_id }).then((res) => {
        if (res.code === 200) {
          this.Weburl = res.data
        }
      })
      getWechatpay({ bloc_id: this.bloc_id }).then((res) => {
        if (res.code === 200) {
          this.Wechatpay = res.data
        }
      })

      getBaidu({ bloc_id: this.bloc_id }).then((res) => {
        if (res.code === 200) {
          this.baiduSdk = res.data
        }
      })

      getSms({ bloc_id: this.bloc_id }).then((res) => {
        if (res.code === 200) {
          this.Sms = res.data
        }
      })
      getEmail({ bloc_id: this.bloc_id }).then((res) => {
        if (res.code === 200) {
          this.Email = res.data
        }
      })
      getWxapp({ bloc_id: this.bloc_id }).then((res) => {
        if (res.code === 200) {
          this.Wxapp = res.data
        }
      })
      getWechat({ bloc_id: this.bloc_id }).then((res) => {
        if (res.code === 200) {
          this.Wechat = res.data
        }
      })

      getMicroapp({ bloc_id: this.bloc_id }).then((res) => {
        if (res.code === 200) {
          this.Microapp = res.data
        }
      })
      getApp({ bloc_id: this.bloc_id }).then((res) => {
        if (res.code === 200) {
          this.App = res.data
        }
      })
      getMap({ bloc_id: this.bloc_id }).then((res) => {
        if (res.code === 200) {
          this.Maps = res.data
        }
      })

      getOss({ bloc_id: this.bloc_id }).then((res) => {
        if (res.code === 200) {
          this.Oss = res.data
        }
      })
    },
    // 对请求结果处理, 返回对象
    handleResponsePem(response, file) {
      return {
        url: response.data.attachment,
        name: file.name,
        size: file.size
      }
    },
    handleResponseError(e) {
      console.log(e)
    },
    submitWeburl() {
      const data = Object.assign(this.Weburl, { bloc_id: this.bloc_id })
      console.log(data)
      saveWeburl({ Weburl: data }).then((res) => {
        console.log(res)

        if (res.code === 200) {
          this.$message.success(res.message)
        } else {
          this.$message.error(res.message)
        }
      })
    },
    submitbaiduSdk() {
      const data = Object.assign(this.baiduSdk, { bloc_id: this.bloc_id })

      saveBaidu({ Baidu: data }).then((res) => {
        if (res.code === 200) {
          this.$message.success(res.message)
        } else {
          this.$message.error(res.message)
        }
      })
    },
    submitWxapp() {
      const data = Object.assign(this.Wxapp, { bloc_id: this.bloc_id })

      saveWxapp({ Wxapp: data }).then((res) => {
        if (res.code === 200) {
          this.$message.success(res.message)
        } else {
          this.$message.error(res.message)
        }
      })
    },
    submitWechat() {
      const data = Object.assign(this.Wechat, { bloc_id: this.bloc_id })

      saveWechat({ Wechat: data }).then((res) => {
        if (res.code === 200) {
          this.$message.success(res.message)
        } else {
          this.$message.error(res.message)
        }
      })
    },
    submitWechatpay() {
      const data = Object.assign(this.Wechatpay, { bloc_id: this.bloc_id })

      saveWechatpay({ Wechatpay: data }).then((res) => {
        if (res.code === 200) {
          this.$message.success(res.message)
        } else {
          this.$message.error(res.message)
        }
      })
    },
    submitSms() {
      const data = Object.assign(this.Sms, { bloc_id: this.bloc_id })

      saveSms({ Sms: data }).then((res) => {
        if (res.code === 200) {
          this.$message.success(res.message)
        } else {
          this.$message.error(res.message)
        }
      })
    },
    submitEmail() {
      const data = Object.assign(this.Email, { bloc_id: this.bloc_id })

      saveEmail({ Email: data }).then((res) => {
        if (res.code === 200) {
          this.$message.success(res.message)
        } else {
          this.$message.error(res.message)
        }
      })
    },
    submitMaps() {
      const data = Object.assign(this.Maps, { bloc_id: this.bloc_id })
      saveMap({ Map: data }).then((res) => {
        if (res.code === 200) {
          this.$message.success(res.message)
        } else {
          this.$message.error(res.message)
        }
      })
    },
    submitOss() {
      const data = Object.assign(this.Oss, { bloc_id: this.bloc_id })

      saveOss({ Oss: data }).then((res) => {
        if (res.code === 200) {
          this.$message.success(res.message)
        } else {
          this.$message.error(res.message)
        }
      })
    },
    handleClick(tab, event) {
      this.activeName = tab.name
      console.log(tab.name, event)
    },
    handleResponse(response, file, fileList) {
      console.log('response', response)
      // 根据响应结果, 设置 URL
      // this.accounts.url = response.attachment;
      // this.accounts.imag = response.url;
      return response.url
    },
    upLoad() {
      // 触发上传图片按钮
      console.log('点击上传按钮')
      this.$refs['uploadImage'].$refs['upload'].$refs[
        'upload-inner'
      ].handleClick()
    },
    beforeRemove(index) {
      console.log(index, this.$refs.uploadImage)
      // this.accounts.url = "";
    },
    cutRadio() {
      this.encryption = this.radio
      console.log(this.encryption)
    }
  }
}
</script>

<style lang="scss" scoped>
.system-set {
  margin: 35px 19px;
  border-radius: 8px;
}
.save {
  width: 157px;
  margin: 76px 0;
  height: 48px;
}
::v-deep .el-tabs__nav-scroll {
  height: 67px;
  background: #f5f6fe;
  color: #8d8d8d;
}
::v-deep .el-tabs--border-card > .el-tabs__header .el-tabs__item {
  border: none;
  height: 67px;
}
::v-deep .el-tabs__item {
  line-height: 67px;
}
::v-deep .el-tabs--border-card > .el-tabs__header .el-tabs__item.is-active {
  color: #5154b8;
}
::v-deep .el-tabs--border-card > .el-tabs__content {
  padding: 39px 29px;
}
</style>>
