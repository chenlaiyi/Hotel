<template>
  <div class="app-container">
    <div class="sub-store">
      <el-row :gutter="20" style="background: #f5f5f5">
        <el-col v-for="(itm, idx) in step" :key="idx" :span="12">
          <div :class="idx === current ? 'avtive' : 'buy-step'">
            {{ itm }}
          </div>
        </el-col>
      </el-row>
      <div class="admin-info">
        <oldadmin v-if="current === 0" @handlestep="handlestep" />
        <newadmin v-if="current === 1" />
      </div>
    </div>
  </div>
</template>
<script>
import oldadmin from './components/oldadmin'
import newadmin from './components/newadmin'
export default {
  components: {
    oldadmin,
    newadmin
  },
  data() {
    return {
      value: 5,
      step: ['1.验证身份', '2.更换管理员'],
      current: 0
    }
  },
  methods: {
    handlestep(data) {
      this.current = data
    }
  }
}
</script>
<style type="text/css">
.sub-store {
  background-color: #fff;
  padding: 23px 23px 341px;
}
.buy-step {
  background: #f5f5f5;
  padding: 20px 0;
  color: #333;
  font-size: 15px;
  text-align: center;
}
.avtive {
  background-color: #191b5c;
  color: #fff;
  padding: 20px 0;
  text-align: center;
  position: relative;
}
.avtive::after {
  content: "";
  display: block;
  position: absolute;
  right: -30px; /* 箭头位置 */
  top: 0; /* 箭头位置 */
  border-top: 30px solid transparent; /* 箭头高低 */
  border-bottom: 30px solid transparent; /* 箭头高低 */
  border-left: 30px solid #191b5c; /* 箭头长度*/
}
.admin-info {
  width: 466px;
  margin: 105px auto;
}
</style>
