<template>
  <div class="form-container">
    <ele-form
      v-model="formData"
      v-bind="formConfig"
      :request-fn="handleRequest"
      :is-show-back-btn="false"
      @request-success="handleRequestSuccess"
    />
  </div>
</template>

<script>
import {
  messagelistView,
  messagelistUpdate,
  messageIndex
} from '@projectName/views/system/api/admin/message.js'
export default {
  components: {},
  data() {
    return {
      id: 0,
      formData: {},
      formConfig: {
        formDesc: {
          title: {
            type: 'input',
            label: '标题'
          },
          content: {
            type: 'textarea',
            label: '内容'
          },
          admin_ids: {
            type: 'input',
            label: '管理员ID(多个用英文‘,’隔开)'
          },
          category_id: {
            type: 'select',
            label: '分类',
            isOptions: true,
            options: messageIndex().then((res) => {
              const arr = []
              res.data.dataProvider.allModels.forEach((item, index) => {
                arr.push({
                  text: item.name,
                  value: item.id
                })
              })
              return arr
            })
          },
          publish_at: {
            type: 'datetime',
            label: '发布日期',
            attrs: {
              valueFormat: 'yyyy-MM-dd HH:mm:ss'
            }
          },
          status: {
            type: 'radio',
            label: '有效状态',
            isOptions: true,
            options: [
              {
                text: '无效的',
                value: -1
              },
              {
                text: '有效的',
                value: 1
              }
            ],
            default: 1
          }
        },
        order: ['title', 'category_id', 'admin_ids', 'publish_at', 'status', 'content']
      }
    }
  },
  created() {
    this.id = this.$route.params.id
    this.getViews()
  },
  methods: {
    getViews() {
      const id = this.id
      messagelistView(id).then((res) => {
        this.formData = res.data
      })
    },
    handleRequest(data) {
      messagelistUpdate(data).then((response) => {
        if (response.code === 200) {
          this.$message.success(response.message)
          this.closePage()
        } else {
          this.$message.error(response.message)
        }
      })
      return Promise.resolve()
    },
    handleRequestSuccess() {},
    closePage() {
      this.$store.dispatch('app/closePage', {
        vm: this,
        fromName: this.$route.name,
        toName: 'admin-messagecate-index',
        params: {}
      })
    }
  }
}
</script>

<style>
.form-container {
  margin: 20px;
  padding: 20px;
  background-color: #fff;
}
</style>
