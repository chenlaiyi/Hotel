<template>
  <div class="app-container">
    <view class="row">
      好的
    </view>
    公司配置
    <el-tabs :tab-position="tabPosition">
      <el-tab-pane v-for="(item,index) in storeList" :key="index" :label="item.business_name">
        <fire-media-box
          v-for="(store,storeIndex) in item.store"
          :key="storeIndex"
          class-name="el-col el-col-24 el-col-xs-24 el-col-sm-12 el-col-md-8 el-col-lg-8 el-col-xl-8"
          :name="store.name"
          :img="store.logo"
          btn-text="选择自助站"
          :desc="store.address"
          @clickBox="setBloc(store)"
        />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
export default {
  data() {
    return {
      storeList: [],
      tabPosition: 'top',
      dialogFormVisible: false,
      workTitle: '系统',
      activeIndex: '1',
      activeIndex2: '1',
      // 检索 start
      filterInfo: {
        data: {
          // page: 1,
          // pageSize: 20
        },
        fieldList: [{
          label: '酒厂名称',
          type: 'input',
          value: 'business_name'
        },
        {
          label: '商户名称',
          type: 'input',
          value: 'store_name'
        }
        ]
      }
      // 检索 end
    }
  },
  methods: {
    toggleSideBar() {
      this.$store.dispatch('app/toggleSideBar')
    },
    async logout() {
      await this.$store.dispatch('user/logout')
      this.$router.push(`/login?redirect=${this.$route.fullPath}`)
    },
    /** 搜索 */
    handleFilter(row) {
      const that = this
      that.$set(that.filterInfo, 'data', row)
      that.getStoreList(that.filterInfo.data)
    },
    /** 重置 */
    handleReset(row) {
      console.log(row)
    },
    /** 焦点失去事件 */
    handleEvent(row) {
      console.log(row)
    },
    handleSelect: function(e) {

    }
  }
}
</script>

<style>
</style>
