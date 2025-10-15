/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2024-04-23 00:10:31
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-04-23 09:49:13
 */
import adaptive from './adaptive'

const install = function(Vue) {
  Vue.directive('el-height-adaptive-table', adaptive)
}

if (window.Vue) {
  window['el-height-adaptive-table'] = adaptive
  Vue.use(install); // eslint-disable-line
}

adaptive.install = install
export default adaptive
