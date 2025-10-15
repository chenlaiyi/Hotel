<template>
  <div class="notic-cont">
    <el-tabs v-model="activeName" @tab-click="handleClick">
      <div
        v-if="messagecatelist.length === 0"
        class="text-center padding-tb-sm"
      >
        暂无消息
      </div>

      <el-tab-pane
        v-for="(itm, idx) in messagecatelist"
        :key="idx"
        :label="itm.name"
        :name="itm.name"
      >
        <el-collapse v-model="activeNames">
          <div v-if="message.length === 0" class="text-center padding-tb-sm">
            暂无消息
          </div>
          <el-collapse-item
            v-for="(i, index) in message"
            :key="index"
            :name="index"
          >
            <template slot="title">
              <div class="title-box">
                <div>{{ i.title }}</div>
                <div>{{ i.publish_at }}</div>
              </div>
            </template>
            <div class="desceipt-cont">
              {{ i.content }}
            </div>
            <div class="fr" style="color: #191b5c" @click="readbtn(i.id)">
              标识为已读
            </div>
          </el-collapse-item>
        </el-collapse>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>
<script>
import { getMessagesList } from '@projectName/api/system.js'
import {
  messageIndex,
  changestates
} from '@projectName/api/message.js'
export default {
  data() {
    return {
      activeName: '',
      activeNames: [1],
      messagecatelist: [],
      message: [],
      category_id: 0
    }
  },
  created() {
    this.getmessagecateList()
  },
  methods: {
    handleClick(tab, event) {
      const that = this
      that.category_id = that.messagecatelist[tab.index].id
      that.getMessage()
      console.log(that.messagecatelist[tab.index].id)
    },
    getmessagecateList() {
      const that = this
      messageIndex().then((response) => {
        that.messagecatelist = response.data.dataProvider.allModels
        that.activeName = response.data.dataProvider.allModels[0].name
        that.category_id = response.data.dataProvider.allModels[0].id
        that.getMessage()
      })
    },
    // 获取消息
    getMessage() {
      const that = this
      const data = {
        'HubMessagesSearch[category_id]': that.category_id
      }
      getMessagesList(data).then((res) => {
        this.message = res.data.dataProvider.allModels
      })
    },
    // 标识为已读
    readbtn(id) {
      changestates(id).then((res) => {
        this.getmessagecateList()
      })
    }
  }
}
</script>
<style scoped>
.notic-cont {
  background: #ffffff;
  margin: 34px 23px;
  padding: 28px 49px;
}
.desceipt-cont {
  color: #535353;
  font-size: 13px;
}
.title-box {
  display: flex;
  justify-content: space-between;
  width: 100%;
  padding-right: 29px;
  font-size: 15px;
  color: #535353;
}
::v-deep .el-tabs__item.is-active {
  color: #191b5c !important;
}

::v-deep .el-tabs__active-bar {
  background-color: #191b5c !important;
}
::v-deep .el-tabs__item {
  height: 50px;
}
</style>
