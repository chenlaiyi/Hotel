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
  messageView,
  messageUpdate
} from '@projectName/views/system/api/admin/message.js'
export default {
  components: {},
  data() {
    return {
      id: 0,
      formData: {},
      formConfig: {
        formDesc: {
          name: {
            type: 'input',
            label: '分类名称'
          }
        },
        order: [
          'name'
        ]
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
      messageView(id).then((res) => {
        this.formData = res.data
      })
    },
    handleRequest(data) {
      messageUpdate(data).then((response) => {
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
