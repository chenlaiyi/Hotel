/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2024-02-25 07:26:47
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-02-27 05:54:52
 */
import WechatMenuEditor from './src/index.vue'

WechatMenuEditor.install = function(Vue) {
  Vue.component(WechatMenuEditor.name, WechatMenuEditor)
}

export default WechatMenuEditor
