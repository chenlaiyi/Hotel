/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-11-30 16:26:32
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-03-13 15:45:39
 */
import RedirectIndex from './redirect/index.vue'
import IframeIndex from './redirect/iframe.vue'
import ErrorPage401 from './error-page/401.vue'
import ErrorPage404 from './error-page/404.vue'
import SystemAccountIndex from './profile/account/index.vue'
import ProfileIndex from './profile/index.vue'
import SettingIndex from './setting/index.vue'
import SettingAdministrator from './setting/administrator.vue'
import SettingAttestation from './setting/attestation/attestation.vue'
import SettingStoreattest from './setting/attestation/storeattest.vue'
//  用户创建商户页面
import SystemNotificationIndex from './notification/index.vue'
// 服务
import ServiceSubscription from './service/subscription.vue'
import ServiceBuy from './service/buy.vue'
import ServiceSuccess from './service/success.vue'
import ServiceRegister from './service/register.vue'
import ServiceDetails from './service/details.vue'
import MainIndex from './main/index.vue'
import Dashboard from './dashboard/index.vue'

const sysPage = {
  'dashboard': Dashboard,
  'main-index': MainIndex,
  'redirect-iframe': IframeIndex,
  'service-subscription': ServiceSubscription,
  'service-buy': ServiceBuy,
  'service-success': ServiceSuccess,
  'service-register': ServiceRegister,
  'service-details': ServiceDetails,
  'redirect-index': RedirectIndex,
  'system-notification-index': SystemNotificationIndex,
  'error-page-401': ErrorPage401,
  'error-page-404': ErrorPage404,
  'setting-index': SettingIndex,
  'system-account-index': SystemAccountIndex,
  'profile-index': ProfileIndex,
  'setting-administrator': SettingAdministrator,
  'setting-attestation': SettingAttestation,
  'setting-storeattest': SettingStoreattest
}

export default sysPage
