<template>
  <div class="app-container">
    <div v-if="userinfo">
      <el-row :gutter="20">
        <el-col :span="10" :xs="24" :md="10" :lg="10">
          <user-card :userinfo="userinfo" :website="Website" :api-conf="apiConf" :apiurl="apiurl" />
        </el-col>

        <el-col :span="14" :xs="24" :md="14" :lg="14">
          <el-card style="height:100%">
            <el-tabs v-model="activeTab" @tab-click="tabClick">
              <el-tab-pane label="公司认证" name="公司认证">
                <companycertif :userinfo="userinfo" />
              </el-tab-pane>
              <el-tab-pane label="修改密码" name="修改密码">
                <repassword :userinfo="userinfo" />
              </el-tab-pane>
              <el-tab-pane label="修改资料" name="修改资料">
                <edituserinfo :userinfo="userinfo" />
              </el-tab-pane>
              <el-tab-pane label="操作日志" name="操作日志">
                <timeline :userinfo="userinfo" />
              </el-tab-pane>
            </el-tabs>
          </el-card>
        </el-col>
      </el-row>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import UserCard from './components/UserCard'
import repassword from './components/repassword'
import Timeline from './components/Timeline'
import companycertif from './components/companycertif'
import edituserinfo from './components/edituserinfo'
import { getuserinfo } from '@projectName/api/profile/profile.js'

export default {
  name: 'Profile',
  components: { UserCard, repassword, Timeline, edituserinfo, companycertif },
  data() {
    return {
      userinfo: {},
      activeTab: '公司认证',
      Website: {},
      apiurl: '',
      apiConf: {}
    }
  },
  computed: {
    ...mapGetters(['name', 'avatar', 'roles'])
  },
  created() {
    this.getUser()
  },
  methods: {
    getUser() {
      const that = this
      getuserinfo().then((res) => {
        console.log('getuserinfo', res.data.userinfo.user)
        that.userinfo = res.data.userinfo.user
        this.$set(that.userinfo, 'avatarUrl', that.userinfo.avatar)

        this.$set(that.userinfo, 'avatar', that.userinfo.avatar.split('attachment')[1])

        that.Website = res.data.apiConf
        that.apiConf = res.data.apiConf
        that.apiurl = res.data.apiurl
      })
    },
    tabClick(e) {
      console.log(e)
      this.getUser()
    }
  }
}
</script>
