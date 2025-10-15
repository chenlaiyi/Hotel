<template>
  <div>
    <div class="planel-group">
      <!-- <div class="text-bold text-sm padding-sm">实时概况</div> -->

      <el-row :gutter="20" class="lancel-item" justify="space-around">
        <el-col class="padding-bottom" :sm="6" :xl="6" :lg="6" :md="12">
          <div class="flex">
            <div class="lancel-radius">
               <svg-icon
                class="icon sub-el-icon"
                icon-class="home-02"
                :size="62"
              />
            </div>
            <div class="margin-left-sm text-center lancel-right">
              <div class="lancel-txt padding-bottom-xs">今日商家访客数</div>
              <div class="lancel-num">0</div>
            </div>
          </div>
        </el-col>

        <el-col class="padding-bottom" :sm="6" :xl="6" :lg="6" :md="12">
          <div class="flex">
            <div class="lancel-radius">
               <svg-icon
                class="icon sub-el-icon"
                icon-class="home-04"
                :size="62"
              />
            </div>
            <div class="margin-left-sm text-center lancel-right">
              <div class="lancel-txt padding-bottom-xs">今日新增用户</div>
              <div class="lancel-num">0</div>
            </div>
          </div>
        </el-col>
        <el-col class="padding-bottom" :sm="6" :xl="6" :lg="6" :md="12">
          <div class="flex">
            <div class="lancel-radius">
                 <svg-icon
                  class="icon sub-el-icon"
                  icon-class="home-05"
                  :size="62"
                />
            </div>
            <div class="margin-left-sm text-center lancel-right">
              <div class="lancel-txt padding-bottom-xs">今日支付金额（元）</div>
              <div class="lancel-num">0</div>
            </div>
          </div>
        </el-col>
        <el-col class="padding-bottom" :sm="6" :xl="6" :lg="6" :md="12">
          <div class="flex">
            <div class="lancel-radius">
                 <svg-icon
                  class="icon sub-el-icon"
                  icon-class="home-01"
                  :size="62"
                />
            </div>
            <div :span="12" class="margin-left-sm text-center lancel-right">
              <div class="lancel-txt padding-bottom-xs">今日订单数</div>
              <div class="lancel-num">0</div>
            </div>
          </div>
        </el-col>
        <el-col class="padding-bottom" :sm="6" :xl="6" :lg="6" :md="12">
          <div class="flex">
            <div class="lancel-radius">
                 <svg-icon
                  class="icon sub-el-icon"
                  icon-class="home-06"
                  :size="62"
                />
            </div>
            <div :span="12" class="margin-left-sm text-center lancel-right">
              <div class="lancel-txt padding-bottom-xs">累计支付人数</div>
              <div class="lancel-num">0</div>
            </div>
          </div>
        </el-col>
        <el-col class="padding-bottom" :sm="6" :xl="6" :lg="6" :md="12">
          <div class="flex">
            <div class="lancel-radius">
                 <svg-icon
                  class="icon sub-el-icon"
                  icon-class="home-07"
                  :size="62"
                />
            </div>
            <div :span="12" class="margin-left-sm text-center lancel-right">
              <div class="lancel-txt padding-bottom-xs">累计用户</div>
              <div class="lancel-num">0</div>
            </div>
          </div>
        </el-col>
        <el-col class="padding-bottom" :sm="6" :xl="6" :lg="6" :md="12">
          <div class="flex">
            <div class="lancel-radius">
                 <svg-icon
                  class="icon sub-el-icon"
                  icon-class="home-08"
                  :size="62"
                />
            </div>
            <div :span="12" class="margin-left-sm text-center lancel-right">
              <div class="lancel-txt padding-bottom-xs">累计支付金额（元）</div>
              <div class="lancel-num">0</div>
            </div>
          </div>
        </el-col>
        <el-col class="padding-bottom" :sm="6" :xl="6" :lg="6" :md="12">
          <div class="flex">
            <div class="lancel-radius">
                 <svg-icon
                  class="icon sub-el-icon"
                  icon-class="home-09"
                  :size="62"
                />
            </div>
            <div class="margin-left-sm text-center lancel-right">
              <div class="lancel-txt padding-bottom-xs">
                <div style="white-space: nowrap">可提现金额（元）</div>
              </div>
              <div class="lancel-num">0</div>
            </div>
          </div>
        </el-col>
      </el-row>
    </div>
    <!-- <el-row class="planel-group">
      <el-col :sm="12" :md="8" :lg="4">
        <div class="lancel-cont text-center">
          <div class="store-num margin-bottom-sm">待付款订单</div>
          <div class="text-bold text-sm">0</div>
        </div>
      </el-col>
      <el-col :sm="12" :md="8" :lg="4">
        <div class="lancel-cont text-center">
          <div class="store-num margin-bottom-sm">待发货订单</div>
          <div class="text-bold text-sm">0</div>
        </div>
      </el-col>
      <el-col :sm="12" :md="8" :lg="4">
        <div class="lancel-cont text-center">
          <div class="store-num margin-bottom-sm">待处理售后订单</div>
          <div class="text-bold text-sm">0</div>
        </div>
      </el-col>
      <el-col :sm="12" :md="8" :lg="4">
        <div class="lancel-cont text-center">
          <div class="store-num margin-bottom-sm">消费者投诉</div>
          <div class="text-bold text-sm">0</div>
        </div>
      </el-col>
      <el-col :sm="12" :md="8" :lg="4">
        <div class="lancel-cont text-center">
          <div class="store-num margin-bottom-sm">用户反馈</div>
          <div class="text-bold text-sm">0</div>
        </div>
      </el-col>
      <el-col :sm="12" :md="8" :lg="4">
        <div class="lancel-cont text-center">
          <div class="store-num margin-bottom-sm">待审核推广员</div>
          <div class="text-bold text-sm">0</div>
        </div>
      </el-col>
    </el-row> -->
  </div>
</template>

<script>
import CountTo from 'vue-count-to'

export default {
  components: {
    CountTo
  },
  methods: {
    handleSetLineChartData(type) {
      this.$emit('handleSetLineChartData', type)
    }
  }
}
</script>

<style lang="scss" scoped>
.planel-group {
  padding: 24px 0;
  background: #fff;
  border-radius: 11px;
  margin-bottom: 37px;

  .border {
    border-left: 1px solid rgb(112, 112, 112, 0.2);
  }

  .lancel-item {
    padding: 25px 0;
    .lancel-radius {
      width: 64px;
      height: 64px;
      // background-color: pink;
      border-radius: 50%;
    }

    .lancel-txt {
      font-size: 16px;
      color: #999999;
      white-space: nowrap;
    }

  .lancel-right{
    width: 120px;
  }
    .lancel-cash {
      display: flex;
      .cash {
        width: 80px;
        height: 24px;
        line-height: 24px;
        background-color: #12a9a4;
        color: #ffffff;
        border-radius: 12px;
        font-size: 12px;
        text-align: center;
      }
    }
    .lancel-num {
      font-size: 30px;
      font-weight: 600;
      color: #141414;
    }
  }

  .lancel-cont {
    // margin: 24px;
    .store-num {
      color: #c4c4c4;
      font-size: 21px;
    }
    .color {
      color: #00b1ff;
    }
  }
}
</style>
