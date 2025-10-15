<template>
  <div class="addons-container">
    <!-- 检索 start -->
    <div class="addons-search">
      <el-filter
        size="medium"
        :data="filterInfo.data"
        :field-list="filterInfo.fieldList"
        :show-selection="false"
        @handleFilter="handleFilter"
        @handleReset="handleReset"
        @handleEvent="handleEvent"
      />
    </div>
    <!-- 检索 end -->

    <div class="main-content">
      <el-row :gutter="20">
        <el-col
          v-for="(item, index) in list"
          :key="index"
          :md="12"
          :xs="24"
          :xl="8"
          :lg="8"
        >
          <el-card
            class="content-itm"
            :body-style="{ padding: 0 }"
            shadow="hover"
          >
            <el-col :span="24" class="table-name">
              <el-col :span="9">
                <div
                  class="index-title"
                  :class="{ 'lines-cyan': index % 2 === 0 }"
                >
                  {{ item.title | indexTitle }}
                </div>
              </el-col>
              <el-col :span="15">
                <el-col :span="16">
                  <div class="addons-title">
                    {{ item.title }}
                  </div>
                </el-col>
                <el-col :span="15">
                  <div class="addons-desc">{{ item.description }}</div>
                </el-col>
                <el-col :span="4">
                  <div class="btn-use el-button--primary" @click="installAddons(item)">安装</div>
                </el-col>
              </el-col>
            </el-col>
          </el-card>
        </el-col>
      </el-row>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { fetchUninstalled } from '@projectName/views/system/api/addons/addons.js'
import { ManageInstall } from '@projectName/views/system/api/addons/manage.js'

import { parseTime } from '@projectName/utils'
import FireMediaBox from '@/components/FireMediaBox'
export default {
  components: {
    FireMediaBox
  },
  data() {
    return {
      // 表格数据 start
      tableColumns: [
        { label: '公司名称', prop: 'business_name' },
        { label: '公司ID', prop: 'bloc_id' },
        { label: '地址', prop: 'address' },
        { label: '启用时间', prop: 'open_time' },
        {
          label: '地址',
          prop: 'thumb',
          align: 'center',
          width: 120,
          render: (h, { row }) => {
            return h('el-image', { attrs: { src: row.thumb }}, row.thumb)
          }
        },
        {
          label: '销售状态',
          prop: 'status',
          width: 80,
          align: 'center',
          render: (h, { row }) => {
            return h('el-tag', {}, row.status === 0 ? '在售' : '下线')
          }
        }
      ],
      tableHandle: [
        {
          label: '修改',
          type: 'primary',
          isPop: false,
          method: (row) => {
            this.editItem(row)
          }
        },
        {
          label: '删除',
          type: 'danger',
          isPop: true,
          method: (row) => {
            this.deleteItem(row)
          }
        }
      ],
      // 表格数据end
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
          { label: '业务名称', type: 'input', value: 'title' }
        ]
      },
      // 检索 end
      tableKey: 0,
      formInline: {
        user: '',
        region: ''
      },
      total: 0,
      list: [],
      demo: {
        title: ''
      },
      temp: {
        id: undefined,
        importance: 1,
        remark: '',
        timestamp: new Date(),
        title: '',
        type: '',
        status: 'published'
      },
      dialogFormVisible: false,
      dialogStatus: '',
      textMap: {
        update: '编辑公司',
        create: '添加公司'
      },
      dialogPvVisible: false,
      pvData: [],
      downloadLoading: false
    }
  },
  computed: {
    ...mapGetters(['accessToken'])
  },
  filters: {
    indexTitle(title) {
      if (title) {
        return title.slice(0, 1)
      }
    }
  },
  created() {
    this.getList()
  },
  methods: {
    // 触发请求
    async resetForm() {
      console.log(this.$refs.form.resetForm())
      await this.$refs.form.resetForm()
    },
    handleSelectionChange(val) {
      console.log('传递来的', val)
    },
    handleRequestSuccess() {
      // this.$message.success("发送成功");
    },
    getList() {
      const that = this
      that.listLoading = true
      fetchUninstalled(that.filterInfo.data).then((response) => {
        const list = response.data.list
        that.list = [...list]
        console.log('列表数据层级测试', that.list)
        that.listLoading = false
      })
    },
    /** 搜索 */
    handleFilter(row) {
      const that = this
      console.log(row)
      that.$set(that.filterInfo, 'data', row)
      console.log('检索前', that.filterInfo.data)
      that.getList()
    },
    /** 重置 */
    handleReset(row) {
      console.log(row)
    },
    /** 焦点失去事件 */
    handleEvent(row) {
      console.log(row)
    },
    handleModifyStatus(row, status) {
      this.$message({
        message: '操作Success',
        type: 'success'
      })
      row.status = status
    },
    sortChange(data) {
      const { prop, order } = data
      if (prop === 'id') {
        this.sortByID(order)
      }
    },
    sortByID(order) {
      if (order === 'ascending') {
        this.listQuery.sort = '+id'
      } else {
        this.listQuery.sort = '-id'
      }
      this.handleFilter()
    },
    handleCreate() {
      const that = this
      this.resetForm()
      console.log(this.list)
      that.dialogStatus = 'create'
      that.dialogFormVisible = true
    },
    handleDeleteAll() {
      console.log('删除')
    },
    handleUpdate(row) {
      this.temp = Object.assign({}, row) // copy obj
      this.temp.timestamp = new Date(this.temp.timestamp)
      this.dialogStatus = 'update'
      this.dialogFormVisible = true
    },
    updateData() {},
    handleDelete(row, index) {
      this.$notify({
        title: 'Success',
        message: 'Delete Successfully',
        type: 'success',
        duration: 2000
      })
      this.list.splice(index, 1)
    },
    handleDownload() {
      this.downloadLoading = true
      import('@/vendor/Export2Excel').then((excel) => {
        const tHeader = ['title', 'create_time']
        const filterVal = [{ title: '12', create_time: '23435345353' }]
        const data = this.formatJson(filterVal)
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'table-list'
        })
        this.downloadLoading = false
      })
    },
    formatJson(filterVal) {
      return this.list.map((v) =>
        filterVal.map((j) => {
          if (j === 'timestamp') {
            return parseTime(v[j])
          } else {
            return v[j]
          }
        })
      )
    },
    getSortClass: function(key) {
      const sort = this.listQuery.sort
      return sort === `+${key}` ? 'ascending' : 'descending'
    },
    installAddons: function(item) {
      ManageInstall(item).then((response) => {
        if (response.code === 200) {
          this.$message.success('安装成功')
          this.getList()
        }
      })
    }
  }
}
</script>
<style type="text/css">
.addons-container {
  background: #f3f4f7;
  padding: 28px;
}
.addons-search {
  padding: 15px 27px;
  background: #fff;
  border-radius: 11px;
}
.main-content {
  display: flow-root;
}
.index-title {
  text-align: center;
  align-items: center;
  display: grid;
  font-size: 53px;
  width: 98px;
  height: 98px;
  background: #f1f1f9;
  border-radius: 30px;
  margin-left: 20px;
  color: #6264a5;
}
.addons-desc {
  font-size: 16px;
  height: 45px;
  line-height: initial;
  margin-top: 27px;
  color: #aeaeae;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.addons-title {
  font-size: 18px;
  line-height: 30px;
}
.el-card {
  height: 189px;
  background: #ffffff;
  border-radius: 8px;
  border: 1px solid #fff;
  position: relative;
}
.content-itm {
  margin-top: 29px;
}
.table-name {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  -webkit-transform: translate(-50%, -50%);
  height: auto;
}
.btn-use {
  width: 82px;
  height: 36px;
  cursor:pointer;
  background: #f1f1f1;
  border-radius: 21px;
  color: #aaaaaa;
  font-size: 14px;
  text-align: center;
  line-height: 36px;
}
.el-pagination {
  margin: 20px auto;
  width: 200px;
}
</style>
