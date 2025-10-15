/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2024-08-07 17:31:08
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-12-26 12:15:13
 */
exports.notEmpty = name => v =>
  !v || v.trim() === '' ? `${name} is required` : true
