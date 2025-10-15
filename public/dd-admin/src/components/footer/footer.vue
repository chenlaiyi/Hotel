<template>
  <el-footer class="footer" height="100px" width="100%" :style="{backgroundColor:backgroundColor}">
    <div class="footer-content">
      <p class="hidden-xs-only">
        <span class="footer-content-left">{{ footerleft }}</span>  | <span class="footer-content-right">{{ footerright }}</span>
      </p>
      <p>
        Copyright © {{ currentYear }} {{ name }}. All rights reserved.
        <span> <a href="https://beian.miit.gov.cn/">{{ icp }}</a> </span>
      </p>
    </div>
  </el-footer>
</template>
<script>
export default {
  name: 'Footer',
  props: {
    backgroundColor: {
      type: String,
      default: '#f5f7fa'
    },
    name: {
      type: String,
      default: 'ICP备XXXXXX号'
    },
    icp: {
      type: String,
      default: 'ICP备XXXXXX号'
    },
    footerleft: {
      type: String,
      default: '底部左侧内容'
    },
    footerright: {
      type: String,
      default: '底部右侧内容'
    }

  },
  data() {
    return {
      currentYear: new Date().getFullYear()
    }
  },
  methods: {
    // 点击事件
    clickHandler() {
      console.log('click')
    }
  }

}
</script>
<style lang="scss" scoped>
  .footer {
    height: 100px;
    width: 100%;
    color: #606266;
    line-height: 1.8;
    text-align: center;
    bottom: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    .footer-content{
        p {
            margin-bottom: 10px;
        }

        a {
            color: #409EFF;
            text-decoration: none;
            transition: all .3s;
        }
        a:hover {
            color: #66b1ff;
        }
        &-left {
            padding-right:5px;
        }
        &-right {
            padding-left:5px;
        }
    }

  }
</style>
