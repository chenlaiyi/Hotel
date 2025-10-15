<template>
  <div class="dashboard-editor-container">
    <!-- 检索 start -->
    <el-filter
      size="medium"
      :data="filterInfo.data"
      :field-list="filterInfo.fieldList"
      :show-selection="false"
      @handleFilter="handleFilter"
      @handleReset="handleReset"
      @handleEvent="handleEvent"
    />
    <!-- 检索 end -->
    <div v-if="list.length ===0" class="margin-sm">当前应用{{ plugins.title }}无扩展插件</div>
    <div class="margin-top-sm">
      <panel-group :list="list" @handleSetLineChartData="handleSetLineChartData" />
    </div>
  </div>
</template>

<script>
import {
  mapGetters
} from 'vuex'
import PanelGroup from './components/PanelGroup'
import { fetchChild } from '@projectName/api/addons/addons.js'
export default {
  name: 'PluginsDashboard',
  components: {
    PanelGroup
  },
  computed: {
    ...mapGetters(['plugins', 'LeftMenu'])
  },
  data() {
    return {
      list: [],
      // 检索 start
      filterInfo: {
        data: {
          page: 1,
          pageSize: 20,
          name: '',
          parent_id: '',
          description: ''
          // sex: 1,
          // date: null,
          // dateTime: null,
          // range: null
        },
        fieldList: [
          { label: '应用名称', type: 'input', value: 'SelfHelpGoods[title]' }
        ]
      }
    }
  },
  created() {
    console.log('plugins', this.plugins)
    this.devDependencies = this.$devDependencies
    this.dependencies = this.$dependencies
    this.fetchData()
    this.getChilds()
  },
  beforeDestroy() {
    clearInterval(this.timer)
  },
  methods: {
    getChilds() {
      fetchChild({
        parent_mid: this.plugins.mid
      }).then((res) => {
        const { data, code } = res
        if (code === 200) {
          this.list = data
        }
      })
    },
    /** 搜索 */
    handleFilter(row) {
      const that = this
      that.getChilds()
    },
    /** 重置 */
    handleReset(row) {
      console.log(row)
    },
    /** 焦点失去事件 */
    handleEvent(row) {
      console.log(row)
    },
    handleSetLineChartData(child) {
      // 第一步，找父节点
      const childMenus = this.LeftMenu.filter((item) => item.type === child.identifie)
      console.log('点击子插件', childMenus, child.identifie)

      this.$store.dispatch('permission/setPluginsMent', childMenus)
    },
    handleClick(e) {
      this.$baseMessage(`点击了${e.name},这里可以写跳转`)
    },
    handleZrClick(e) {},
    handleChangeTheme() {
      this.$baseEventBus.$emit('theme')
    },
    async fetchData() {
      const data = [
        {
          'content': '店滴开源框架项目立项',
          'timestamp': '2021-05-01'
        },
        {
          'content': '店滴单商户功能上线',
          'timestamp': '2021-07-01'
        },
        {
          'content': '店滴多商户功能上线',
          'timestamp': '2021-10-01'
        },
        {
          'content': '店滴稳定版发布',
          'timestamp': '2021-11-01'
        },
        {
          'content': '店滴商业版上线',
          'timestamp': '2021-12-01'
        },
        {
          'content': '店滴中后台稳定版上线',
          'timestamp': '2022-02-01'
        },
        {
          'content': '确定以音视频处理为核心技术的发展方向',
          'timestamp': '2022-02-07'
        }
      ]
      data.map((item, index) => {
        if (index === data.length - 1) {
          item.color = '#0bbd87'
        }
      })
      this.activities = data
    }
  }
}
</script>

<style lang="scss" scoped>
.dashboard-editor-container {
  padding: 15px;
  background-color: rgb(240, 242, 245);
  position: relative;

  .github-corner {
    position: absolute;
    top: 0px;
    border: 0;
    right: 0;
  }

  .chart-wrapper {
    background: #fff;
    padding: 16px 16px 0;
    margin-bottom: 32px;
  }
}

@media (max-width:1024px) {
  .chart-wrapper {
    padding: 8px;
  }
}

 .sys-info-main {
    padding: 0 !important;
    margin: 0 !important;
    background: #f5f7f8 !important;
    .sys-tree {
        height: 270px;
    }
    ::v-deep {
      .el-alert {
        padding: 20px;

        &--info.is-light {
          min-height: 82px;
          padding: 20px;
          margin-bottom: 15px;
          color: #909399;
          background-color: #fff;
          border: 1px solid #ebeef5;
        }
      }

      .el-card__body {
        .echarts {
          width: 100%;
          height: 115px;
        }
      }
    }

    .card {
      ::v-deep {
        .el-card__body {
          .echarts {
            width: 100%;
            height: 305px;
          }
        }
      }
    }

    .bottom {
      padding-top: 20px;
      margin-top: 5px;
      color: #595959;
      text-align: left;
      border-top: 1px solid #595959;
    }

    .table {
      width: 100%;
      color: #666;
      border-collapse: collapse;
      background-color: #fff;

      td {
        position: relative;
        min-height: 20px;
        padding: 9px 15px;
        font-size: 14px;
        line-height: 20px;
        border: 1px solid #e6e6e6;

        &:nth-child(odd) {
          width: 20%;
          text-align: right;
          background-color: #f7f7f7;
        }
      }
    }

    .icon-panel {
      height: 117px;
      text-align: center;
      cursor: pointer;

      svg {
        font-size: 40px;
      }

      p {
        margin-top: 10px;
      }
    }
    .update-log {
      padding-top: 15px;
        height: 386px;
        overflow: auto;
    }
    .team-flex{
      display: inline;
      font-size: 14px;
      span{
        padding-right: 5px;
        color: #1890ff;
      }
    }
    .bottom-btn {
      button {
        margin: 5px 10px 15px 0;
      }
    }
  }
</style>
import { find } from 'mock/user'
