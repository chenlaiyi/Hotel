/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-11-30 16:26:32
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-08-02 00:12:51
 */
import LoginIndex from './index'
import LoginRegister from './register.vue'
import LoginForget from './forget.vue'
import LoginAuthRedirect from './auth-redirect.vue'
const LoginMap = {
  'login-index': LoginIndex,
  'login-register': LoginRegister,
  'login-forget': LoginForget,
  'login-auth-redirect': LoginAuthRedirect
}

export default LoginMap
