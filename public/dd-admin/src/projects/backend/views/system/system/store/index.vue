<template>
  <div class="my-store">
    <el-header>
      <navbar />
    </el-header>
    <div class="mystore-cont">
      <el-row type="flex" class="row-bg" justify="center">
        <el-col :span="12">
          <div class="store-title text-sm text-bold">我的经营店铺 (3)</div>
        </el-col>
        <el-col :span="12">
          <div
            class="cancel-store padding-xs justify-end"
            @click="cendleed = true"
          >
            <div class="zxstore-title text-pointer">已注销的店铺</div>
            <i class="el-icon-arrow-right" />
          </div>
        </el-col>
      </el-row>
      <div class="store-card-list">
        <div
          v-for="(item, i) in storelist"
          class="store-card"
          @mouseenter="handlemove(i)"
        >
          <div v-show="current != i" class="card-panel" @click="gostore(item)">
            <div class="card-itm">
              <el-image
                style="width: 24px; height: 24px"
                :src="require('@/static/img/store1.png')"
              />
              <div class="card-font">{{ item.bloc.telephone }}</div>
              <div class="card-type margin-left">最近访问</div>
            </div>
            <div class="card-cont padding-left-sm">
              <div>创建日期:{{ item.create_time }}</div>
              <div class="margin-top">店铺 : {{ item.name }}</div>
            </div>
            <div class="store-btn text-center text-pointer">进入店铺</div>
          </div>
          <div
            v-if="houer && current === i"
            class="card-panel"
            @mouseleave="handleleave"
            @click="gostore(item)"
          >
            <div class="card-itm">
              <el-image
                style="width: 24px; height: 24px"
                :src="require('@/static/img/store1.png')"
              />
              <div class="card-font">{{ item.bloc.telephone }}</div>
              <div class="card-type margin-left">最近访问</div>
            </div>
            <div class="card-cont padding-left-sm">
              <div>创建日期:{{ item.create_time }}</div>
              <div class="margin-top">
                店铺 : {{ item.name }}
                <span class="color margin-left-xs" @click="handlecencel">注销</span>
              </div>
            </div>
            <div class="storeing-btn color text-pointer">进入店铺</div>
          </div>
        </div>
      </div>
      <div class="store-title text-sm text-bold margin-bottom-xl">店铺分类</div>
      <div v-for="(item, index) in addonscatelist" :key="index">
        <div class="store-type">
          <!-- <el-image
            class="store-type-img"
            :src="require('@/static/img/home.png')"
          /> -->
          <div class="store-type-title">{{ item.name }}</div>
        </div>
        <div class="store-list">
          <div class="store-contents">
            <div
              v-for="(child, idxs) in item.addons"
              :key="idxs"
              class="store-intro radius-4"
              @mouseover="changeshow(idxs)"
            >
              <div>
                <el-image
                  class="store-intro-img"
                  style="width: 61px; height: 61px"
                  :src="child.logo"
                />
              </div>

              <div class="shop-introduced">
                <div>{{ child.title }}</div>
                <div class="shop-fun">
                  {{ child.ability }}
                </div>
              </div>
            </div>
          </div>
          <div class="store-contents-active">
            <div
              v-for="(child, index) in item.addons"
              :key="index"
              class="store-intro-active color-white text-center radius-4"
              :style="{ opacity: index === iscontent ? '' : 0 }"
              @mouseover="changeshow(index)"
            >
              <span slot="title" @click="goaddstore(child.id)">
                <div class="creat-store text-pointer">+ 创建我的经营店铺</div></span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 注销店铺 -->
    <el-dialog
      title="店铺注销"
      :visible.sync="handlecancel"
      width="70%"
      :before-close="handleClose"
    >
      <div class="cancel-store justify-end padding-xs">
        <div class="cancel-tips padding-xs">
          <div class="cancel-itm">
            <div class="cancel-icon">
              <i class="el-icon-warning" style="color: #ffaa00" />
            </div>
            <div>
              <div>您正在进行店铺注销流程，请您阅读以下注销须知</div>
              <div>
                <p>
                  1、店铺注销后，您将无法通过店滴云平台进入店铺管理台。同时，您店铺内的商品数据、素材中心、交易数据、
                  资产数据等将一并被删除，且无法恢复；
                </p>
                <p>
                  2、店铺注销后，您店铺内的用户将无法通过店滴云平台访问您的店铺使用课程、优惠券、积分等权益，请您积极
                  与消费者协商有关事宜。
                </p>
                <p>
                  3、本次注销不代表注销前的行为和相关责任得到豁免和减轻，您仍需对您的店铺经营行为承担相应的责任。
                </p>
              </div>
            </div>
          </div>
        </div>
        <el-form ref="form" :model="cancause" label-width="120px">
          <el-form-item label="注销原因描述:">
            <el-col :span="11">
              <el-input
                v-model="cancause.desc"
                type="textarea"
                maxlength="50"
                show-word-limit
                :autosize="{ minRows: 6, maxRows: 10 }"
                placeholder="请输入注销原因"
              />
            </el-col>
          </el-form-item>
          <el-form-item label="短信验证码:">
            <el-col :span="11">
              <el-input v-model="cancause.code" placeholder="验证码">
                <span slot="suffix">获取验证码</span>
              </el-input>
              <div class="code-tips margin-left-xs">
                验证码将发送至账号关联手机号，请注意查收
              </div>
            </el-col>
          </el-form-item>
        </el-form>
      </div>
      <span slot="footer" class="dialog-footer justify-between">
        <el-checkbox v-model="checked">已阅读并同意以上注销须知</el-checkbox>
        <div>
          <el-button @click="handlecancel = false">取 消</el-button>
          <el-button
            type="danger"
            @click="dialogVisible = false"
          >注销店铺</el-button>
        </div>
      </span>
    </el-dialog>

    <!-- 已注销的店铺 -->
    <el-dialog
      title="已注销店铺"
      :visible.sync="cendleed"
      width="40%"
      :before-close="handleClose"
    >
      <span>这是一段信息</span>
    </el-dialog>
  </div>
</template>

<script>
import Navbar from '@projectName/layout/components/Navbar'
import { getView } from '@projectName/views/system/api/addons/store.js'
import { getStorelist } from '@projectName/views/system/api/addons/bloc.js'
import { fetchList } from '@projectName/views/system/api/addons/addons.js'
import { addonscate } from '@projectName/views/system/api/system/system.js'
export default {
  components: {
    Navbar
  },
  data() {
    return {
      centerDialogVisible: false,
      handlecancel: false,
      cendleed: false,
      cancause: {
        code: '',
        desc: ''
      },
      checked: false,
      houer: false,
      current: null,
      listLoading: false,
      storelist: [],
      addonscatelist: [],
      iscontent: 0
    }
  },
  created() {
    // this.getList();
    this.getsoreList()
    this.getaddonscate()
  },
  methods: {
    getaddonscate() {
      const that = this
      addonscate().then((response) => {
        that.addonscatelist = response.data.dataProvider.allModels
      })
    },
    getsoreList() {
      const that = this
      that.listLoading = true
      getStorelist({}).then((response) => {
        that.storelist = response.data
      })
    },
    // 注销
    handlecencel() {
      const that = this
      that.handlecancel = true
    },
    handleClose(done) {
      this.handlecancel = false
    },
    handlemove(index) {
      console.log('handlemove')
      this.houer = true
      this.current = index
    },
    handleleave() {
      this.houer = false
      this.current = null
    },
    addStore() {
      const that = this
      that.centerDialogVisible = !that.centerDialogVisible
    },
    goaddstore(id) {
      this.$router.push({
        path: '/system-store-addstore',
        query: {
          id: id
        }
      })
    },
    gostore(val) {
      this.getStoreList(val)
    },
    getStoreList: function(data) {
      const that = this
      console.log(data)
      getView(data.store_id).then((res) => {
        console.log('商户数据', res)
        that.$store.dispatch('elForm/changeHEaders', {
          key: 'store-id',
          value: res.data.store_id
        })
        that.$store.dispatch('elForm/changeHEaders', {
          key: 'bloc-id',
          value: res.data.bloc_id
        })
        that.$store.dispatch('elForm/changeSetting', {
          key: 'attachmentUrl',
          value: res.data.config.attachmentUrl
        })
        that.$store.dispatch('app/setBlocs', res.data)
        this.$nextTick(() => {
          this.$router.replace({
            path: '/redirect' + this.$route.fullPath
          })
          this.showStore = false
          this.$emit('hideStore', {})
        })
      })
      this.$router.push({ name: 'dashboard' })
    },
    changeshow(idxs) {
      this.iscontent = idxs
      console.log(this.iscontent, idxs)
    }
  }
}
</script>

<style lang="scss" scoped>
.my-store {
  .mystore-cont {
    margin: 34px 300px;

    .store-title {
      color: #040404;
    }
    .zxstore-title {
      padding-right: 5px;
    }
    .cancel-store {
      display: flex;
      color: #a4a4a7;
      font-size: 11px;
    }
    .store-card-list {
      display: flex;
      flex-wrap: wrap;
      .store-card {
        width: 25%;
        height: 230px;
        .card-panel {
          width: 95%;
          height: 217px;
          background: #ffffff;
          border-radius: 6px;
          border: 1px solid #f1f1f1;
          margin: auto;

          .card-itm {
            display: flex;
            color: #9b9b9b;
            margin: 38px 27px 33px;
            .card-font {
              font-size: 16px;
            }
            .card-type {
              font-size: 10px;
              -webkit-transform: scale(0.8);
            }
          }
          .card-cont {
            color: #9b9b9b;
            font-size: 14px;
            .margin-top {
              margin-top: 17px;
            }
          }
          .store-btn {
            margin: 15px 47px;
            // width: 264px;
            height: 37px;
            font-size: 13px;
            color: #9a9797;
            background: #f2f2f2;
            border-radius: 4px;
            line-height: 37px;
          }
          .storeing-btn {
            margin: 15px 47px;
            // width: 264px;
            height: 37px;
            font-size: 13px;
            background: #ecedff;
            border-radius: 4px;
            text-align: center;
            line-height: 37px;
          }
          .color {
            color: #5c5fc9;
          }
        }
      }
    }
    .store-type {
      display: flex;
      margin: 25px 7px;
      height: 25px;

      .store-type-img {
        width: 16px;
        height: 16px;
        margin: auto 0;
      }

      .store-type-title {
        color: #333333;
        font-size: 23px;
        margin-left: 35px;
      }
    }
    .store-list {
      position: relative;
      .store-contents {
        display: flex;
        flex-wrap: wrap;
        margin-left: 25px;
        .store-intro {
          display: flex;
          width: 31%;
          background: #ffffff;
          align-items: center;
          margin: 0 25px 25px 0;
          padding: 0 33px;
          height: 113px;

          .shop-introduced {
            margin-left: 26px;
            color: #333333;
            font-size: 27px;
            .shop-fun {
              color: #999999;
              font-size: 10px;
              margin-top: 15px;
            }
          }
        }
      }
      .store-contents-active {
        top: 0;
        left: 0;
        position: absolute;
        display: flex;
        flex-wrap: wrap;
        margin-left: 25px;
        .store-intro-active {
          display: flex;
          width: 31%;
          height: 113px;
          background: rgb(0, 0, 0, 0.6);
          align-items: center;
          margin: 0 25px 25px 0;
          padding: 0 33px;
        }
      }
    }
  }
  .cancel-store {
    .cancel-tips {
      width: 100%;
      height: 100%;
      background: #fffbee;
      border-radius: 3px;
      border: 1px solid #ffaa00;
      color: #362e2e;
      line-height: 20px;
      font-size: 7px;
      margin-bottom: 40px;
      .cancel-itm {
        display: flex;
      }
      .cancel-icon {
        width: 20px;
        font-size: 17px;
      }
    }
    .code-tips {
      color: #c5c5c5;
      font-size: 6px;
    }
  }
  .dialog-footer {
    display: flex;
  }
}
::v-deep .add .el-dialog {
  background: rgb(10, 10, 10, 0.67) !important;
  height: 81px;
  border-radius: 4px;
  line-height: 81px;
}
::v-deep .add .el-dialog__header {
  padding: 0 !important;
}
.creat-store {
  color: #fff;
  font-size: 10px;
}
::v-deep .el-dialog__header {
  border-bottom: 1px solid rgb(112, 112, 112, 0.16);
}
</style>
