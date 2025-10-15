<template>
  <el-card
    class="margin-bottom-sm"
    shadow="always"
    :body-style="{ padding: '20px' }"
    style="height:100%"
  >
    <div slot="header">
      <span>接口参数</span>
    </div>
    <!-- card body -->
    <div class="user-skills user-bio-section">
      <div class="user-bio-section-body">
        <el-row>
          <el-col :span="7">
            <div class="grid-content bg-purple">接口地址</div>
          </el-col>
          <el-col :span="14">
            <div class="grid-content bg-purple">
              {{ apiurl }}
            </div>
          </el-col>
          <el-col :span="3">
            <el-button
              v-clipboard:copy="apiurl"
              v-clipboard:success="onCopy"
              type="primary"
              size="mini"
            >复制</el-button>
          </el-col>
        </el-row>
        <el-row class="margin-bt-sm">
          <el-col :span="7">
            <div class="grid-content bg-purple">app_id</div>
          </el-col>
          <el-col :span="14">
            <div class="grid-content bg-purple">
              {{ apiConf.app_id }}
            </div>
          </el-col>
          <el-col :span="3">
            <el-button
              v-clipboard:copy="apiConf.app_id"
              v-clipboard:success="onCopy"
              type="primary"
              size="mini"
            >复制</el-button>
          </el-col>
        </el-row>
        <el-row class="margin-bt-sm">
          <el-col :span="7">
            <div class="grid-content bg-purple">app_secret</div>
          </el-col>
          <el-col :span="14">
            <div class="grid-content bg-purple">
              {{ apiConf.app_secret }}
            </div>
          </el-col>
          <el-col :span="3">
            <el-button
              v-clipboard:copy="apiConf.app_secret"
              v-clipboard:success="onCopy"
              type="primary"
              size="mini"
            >复制</el-button>
          </el-col>
        </el-row>
        <el-row class="margin-bt-sm">
          <el-col :span="7">
            <div class="grid-content bg-purple">store_id</div>
          </el-col>
          <el-col :span="14">
            <div class="grid-content bg-purple">
              {{ userinfo.store_id || 0 }}
            </div>
          </el-col>
          <el-col :span="3">
            <el-button
              v-clipboard:copy="userinfo.store_id"
              v-clipboard:success="onCopy"
              type="primary"
              size="mini"
            >复制</el-button>
          </el-col>
        </el-row>
        <el-row class="margin-bt-sm">
          <el-col :span="7">
            <div class="grid-content bg-purple">bloc_id</div>
          </el-col>
          <el-col :span="14">
            <div class="grid-content bg-purple">
              {{ userinfo.bloc_id || 0 }}
            </div>
          </el-col>
          <el-col :span="3">
            <el-button
              v-clipboard:copy="userinfo.bloc_id"
              v-clipboard:success="onCopy"
              type="primary"
              size="mini"
            >复制</el-button>
          </el-col>
        </el-row>
      </div>
    </div>
  </el-card>
</template>

<script>
import PanThumb from '@/components/PanThumb'
export default {
  components: { PanThumb },
  props: {
    userinfo: {
      type: Object,
      default: () => {
        return {}
      }
    },
    apiConf: {
      type: Object,
      default: () => {
        return {}
      }
    },
    apiurl: {
      type: String,
      default: () => {
        return ''
      }
    }
  },
  data() {
    return {
      logo: '',
      roles: [],
      footerleft: '',
      Website: {}
    }
  },
  methods: {
    onCopy() {
      this.$message.success('复制成功')
    }
  }
}
</script>

<style>
.margin-bt-sm {
  margin: 15px 0px;
}
</style>
