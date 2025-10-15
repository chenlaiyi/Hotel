<template>
  <el-container>
    <el-header>
      <navbar />
    </el-header>
    <el-main>
      <div class="my-store">
        <div class="mystore-cont">
          <el-row v-if="storelist.length > 0" class="row-bg mystore-cont-header" justify="center">
            <el-col :span="16">
              <div class="text-tile text-bold">
                我的经营店铺 ({{ storelist.length }})
              </div>
            </el-col>
            <el-col :span="8">
              <div class="cancel-store justify-end" @click="cendleed = true">
                <div class="zxstore-title text-pointer">
                  已注销的店铺
                  <i class="el-icon-arrow-right" />
                </div>
              </div>
            </el-col>
          </el-row>
          <div v-if="storelist.length > 0" class="store-card-list">
            <el-row :gutter="20">
              <!-- xs、sm、md、lg 和 xl -->
              <el-col
                v-for="(item, i) in storelist"
                :key="i"
                :sm="12"
                :xl="6"
                :md="6"
                :lg="6"
                :xs="24"
                :offset="0"
                class="store-card margin-bottom-sm"
              >
                <div
                  class="card-panel"
                  @click="gostore(item)"
                  @mouseenter="handlemove(i)"
                >
                  <div class="card-itm">
                    <svg-icon
                      icon-class="storegl"
                      :size="24"
                    />
                    <!-- <el-image
                      style="width: 24px; height: 24px"
                      :src="require('@/static/img/store1.png')"
                    /> -->
                    <div
                      class="card-font margin-left-sm store-name"
                      :class="{ 'store-text-active':current === i }"
                    >
                      {{ item.name }}
                    </div>
                    <!-- <div class="card-type margin-left">最近访问</div> -->
                  </div>
                  <div class="card-cont padding-left-sm">
                    <div>
                      创建日期:{{ item.create_time }}
                      <span
                        v-if="current === i"
                        class="store-text-active text-pointer margin-left-xs"
                        @click="handlecencel"
                      >
                        注销
                      </span>
                    </div>
                    <div class="margin-top store-address">
                      商户地址 : {{ item.address }}
                    </div>
                  </div>
                  <div
                    class="store-btn text-center text-pointer"
                    :class="{ 'store-btn-active': current === i }"
                  >
                    进入店铺
                  </div>
                </div>
              </el-col>
            </el-row>
          </div>

          <el-row class="row-bg mystore-cont-header" justify="start">
            <el-col :span="16">
              <div class="text-tile text-bold">店铺分类</div>
            </el-col>
          </el-row>

          <div v-for="(item, index) in addonscatelist" :key="index">
            <!-- xs、sm、md、lg 和 xl -->
            <div class="store-type">
              <!-- <el-image
            class="store-type-img"
            :src="require('@/static/img/home.png')"
          /> -->
              <div class="store-type-title">{{ item.name }}</div>
            </div>
            <div class="store-lists">
              <div class="store-contents">
                <el-row :gutter="20">
                  <el-col
                    v-for="(child, idxs) in item.addons"
                    :key="idxs"
                    class="margin-bottom-sm"
                    :class="{ 'store-contents-active': (index+'#'+idxs) === iscontent }"
                    :sm="12"
                    :xl="8"
                    :md="8"
                    :lg="8"
                    :xs="24"
                  >
                    <div
                      class="store-intro radius-4"
                      @mouseover="changeshow(index+'#'+idxs)"
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
                        <!-- 手机端创建按钮 start-->

                        <div
                          class="hidden-sm-and-up"
                          @click="goaddstore(child.mid)"
                        >
                          <el-button type="primary" size="mini">创建店铺</el-button>
                        </div>
                        <!-- 手机端创建按钮 end-->
                      </div>
                    </div>
                    <!-- 遮罩开始 -->
                    <div v-if=" (index+'#'+idxs) === iscontent" class="store-intro-active hidden-xs-only">
                      <div
                        class="color-white text-center radius-4"
                        :style="{ opacity: (index+'#'+idxs) === iscontent ? '' : 0 }"
                      >
                        <span slot="title" @click="goaddstore(child.mid)">
                          <div class="creat-store text-pointer">
                            + 创建我的经营店铺
                          </div>
                        </span>
                      </div>
                    </div>
                    <!-- 遮罩end -->
                  </el-col>
                </el-row>
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
            <el-checkbox
              v-model="checked"
            >已阅读并同意以上注销须知</el-checkbox>
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
    </el-main>
  </el-container>
</template>

<script>
import Navbar from '@projectName/layout/components/Navbar'
import { getView } from '@projectName/api/store.js'
import { getStorelist } from '@projectName/api/bloc.js'
import { fetchList } from '@projectName/api/addons.js'
import { addonscate } from '@projectName/api/system.js'
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
      activeWidth: 0,
      checked: false,
      houer: false,
      current: 0,
      listLoading: false,
      storelist: [],
      addonscatelist: [],
      iscontent: 0
    }
  },
  created() {
    // this.getList();
    this.getaddonscate()
    this.getsoreList()
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
      this.houer = true
      this.current = index
    },
    handleleave() {
      this.houer = false
      this.current = 0
    },
    addStore() {
      const that = this
      that.centerDialogVisible = !that.centerDialogVisible
    },
    goaddstore(mid) {
      this.$router.push({
        path: '/system-store-addstore',
        query: {
          mid: mid
        }
      })
    },
    gostore(val) {
      this.getStoreList(val)
    },
    getStoreList: function(data) {
      const that = this
      getView(data.store_id).then((res) => {
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
          //   this.$router.replace({
          //     path: "/redirect" + this.$route.fullPath,
          //   });
          const menuType = data.identifie
          this.$store.dispatch('settings/setMenuType', menuType)
          this.$store.dispatch(
            'settings/setPlugins',
            data.addons ?? data.addons.addons
          )
          const path = '/' + menuType + '/default/index.vue'
          this.$router.push({ path: path })
          this.showStore = false
          this.$emit('hideStore', {})
        })
      })
    },
    changeshow(idxs) {
      this.iscontent = idxs
    }
  }
}
</script>

<style lang="scss" scoped>
.el-main {
  height: calc(100vh - 60px);
}
.my-store {
  .mystore-cont {
    margin: 0 auto;
    max-width: 1200px;
    .mystore-cont-header {
      line-height: 24px;
      padding: 20px;
      margin-bottom: 15px;
      padding-right: 5px;
      border-bottom: 1px solid rgb(112, 112, 112, 0.1);
    }
    .store-name {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .store-address {
      overflow: hidden;
      text-overflow: ellipsis;
      display: -webkit-box;
      // 下面这句用来控制行数
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      height: 40px;
      line-height: 20px;
    }
    .store-title {
      color: #040404;
    }
    .text-tile {
      font-size: 15px;
    }
    .zxstore-title {
    }
    .cancel-store {
      display: flex;
      color: #a4a4a7;
      font-size: 11px;
    }
    .store-card-list {
      .store-card {
        .card-panel {
          height: 230px;
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
              line-height: 24px;
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
          .store-text-active {
            color: #5c5fc9;
          }
          .store-btn-active {
            background: #5c5fc9;
            color: #fff;
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
        font-size: 15px;
        margin-left: 35px;
      }
    }
    .store-lists {
      // position: relative;
      .store-contents {
        // display: flex;
        // flex-wrap: wrap;
        // margin-left: 25px;
        .store-intro {
          display: flex;
          background: #ffffff;
          align-items: center;
          // margin: 0 25px 25px 0;
          padding: 0 33px;
          height: 113px;
          .shop-introduced {
            margin-left: 26px;
            color: #333333;
            font-size: 16px;
            .shop-fun {
              color: #999999;
              font-size: 10px;
              margin-top: 15px;
            }
          }
        }
        .store-contents-active {
          position: relative;
          align-items: center;
          // padding:0!important;
          // padding-left: 10px;
          // padding-right: 10px;
          .store-intro-active {
            background: rgb(0, 0, 0, 0.6);
            height: 113px;
            width: 100%;
            top: 0;
            left: 0;
            right: 0;
            position: absolute;
            flex-wrap: wrap;
            border-radius: 4px;
            z-index: 999;
            line-height: 8;
            align-items: center;
            text-align: center;
          }
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
  font-size: 16px;
  line-height: 113px;
}
::v-deep .el-dialog__header {
  border-bottom: 1px solid rgb(112, 112, 112, 0.16);
}
</style>
