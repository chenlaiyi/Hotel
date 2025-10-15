<template>
  <div class="app-container">
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
    <!-- 公共操作 end -->

    <!-- 数据列表 start -->
    <fire-table
      ref="table"
      :list="list"
      :total="total"
      row-key="store_id"
      :list-loading="listLoading"
      :list-query="filterInfo.data"
      :columns="tableColumns"
      :handle="tableHandle"
      @getList="getList"
      @handleSelectionChange="handleSelectionChange"
    >
      <template slot="name" slot-scope="{ row }">
        <div style="color: #4043a8">
          {{ row.bloc ? row.bloc.business_name : "无" }}
        </div>
      </template>
      <template slot="status" slot-scope="{ row }">
        <div>
          {{ statuslits[row.status] }}
        </div>
      </template>
    </fire-table>

    <!-- 数据列表 end -->
    <el-dialog
      v-if="dialogFormVisible"
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
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import {
  fetchList,
  createItem,
  updateItem,
  deleteItem,
  fetchView,
  getBloc,
  getStorestatus,
  getStoreLabel
} from '@projectName/views/system/api/addons/store.js'
// import waves from '@projectName/directive/waves' // waves directive
import { parseTime } from '@projectName/utils'
import { getCitylist } from '@projectName/views/system/api/system/system.js'
export default {
  data() {
    return {
      // 表格数据 start
      statuslits: [
        '待审核',
        '已通过',
        '审核不通过'
      ],
      tableColumns: [
        { label: '门店ID', prop: 'store_id',
          width: 230 },
        { label: '门店名称', prop: 'name' },
        { label: '公司名称', prop: 'name', slot: 'name' },
        { label: '地址', prop: 'address' },
        { label: '联系电话', prop: 'mobile' },
        { label: '审核状态', prop: 'status', slot: 'status' }
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
          pageSize: 10
        },
        fieldList: [
          { label: '门店名称', type: 'input', value: 'BlocStoreSearch[name]' }
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
          name: {
            type: 'input',
            label: '门店名称'
          },
          bloc_id: {
            label: '所属公司',
            // 只需要在这里指定为 tree-select 即可
            type: 'tree-select',
            // 属性参考: https://vue-treeselect.js.org/
            attrs: {
              multiple: false,
              clearable: true
            },
            options: getBloc().then((res) => {
              console.log('所属公司', res.data)
              const arr = [{ id: 0, label: '请选择公司' }]
              console.log('父级门店', arr.concat(res.data))
              return arr.concat(res.data)
            })
          },
          provinceCityDistrict: {
            type: 'cascader',
            label: '所在地区',
            isOptions: true,
            options: getCitylist().then((res) => {
              console.log('城市列表', res)
              return res.data
            })
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
                  center:
                    this.$refs[
                      'form'
                    ].$refs.provinceCityDistrict[0].$refs.cascader.getCheckedNodes()[0]
                      .label
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
          mobile: {
            type: 'input',
            label: '联系电话'
          },
          status: {
            type: 'radio',
            label: '审核状态',
            isOptions: true,
            options: getStorestatus().then((res) => {
              console.log('审核状态', res)
              return res.data
            }),
            default: 1
          },
          logo: {
            label: '门店LOGO',
            type: 'image-uploader' // 只需要在这里指定为 image-uploader 即可
          },
          label_link: {
            type: 'checkbox',
            label: '门店标签',
            isOptions: true,
            options: getStoreLabel().then((res) => {
              return res.data
            })
          }
        },
        order: [
          'name',
          'bloc_id',
          'provinceCityDistrict',
          'address',
          'longitude',
          'latitude',
          'status',
          'mobile',
          'logo'
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
        update: '编辑门店',
        create: '添加门店'
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
    // 更新，和创建
    handleRequest(data) {
      const that = this

      console.log('handleRequest', data.provinceCityDistrict)

      if (!data.provinceCityDistrict) {
        that.$message.error('请选择省市区')
        return false
      }

      data.province = data.provinceCityDistrict[0]
      data.city = data.provinceCityDistrict[1]
      data.county = data.provinceCityDistrict[2]

      if (that.dialogStatus === 'create') {
        createItem(data).then((response) => {
          console.log(response)
          that.getList()
          that.dialogFormVisible = false
        })
      } else if (that.dialogStatus === 'update') {
        console.log('更新', data)

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
      deleteItem(row.store_id).then((res) => {
        console.log('更新', res)
        that.getList()
        that.dialogFormVisible = false
      })
    },
    handleRequestSuccess() {
      // this.$message.success('发送成功')
    },
    getList() {
      const that = this
      that.listLoading = true
      fetchList(that.filterInfo.data).then((response) => {
        console.log('response', response)
        that.total = response.data.dataProvider.total
        const list = response.data.dataProvider.allModels
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
        message: '删除成功',
        type: 'success',
        duration: 2000
      })
      this.list.splice(index, 1)
    },
    editItem(row) {
      const that = this
      that.dialogFormVisible = true
      this.dialogStatus = 'update'
      fetchView(row.store_id).then((res) => {
        that.formData = res.data
        console.log('view', res)
      })
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
    }
  }
}
</script>
<style scoped>
::v-deep .el-form-item--medium .el-form-item__content{
    text-align: initial;
}
</style>
