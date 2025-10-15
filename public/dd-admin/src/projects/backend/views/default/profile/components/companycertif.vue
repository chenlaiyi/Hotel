<template>
  <div class="app-container">
    <ele-form
      ref="form"
      v-model="formData"
      v-bind="formConfig"
      :request-fn="handleRequest"
      :is-show-back-btn="false"
      @request-success="handleRequestSuccess"
    >
      <!-- <el-col :span="24">
        <el-form-item label="审核进度">
          <el-steps
            :space="200"
            :active="formData.status"
            finish-status="success"
          >
            <el-step title="待申请"></el-step>
            <el-step title="申请中"></el-step>
            <el-step title="已认证"></el-step>
          </el-steps>
        </el-form-item>
      </el-col> -->
    </ele-form>
  </div>
</template>

<script>
import { getuserinfo } from '@projectName/api/profile/profile.js'
import { mapGetters } from 'vuex'
import {
  updateItem,
  fetchView
} from '@projectName/api/addons/bloc.js'
import { getCitylist } from '@projectName/api/system.js'
export default {
  data() {
    return {
      // 表单数据 start
      formData: {
        bloc_id: 0,
        address: {
          address: '12321'
        }
      },
      formConfig: {
        formSpan: 24,
        labelPosition: 'top',
        formDesc: {
          business_name: {
            type: 'input',
            label: '公司名称'
          },
          logo: {
            type: 'image-uploader',
            label: 'logo'
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
            attrs: async(data) => {
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
          }
        },
        order: [
          'business_name',
          'logo',
          'provinceCityDistrict',
          'address',
          'longitude',
          'latitude',
          'recommend',
          'telephone',
          'license_no',
          'license_name'
        ]
      },
      dialogFormVisible: false
    }
  },
  computed: {
    ...mapGetters(['accessToken'])
  },
  created() {
    this.getDetail()
  },
  methods: {
    getDetail() {
      const that = this
      getuserinfo().then((res) => {
        const bloc_id = res.data.userinfo.user.bloc_id
        that.formData.bloc_id = res.data.userinfo.user.bloc_id
        if (bloc_id) {
          that.editItem(bloc_id)
        }
      })
    },
    // 更新
    handleRequest(data) {
      console.log('datassssss', data)
      const that = this
      updateItem(data).then((res) => {
        console.log('更新', res)
        that.editItem(res.data.bloc_id)
        that.dialogFormVisible = false
      })
      return Promise.resolve()
    },
    handleRequestSuccess() {
    },
    editItem(id) {
      const that = this
      // that.formData = { ...row }
      fetchView(id).then((res) => {
        that.formData = { ...res.data }
        this.$message.success(res.message)
      })
    }
  }
}
</script>
