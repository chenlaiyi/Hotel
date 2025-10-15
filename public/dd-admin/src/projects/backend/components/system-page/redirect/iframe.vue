<template>
  <div class="app-container">
    {{ iframeUrl }}
    <iframe :src="url" frameborder="0" width="100%" height="500px" />
  </div>
</template>

<script>
export default {
  data() {
    return {
      url: ''
    }
  },
  computed: {
    iframeUrl() {
      // 确保URL以http或https开头
      console.log('url', this.url)
      if (!this.url) {
        return 'about:blank' // 如果不是有效的URL，则显示空白页
      }

      if (this.url.indexOf('http://') === -1 || this.url.startsWith('https://') === -1) {
        console.warn('URL should start with http:// or https://')
        return 'about:blank' // 如果不是有效的URL，则显示空白页
      }
      console.log('url2', this.url)
      return this.url
    }
  },
  created() {
    this.url = this.$route.query.url
  }
}
</script>

  <style scoped>
  /* 在这里可以添加你的样式 */
  iframe {
    border: none;
    width: 100%;
    height: 500px; /* 或者你可以使用其他高度 */
  }
  </style>
