<template>
  <div class="app-container">
    <div class="app-form">
      <ele-form
          v-model="formData"
          v-bind="formConfig"
          :request-fn="handleRequest"
          @request-success="handleRequestSuccess"
      />
    </div>
  </div>
</template>

<script>
import {form, order, path} from './init.js'
import {itemCreate} from "./api";
export default {
  data() {
    return {
      formData: {
        status: 1
      },
      formConfig: {
        formDesc: form,
        order: order
      }
    }
  },
  methods: {
    handleRequest(data) {
      itemCreate(data).then((response) => {
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
        toName: path.index,
        params: {}
      })
    }
  }
}
</script>
