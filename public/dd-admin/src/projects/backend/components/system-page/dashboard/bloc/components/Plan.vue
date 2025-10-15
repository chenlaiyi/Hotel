<template>
  <el-card shadow="hover">
    <template #header>
      <svg-icon icon-class="list" />
      <span class="margin-left-xs">开发计划</span>
    </template>
    <el-table :data="tableData" row-key="title" height="385px">
      <el-table-column align="center" label="拖拽" width="50px">
        <template #default="{}">
          <i class="el-icon-rank" />
        </template>
      </el-table-column>
      <el-table-column width="20px" />
      <el-table-column prop="title" label="目标" width="230px" />
      <el-table-column label="进度" width="220px">
        <template #default="{ row }">
          <el-progress :percentage="row.percentage" :color="row.color" />
        </template>
      </el-table-column>
      <el-table-column width="50px" />
      <el-table-column prop="endTIme" label="完成时间" />
    </el-table>
  </el-card>
</template>
<script>
import Sortable from 'sortablejs'
export default {
  name: 'Plan',
  data() {
    return {
      tableData: [
        {
          title: 'IM客服',
          endTIme: '2022-04-31',
          percentage: 30,
          color: '#95de64'
        },
        {
          title: '直播APP',
          endTIme: '2022-04-31',
          percentage: 10,
          color: '#95de64'
        },
        {
          title: '直播电商',
          endTIme: '2022-04-31',
          percentage: 50,
          color: '#95de64'
        },
        {
          title: '企业官网',
          endTIme: '2029-03-31',
          percentage: 80,
          color: '#69c0ff'
        },
        {
          title: '外呼APP',
          endTIme: '2021-02-31',
          percentage: 76,
          color: '#1890FF'
        },
        {
          title: '党建小程序',
          endTIme: '2020-02-11',
          percentage: 100,
          color: '#ffc069'
        },
        {
          title: '分销商城',
          endTIme: '2022-4-16',
          percentage: 80,
          color: '#5cdbd3'
        },
        {
          title: '积分商城',
          endTIme: '2022-3-16',
          percentage: 20,
          color: '#b37feb'
        }
      ]
    }
  },
  mounted() {
    const tbody = document.querySelector('.el-table__body-wrapper tbody')
    const _this = this
    Sortable.create(tbody, {
      onEnd({ newIndex, oldIndex }) {
        const currRow = _this.tableData.splice(oldIndex, 1)[0]
        _this.tableData.splice(newIndex, 0, currRow)
      }
    })
  }
}
</script>
