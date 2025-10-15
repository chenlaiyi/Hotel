<template>
  <div class="block">
    <el-timeline>
      <el-timeline-item
        v-for="(item, index) of timeline"
        :key="index"
        :timestamp="index"
        placement="top"
      >
        <el-card v-for="(itm, idx) of item" :key="idx" style="margin: 10px 0;height:100%">
          <h4>{{ itm.logtime }}</h4>
          <p>{{ itm.operation }}</p>
        </el-card>
      </el-timeline-item>
    </el-timeline>
  </div>
</template>

<script>
import { getlog, getuserinfo } from '@projectName/api/profile/profile.js'
export default {
  data() {
    return {
      id: 0,
      timeline: []
    }
  },
  created() {
    this.getDetail()
  },
  methods: {
    getDetail() {
      const that = this
      getuserinfo().then((res) => {
        that.id = res.data.userinfo.user.id
        console.log('iddata', res.data.userinfo.user)
        that.getLog()
      })
    },
    getLog() {
      const that = this
      const data = {
        user_id: that.id
      }
      getlog(data).then((res) => {
        that.timeline = res.data
      })
    }
  }
}
</script>
