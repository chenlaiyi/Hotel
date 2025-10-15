<template>
  <div>
    <el-card style="margin-bottom: 20px;height:100%">
      <div slot="header" class="clearfix">
        <span>关于我</span>
      </div>

      <div class="user-profile">
        <div class="box-center">
          <el-image style="width: 100px; height: 100px" :src="userinfo.avatarUrl" />
          <!-- <pan-thumb
          :image="logo"
          :height="'100px'"
          :width="'100px'"
          :hoverable="false"
        > -->
          <!-- <div>Hello</div>
          <div v-for="(itm,idx) in userinfodetail.userinfo.roles" :key="idx">{{ itm }}</div> -->
          <!-- </pan-thumb> -->
        </div>
        <div v-if="roles" class="box-center">
          <div class="user-name text-center">{{ userinfo.username }}</div>
          <div
            v-for="(itm, idx) in roles"
            :key="idx"
            class="user-role text-center text-muted"
          >
            {{ itm }}
          </div>
        </div>
      </div>

      <div class="user-bio">
        <div class="user-education user-bio-section">
          <div class="user-bio-section-header">
            <svg-icon icon-class="education" /><span>公司名称</span>
          </div>
          <div class="user-bio-section-body">
            <div class="text-muted">
              {{ footerleft }}
            </div>
          </div>
        </div>
      </div>
    </el-card>
    <setting :userinfo="userinfo" :api-conf="apiConf" :website="Website" :apiurl="apiurl" />
  </div>
</template>

<script>
import setting from './setting'
import PanThumb from '@/components/PanThumb'
import { getuserinfo } from '@projectName/api/profile/profile.js'
export default {
  components: { PanThumb, setting },
  props: {
    userinfo: {
      type: Object,
      default: () => {
        return {}
      }
    },
    Website: {
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
    },
    apiConf: {
      type: Object,
      default: () => {
        return {}
      }
    }
  },
  data() {
    return {
      userinfodetail: '',
      logo: '',
      roles: [],
      footerleft: ''
    }
  },
  methods: {
    onCopy() {
      this.$message.success('复制成功')
    }
  }
}
</script>

<style lang="scss" scoped>
.box-center {
  margin: 0 auto;
  display: table;
}

.text-muted {
  color: #777;
}

.user-profile {
  .user-name {
    font-weight: bold;
  }

  .box-center {
    padding-top: 10px;
  }

  .user-role {
    padding-top: 10px;
    font-weight: 400;
    font-size: 14px;
  }

  .box-social {
    padding-top: 30px;

    .el-table {
      border-top: 1px solid #dfe6ec;
    }
  }

  .user-follow {
    padding-top: 20px;
  }
}

.user-bio {
  margin-top: 20px;
  color: #606266;

  span {
    padding-left: 4px;
  }

  .user-bio-section {
    font-size: 14px;
    padding: 15px 0;

    .user-bio-section-header {
      border-bottom: 1px solid #dfe6ec;
      padding-bottom: 10px;
      margin-bottom: 10px;
      font-weight: bold;
    }
  }
}
</style>
