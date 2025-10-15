<template>
  <div class="my-store">
    <div class="logo-top">
      <el-image
        style="width: 98px; height: 36px"
        src="https://www.dandicloud.com/_nuxt/img/logo.bba96d5.png"
      />
    </div>
    <div class="store-cont">
      <div class="add-title">添加商户</div>
      <ele-form
        ref="form"
        v-model="formData"
        v-bind="formConfig"
        :request-fn="handleRequest"
        :is-show-back-btn="false"
        @request-success="handleRequestSuccess"
      />
    </div>
  </div>
</template>

<script>
import {
  fetchList,
  addonscreateItem,
  updateItem,
  deleteItem,
  fetchView,
  getBloc,
  getStorestatus,
  getStoreLabel,
  getCategory
} from '@projectName/views/system/api/addons/store.js'
import { parseTime } from '@projectName/utils'
import { getToken } from '@projectName/utils/auth'
import { getCitylist } from '@projectName/views/system/api/system/system.js'
export default {
  data() {
    return {
      // 表单数据 start
      formData: {
        mid: 0
      },
      formConfig: {
        formDesc: {
          name: {
            type: 'input',
            label: '商户名称'
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
              const arr = [{ id: '', label: '请选择父级商户' }]
              console.log('父级商户', arr.concat(res.data))
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
          category: {
            type: 'cascader',
            label: '商户分类',
            isOptions: true,
            options: getCategory().then((res) => {
              console.log('商户分类', res.data)
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
          // status: {
          //   type: "radio",
          //   label: "审核状态",
          //   isOptions: true,
          //   options: getStorestatus().then((res) => {
          //     console.log("审核状态", res);
          //     return res.data;
          //   }),
          //   default: 1,
          // },
          logo: {
            label: '商户LOGO',
            type: 'image-uploader' // 只需要在这里指定为 image-uploader 即可
          },
          label_link: {
            type: 'checkbox',
            label: '商户标签',
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
          'category',
          'address',
          'longitude',
          'latitude',
          'mobile',
          'logo'
        ]
      }
      // 表单数据 end
    }
  },
  created() {
    this.formData.mid = this.$route.query.id
  },
  methods: {
    handleRequest(data) {
      const that = this
      console.log('handleRequest', data.provinceCityDistrict)
      if (!data.provinceCityDistrict) {
        that.$message.error('请选择省市区')
        return false
      }
      if (!data.category) {
        that.$message.error('请选择商户分类')
        return false
      }
      data.province = data.provinceCityDistrict[0]
      data.city = data.provinceCityDistrict[1]
      data.county = data.provinceCityDistrict[2]

      data.category_id = data.category[0]
      data.category_pid = data.category[1]

      addonscreateItem(data).then((response) => {
        if (response.code === 200) {
          console.log(response)
          that.getList()
          that.dialogFormVisible = false
        }
      })

      return Promise.resolve()
    },
    handleRequestSuccess() {
      // this.$message.success("创建成功");
      // this.$router.push({ path: "/system-store-index" });
    }
  }
}
</script>

<style lang="scss" scoped>
.my-store {
  padding-bottom: 100px;
  .logo-top {
    margin: 61px 127px;
  }
  .store-cont {
    margin: 0 353px;
    padding: 43px 80px;
    background: #fff;

    .add-title {
      font-size: 18px;
      text-align: center;
      margin-bottom: 50px;
      font-weight: bold;
    }
  }
}
::v-deep .el-button {
  width: 222px;
  height: 38px;
}
</style>
