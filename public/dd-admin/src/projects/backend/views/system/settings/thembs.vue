<template>
  <div class="app-container">
    <div class="drawer-container">
      <el-card shadow="always" :body-style="{ padding: '20px' }">
        <div slot="header">
          <span>页面风格配置</span>
        </div>
        <div>
          <div class="drawer-item">
            <el-radio-group v-model="layoutVal" size="small" class="drawer-switch" @change="setLayout">
              <el-radio-button v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
                <div class="drawer-item-img">
                  <img :src="item.imageUrl" :alt="item.value" class="radio-button-img" :preview-src-list="[item.imageUrl]">
                </div>
                <div class="drawer-item-content">
                  <div class="drawer-item-title">{{ item.label }}</div>
                  <div class="drawer-item-desc">{{ item.desc }}</div>
                </div>
              </el-radio-button>
            </el-radio-group>
          </div>
        </div>
      </el-card>

    </div>
  </div>
</template>

<script>
const subfield = require('@projectName/assets/custom-theme/subfield.jpg')
const unSubfield = require('@projectName/assets/custom-theme/unSubfield.jpg')
export default {
  data() {
    return {
      options: [
        {
          value: 'subfield',
          label: '分栏',
          imageUrl: subfield,
          desc: '顶部导航+左右布局，左侧两栏'
        },
        {
          value: 'unSubfield',
          label: '独立分栏',
          imageUrl: unSubfield,
          desc: '顶部导航+左右布局，左侧一栏'
        }
      ],
      value1: true,
      layoutVal: '常规'
    }
  },
  computed: {
    fixedHeader: {
      get() {
        return this.$store.state.settings.fixedHeader
      },
      set(val) {
        this.$store.dispatch('settings/changeSetting', {
          key: 'fixedHeader',
          value: val
        })
      }
    },
    tagsView: {
      get() {
        return this.$store.state.settings.tagsView
      },
      set(val) {
        this.$store.dispatch('settings/changeSetting', {
          key: 'tagsView',
          value: val
        })
      }
    },
    sidebarLogo: {
      get() {
        return this.$store.state.settings.sidebarLogo
      },
      set(val) {
        this.$store.dispatch('settings/changeSetting', {
          key: 'sidebarLogo',
          value: val
        })
      }
    }
  },
  methods: {
    themeChange(val) {
      this.$store.dispatch('settings/changeSetting', {
        key: 'theme',
        value: val
      })
    },
    setLayout(val) {
      this.layoutVal = val
      const item = this.options.find(item => item.label === val)
      console.log('setLayout', val, item)
      this.$store.dispatch('settings/setLayout', item.value)
      this.$message.success('设置' + item.label + '模板成功')
    }
  }
}
</script>

  <style lang="scss" scoped>
  .drawer-container {
    padding: 24px;
    font-size: 14px;
    line-height: 1.5;
    word-wrap: break-word;

    .drawer-title {
      margin-bottom: 12px;
      color: rgba(0, 0, 0, 0.85);
      font-size: 14px;
      line-height: 22px;
    }

    .drawer-item {
      color: rgba(0, 0, 0, 0.65);
      font-size: 14px;
      padding: 12px 0;
    }
    .el-radio-button {
      margin: 10px;
      border-radius: 5px;
    }
    .custom-radio-button {
      /* 移除默认的边框和背景等样式 */
      border: none;
      background-color: transparent;
      /* 根据需要添加其他样式 */
      padding: 0;
      margin-right: 10px;
      /* 或者其他你需要的间距 */
    }

    .drawer-item-img {

      .radio-button-img {
        /* 设置图片样式，如大小、边距等 */
        width: 250px;
        /* 举例 */
        // height: 250px;
        /* 举例 */
        border-radius: 5px;
        /* 可选，使图片呈圆形 */
        vertical-align: middle;
        /* 使图片和文字垂直对齐（如果需要） */
      }
    }

    .drawer-item-title {
      height: 50px;
      line-height: 50px;
      font-size: 18px;
      font-weight: bold;
    }

    /* 选中状态的样式，这里使用 Element UI 的 CSS 类名作为参考，可能需要调整 */
    .custom-radio-button.is-checked {
      /* 你可以在这里添加选中状态的样式，比如边框、阴影等 */
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    }
  }
</style>
