<template>
  <div class="dashboard-editor-container">
    <!-- <github-corner class="github-corner" /> -->

    <panel-group @handleSetLineChartData="handleSetLineChartData" />
    <div class="sys-info-main">
      <div class="panel-group">
        <el-row :gutter="20">
          <el-col :sm="24" :xl="16" :md="16" :lg="16">
            <el-row :gutter="20">
              <el-col class="lately-day" :span="8">近七日成交量与访问量 </el-col>
              <!-- <el-col class="lately-title" :span="16">成交量与访问量</el-col> -->
            </el-row>
            <div class="data-map">
              <div id="main" class="mian" />
            </div>
          </el-col>
          <el-col :sm="24" :xl="8" :md="8" :lg="8">
            <div class="lately-day">转化率</div>
            <div class="percent text-center">
              <div class="percent-time" @click="changetime">
                {{ timeValueArr }}
              </div>
              <el-progress
                type="circle"
                :width="147"
                color="#1875F0"
                :percentage="25"
              />
              <div class="precent-yes">昨日总流量</div>
              <div class="el-progress__text">2150</div>
            </div>
          </el-col>
        </el-row>
      </div>

      <!-- <el-date-picker
        v-model="timeValueArr"
        type="daterange"
        ref="datePickerRef"
        class="date_style"
        v-show="false"
        @change="fnchooseCustomTime"
      >
      </el-date-picker> -->
    </div>
  </div>
</template>

<script>
import PanelGroup from './components/PanelGroup'
export default {
  name: 'BlocDashboard',
  components: {
    PanelGroup
  },
  data() {
    return {
      value1: '',
      timeValueArr: ''
    }
  },
  mounted() {
    this.getlately()
    this.addDate()
  },
  methods: {
    addDate() {
      const nowDate = new Date()
      const date = {
        year: nowDate.getFullYear(),
        month: nowDate.getMonth() + 1,
        date: nowDate.getDate()
      }
      this.timeValueArr = date.year + '-' + date.month + '-' + date.date
    },
    getlately() {
      const that = this
      var myChart = this.$echarts.init(document.getElementById('main'))
      var option = {
        legend: {
          icon: 'rect',
          itemWidth: 27,
          itemHeight: 3,
          padding: [10, 0, 0, 0],
          data: ['成交量', '访问量']
        },
        xAxis: {
          type: 'category',
          data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
        },
        yAxis: {
          type: 'value',
          axisLabel: {
            textStyle: {
              color: '#1E87F0',
              fontSize: '13'
            }
          }
        },
        series: [
          {
            name: '成交量',
            type: 'line',
            symbol: 'none',
            smooth: false,
            color: '#0063ED',
            data: [120, 132, 101, 134, 90, 230, 210]
          },
          {
            name: '访问量',
            data: [820, 932, 901, 934, 1290, 1330, 1320],
            type: 'line',
            symbol: 'none',
            color: '#FF9307'
          }
        ]
      }
      myChart.setOption(option)
    },
    handleSetLineChartData(type) {
      this.lineChartData = lineChartData[type]
    },
    changetime() {
      this.$refs.datePickerRef.$el.click()
    },
    fnchooseCustomTime(value) {
      this.timeValue = value.join(',')
    }
  }
}
</script>

<style lang="scss" scoped>
.dashboard-editor-container {
  padding: 24px;
  position: relative;

  .sys-info-main {
    .lately-day {
      color: #1c2e32;
      font-size: 21px;
    }
    .lately-title {
      color: #a4a4a4;
      font-size: 19px;
    }
  }
  .data-map {
    height: 401px;
    background: #ffffff;
    border-radius: 9px;
    margin-top: 37px;
  }
  .lately-day-color {
    color: #1e87f0;
    font-size: 19px;
  }
  .percent {
    height: 402px;
    background: #ffffff;
    border-radius: 8px;
    margin-top: 37px;
    .percent-time {
      color: #1e87f0;
      font-size: 23px;
      padding: 32px 0;
    }
    .precent-yes {
      padding: 37px 0 25px;
      color: #c5c5c5;
      font-size: 19px;
    }
  }
}
::v-deep .el-progress__text {
  font-size: 32px !important;
  color: #1875f0 !important;
}
.date_style {
  opacity: 0;
}
.mian {
  height: 400px;
  width: 100%;
}
</style>
