<template>
  <div class="app-container">
    <router-view>23</router-view>
    <div class="top-search">
      <div class="bg-search">
        <!-- 公共操作 star -->
        <fire-oper-menu
          :show-excel-export="true"
          :show-excel-import="true"
          @ExcelExport="handleDownload"
          @handleCreate="handleCreate"
          @handleDeleteAll="handleDeleteAll"
        />
        <!-- 公共操作 end -->

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

      </div>
    </div>

    <!-- 数据列表 start -->
    <fire-table
      ref="table"
      :list="list"
      :total="total"
      row-key="bloc_id"
      :list-loading="listLoading"
      :list-query="filterInfo.data"
      :columns="tableColumns"
      :handle="tableHandle"
      @handleSelectionChange="handleSelectionChange"
    >
      <template slot="status" slot-scope="{ row }">
        <el-tag>{{ statusList[row.status].text }}</el-tag>
      </template>

      <template slot="store" slot-scope="{ row }">
        <el-tag @click="storeHandleOpen">添加门店</el-tag>
      </template>

      <template slot="setting" slot-scope="{ row }">
        <el-button
          type="primary"
          size="mini"
          style="margin-right: 10px"
          @click="setting(row, index)"
        >配置</el-button>
      </template>
    </fire-table>
    <!-- 数据列表 end -->
    <el-dialog
      :close-on-click-modal="false"
      width="80%"
      :title="textMap[dialogStatus]"
      :visible.sync="dialogFormVisible"
    >
      <ele-form
        ref="form"
        v-model="formData"
        v-bind="formConfig"
        :request-fn="handleRequest"
        :is-show-back-btn="false"
        @request-success="handleRequestSuccess"
      />
    </el-dialog>
<!-- 门店 start -->
    <el-drawer
      title="门店管理"
      :visible.sync="storeDialogVisible"
      direction="rtl"
      :before-close="storeHandleClose"
      append-to-body
    >
      门店列表展示
    </el-drawer>
<!-- 门店 start -->

  </div>
</template>

<script>
import { FormatDate } from '@/filters'
import { mapGetters } from 'vuex'
import {
  fetchList,
  createItem,
  updateItem,
  deleteItem,
  fetchView,
  getParentbloc,
  getBlocstatus,
  getLevels
} from '@projectName/views/system/api/addons/bloc.js'
// import waves from '@projectName/directive/waves' // waves directive
import { parseTime } from '@projectName/utils'
import { getCitylist } from '@projectName/views/system/api/system/system.js'
export default {
  filters: {
    formatterDate(val) {
      if (val) {
        return FormatDate(val, 'yyyy-MM-dd hh:mm:ss')
      } else {
        return '-'
      }
    }
  },
  data() {
    return {
      storeDialogVisible: false,
      statusList: [],
      // 表格数据 start
      tableColumns: [
        {
          label: '公司ID',
          prop: 'bloc_id',
          width: 230
        },
        {
          label: '公司名称',
          prop: 'business_name'
        },
        {
          label: '地址',
          prop: 'address'
        },
        {
          label: '启用时间',
          prop: 'open_time'
        },
        {
          label: '有效期',
          prop: 'end_time'
        },
        {
          label: '审核状态',
          prop: 'status',
          width: 80,
          align: 'center',
          slot: 'status'
        },
        {
          label: '门店',
          prop: 'store',
          width: 80,
          align: 'center',
          slot: 'store'
        },
        {
          label: '集团配置',
          prop: 'setting',
          width: 80,
          align: 'center',
          slot: 'setting'
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
          limit: 20,
          name: '',
          parent_id: '',
          description: ''
          // sex: 1,
          // date: null,
          // dateTime: null,
          // range: null
        },
        fieldList: [
          {
            label: '公司名称',
            type: 'input',
            value: 'Bloc[business_name]'
          }
        ]
      },
      // 检索 end
      tableKey: 0,
      formInline: {
        user: '',
        region: ''
      },
      // 表单数据 start
      formData: {},
      formConfig: {
        formDesc: {
          business_name: {
            type: 'input',
            label: '公司名称'
          },
          logo: {
            type: 'image-uploader',
            label: 'logo'
          },
          pid: {
            label: '父级公司',
            // 只需要在这里指定为 tree-select 即可
            type: 'tree-select',
            // 属性参考: https://vue-treeselect.js.org/
            attrs: {
              multiple: false,
              clearable: true
            },
            options: getParentbloc().then((res) => {
              console.log('父级公司', res.data)
              const arr = [
                {
                  id: 0,
                  label: '请选择父级公司'
                }
              ]
              console.log('父级公司', arr.concat(res.data))
              return arr.concat(res.data)
            })
          },
          provinceCityDistrict: {
            type: 'cascader',
            label: '所在地区',
            isOptions: true,
            options: getCitylist().then((res) => {
              console.log('城市列表', res.data)
              return res.data
            })
          },
          open_time: {
            type: 'date',
            label: '启用时间',
            attrs: {
              valueFormat: 'yyyy-MM-dd'
            }
          },
          end_time: {
            type: 'date',
            label: '有效期',
            attrs: {
              valueFormat: 'yyyy-MM-dd'
            }
          },
          address: {
            type: 'bmap',
            label: '具体地址',
            valueFormatter(val) {
              console.log(val)
              // 最终提交时
              return val && val.address ? val.address : null
            },
            vif: (data) => data.provinceCityDistrict,
            // 这里必须制定 optionsLinkageFields 做为关联字段，当字段值发生变化时，会重新出发请求
            optionsLinkageFields: ['provinceCityDistrict'],
            attrs: (data) => {
              // 动态设置地图中心
              if (data.provinceCityDistrict && !data.address) {
                return {
                  zoom: 19,
                  center: this.$refs['form'].$refs.provinceCityDistrict[0].$refs.cascader.getCheckedNodes()[0].label
                }
              } else {
                return {
                  zoom: 19
                }
              }
            },
            displayFormatter: (address) => {
              console.log('address', address, typeof address)
              // 设置显示的值
              if (typeof address !== 'undefined') {
                this.formData.latitude = address.lat
                this.formData.longitude = address.lng
                return address
              }
            },
            props: {
              showAddressBar: true,
              autoLocation: true
            }
          },
          longitude: {
            type: 'input',
            label: '经度',
            attrs: {
              disabled: true
            }
          },
          latitude: {
            type: 'input',
            label: '维度',
            attrs: {
              disabled: true
            }
          },
          recommend: {
            type: 'textarea',
            label: '简介'
          },
          introduction: {
            type: 'textarea',
            label: '详细介绍'
          },
          special: {
            type: 'input',
            label: '特色'
          },
          telephone: {
            type: 'input',
            label: '联系电话'
          },
          license_no: {
            type: 'input',
            label: '营业执照注册号'
          },
          license_name: {
            type: 'input',
            label: '营业执照公司名称'
          },
          store_num: {
            type: 'input',
            label: '可添加门店数量'
          },
          level_num: {
            type: 'select',
            label: '公司等级',
            isOptions: true,
            options: getLevels().then((res) => {
              console.log('用户等级', res.data)
              return res.data
            })
          },
          status: {
            type: 'radio',
            label: '审核状态',
            isOptions: true,
            options: getBlocstatus().then((res) => {
              return res.data
            }),
            default: 1
          }
        },
        order: [
          'business_name',
          'logo',
          'pid',
          'provinceCityDistrict',
          'open_time',
          'end_time',
          'address',
          'longitude',
          'latitude',
          'status',
          'recommend',
          'special',
          'telephone',
          'license_no',
          'license_name',
          'level_num'
        ]
      },
      // 表单数据 end
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
  created() {
    this.getList()
    getBlocstatus().then((response) => {
      this.statusList = response.data
    })
  },
  methods: {
    storeHandleOpen() {
      this.storeDialogVisible = true
    },
    storeHandleClose(done) {
      this.$confirm('确认关闭？')
        .then(_ => {
          done()
        })
        .catch(_ => {})
    },
    // 触发请求
    async resetForm() {
      console.log(this.$refs.form.resetForm())
      await this.$refs.form.resetForm()
    },
    handleSelectionChange(val) {
      console.log('传递来的', val)
    },
    // 更新，和创建
    handleRequest(data) {
      console.log(data)
      const that = this
      if (that.dialogStatus === 'create') {
        createItem(data).then((response) => {
          console.log(response)
          that.getList()
          that.dialogFormVisible = false
        })
      } else if (that.dialogStatus === 'update') {
        updateItem(data).then((res) => {
          console.log('更新', res)
          that.getList()
          that.dialogFormVisible = false
        })
      }

      return Promise.resolve()
    },
    // 单行数据删除
    deleteItem(row) {
      const that = this
      deleteItem(row.bloc_id).then((res) => {
        console.log('更新', res)
        that.getList()
        that.dialogFormVisible = false
      })
    },
    handleRequestSuccess() {
      this.$message.success('发送成功')
    },
    getList() {
      const that = this
      that.listLoading = true
      fetchList(that.filterInfo.data).then((response) => {
        console.log('response', response)
        that.total = response.data.dataProvider.total
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
    editItem(row) {
      const that = this
      // that.formData = { ...row }
      that.dialogFormVisible = true
      console.log('formData', row)
      this.dialogStatus = 'update'

      fetchView(row.bloc_id).then((res) => {
        that.formData = {
          ...res.data
        }
        console.log('view', that.formData)
      })
    },
    viewItem(row) {
      const that = this
      that.$router.push({
        path: './bloc-view.vue',
        query: {
          bloc_id: row.bloc_id
        }
      })
    },
    setting(row, index) {
      const that = this
      that.$router.push({
        name: 'system-settings-index',
        params: {
          bloc_id: row.bloc_id
        }
      })
    },
    handleDownload() {
      this.downloadLoading = true
      import('@/vendor/Export2Excel').then((excel) => {
        const tHeader = ['title', 'create_time']
        const filterVal = [
          {
            title: '12',
            create_time: '23435345353'
          }
        ]
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
    }
  }
}
</script>
<style scoped>
</style>
