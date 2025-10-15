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
import {getView, itemUpdate} from "./api";
import {form, order, path, rowKey} from "./init";

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
  props: {
    params: {
      type: Object,
      default: () => {},
    },
  },
  created() {
    this.id = this.params.id
    this.getViews()
  },
  methods: {
    getViews() {
      const id = this.id
      getView(id).then((res) => {
        this.formData = res.data
      })
    },
    handleRequest(data) {
      itemUpdate(data[rowKey], data).then((response) => {
        if (response.code === 200) {
          this.$message.success(response.message)
          this.closePage()
        } else {
          this.$message.error(response.message)
        }
      })
      return Promise.resolve()
    },
    handleRequestSuccess() {
    },
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
