<template>
  <div class="app-container">
    <div class="buy-use">
      <div>购买应用</div>
    </div>
    <div class="sub-store">
      <div class="float-right text-pointer" @click="orderRegis">订购记录</div>
      <el-row class="margin">
        <el-col
          v-for="(item, index) in list"
          :key="index"
          :md="12"
          :xs="24"
          :xl="8"
          :lg="8"
        >
          <el-card class="sub-itm" :body-style="{ padding: 0 }" shadow="hover">
            <div class="ty-btn">体验</div>
            <el-col :span="24">
              <el-col :span="5">
                <el-image
                  style="width: 56px; height: 56px"
                  :src="item.logo"
                />
              </el-col>
              <el-col :span="19">
                <div class="addons-title">{{ item.addons.title }}</div>
                <div class="block">
                  <el-rate
                    v-model="value"
                    disabled
                    text-color="#FFC400"
                  />
                </div>
                <div class="bom-cont">
                  <div class="addons-desc">
                    {{ item.addons.ability }}
                  </div>
                  <div class="ex-btn addons-title text-pointer" @click="gobuy(item.goods_id)">
                    立即购买
                  </div>
                </div>
              </el-col>
            </el-col>
          </el-card>
        </el-col>
      </el-row>
    </div>
  </div>
</template>
<script>
import { getorderlist } from '@projectName/api/service/goods.js'
export default {
  data() {
    return {
      value: 5,
      list: [],
      total: 0,
      listLoading: false
    }
  },
  created() {
    this.getList()
  },
  methods: {
    getList() {
      const that = this
      that.listLoading = true
      getorderlist().then((response) => {
        console.log('response', response)
        that.total = response.data.total
        const list = response.data
        that.list = [...list]
        console.log('列表数据层级测试', that.list)
        that.listLoading = false
      })
    },
    gobuy(mid) {
      const that = this
      that.$router.push({
        name: 'service-buy',
        params: {
          id: mid
        }
      })
    },
    orderRegis() {
      const that = this
      that.$router.push({ name: 'service-register' })
    }
  }
}
</script>
<style type="text/css">
.buy-use {
  color: #4d4d4d;
  font-size: 12px;
  padding: 22px;
  background-color: #fff;
  border-bottom: 1px solid #dbdbdb;
  border-radius: 15px 15px 0 0;
}
.sub-store {
  background-color: #fff;
  padding: 35px 142px;
  border-radius: 0 0 15px 15px;
}
.sub-itm {
  margin: 30px 15px;
  padding: 20px;
  border-radius: 4px;
  border: 1px solid #e8e8e8;
}
.ty-btn {
  width: 34px;
  height: 20px;
  border-radius: 4px;
  border: 1px solid #aaadec;
  color: #aaadec;
  font-size: 11px;
  text-align: center;
  line-height: 20px;
  float: right;
}
.bom-cont {
  display: flex;
  justify-content: space-between;
}
.addons-desc {
  font-size: 11px;
  margin-top: 5px;
  color: #4d4d4d;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  height: 35px;
  width: 70%;
}
.ex-btn {
  width: 87px;
  height: 27px;
  background: #5357cb;
  box-shadow: 0px 3px 3px 1px rgba(83, 87, 203, 0.29);
  border-radius: 14px;
  color: #fff;
  text-align: center;
  line-height: 27px;
}
.addons-title {
  font-size: 13px;
}
.float-right {
  float: right;
  color: #7174ec;
  font-size: 13px;
}
.margin {
  margin: 0 170px;
}
</style>
