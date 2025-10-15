/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2024-04-23 00:10:31
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-04-23 09:49:17
 */
import waves from './waves'

const install = function(Vue) {
  Vue.directive('waves', waves)
}

if (window.Vue) {
  window.waves = waves
  Vue.use(install); // eslint-disable-line
}

waves.install = install
export default waves
