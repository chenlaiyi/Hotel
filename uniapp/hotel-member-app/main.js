import Vue from 'vue'
import store from './store'
import App from './App'
import tabBar from './components/tabBar.vue'
import fui from '@/uni_modules/ddiot-ui/js_sdk/index.js'
import diandi from './config.js'
import uView from '@/uni_modules/uview-ui'
import * as api from '@/api/api.js'
Vue.use(uView)

import {
	router,
	RouterMount
} from './router.js' //路径换成自己的
Vue.use(router)


// #ifdef H5
window.QQmap = null;
// #endif
// #ifndef MP-TOUTIAO
//网络监听
setTimeout(() => {
	uni.onNetworkStatusChange(function(res) {
		//console.log(res.networkType);
		store.commit("networkChange", {
			isConnected: res.isConnected
		})
	});
}, 100)
// #endif
Vue.prototype.fui = fui
Vue.prototype.$eventHub = Vue.prototype.$eventHub || new Vue()
Vue.prototype.$store = store
Vue.component('tab-bar', tabBar)
Vue.prototype.$store = store
Vue.config.productionTip = false
Vue.prototype.$api = api
App.mpType = 'app'



Vue.use(fui, {
	websoketConf:{
		url: "wss://iot.ddicms.com:9502/addons",
		heartRateType: 'heart',
		heartRate: 60000,
		username: 'blocc',
		password: '12345678',
		app_secret: 'SWcIzkphxfdHT4gs',
		app_id: 'jCVmgW9dkI8cRJps',
	},
	// 请求相关的配置
	http: {
		successCode: 200,
		apiConfig: {
			'bloc_id': diandi.bloc_id,
			'store_id': diandi.store_id,
			'app_secret': '2',
			'app_id': '1',
			'baseUrl': diandi.baseUrl,
			'siteUrl': diandi.siteUrl,
			'uploadImgUrl': diandi.baseUrl + '/upload/images',
			'imgBaseUrl': diandi.baseUrl + '/attachment/',
			// 代理名称
			'proxyName': diandi.baseUrl,
			'tokenApi': '/wechat/basics/signup',
			'refreshApi': '/user/refresh',
			// 备用域名配置,至少配置一个,这边会自动设置baseUrl，至少传入一个域名，H5本地调试会自己代理到proxyName
			'domain': diandi.baseUrl,
			'iot':{
				'app_secret': 'SWcIzkphxfdHT4gs',
				'app_id': 'jCVmgW9dkI8cRJps',
				'baseUrl': diandi.iotUrl,
			},
			// 签名算法
			// signFunc: (res, app_id, app_secret) => {
			// 	console.log('签名算法', res, app_id, app_secret)
			// 	return Object.assign({
			// 		sign: 666
			// 	}, res)
			// },
			// 动态头部参数
			headerFunc: (config) => {
				console.log('头部处理',uni.getStorageSync('access_token'))
				return {
					'bloc-id': uni.getStorageSync('bloc-id') || diandi.bloc_id,
					'store-id': uni.getStorageSync('store-id') || diandi.store_id,
					'access-token': uni.getStorageSync('access_token') || ''
				}
			},
			getToken: (config) => {
				let accessToken = uni.getStorageSync('access_token');
				return accessToken;
			},
			getRefToken: (config) => {
				let refresh_token = uni.getStorageSync('refresh_token');
				return refresh_token;
			},
			refTokenCallBack: (res) => {
				// #ifdef MP-WEIXIN
				uni.setStorageSync('fans', res.data.wxappFans);
				// #endif

				// #ifndef MP-WEIXIN
				uni.setStorageSync('fans', res.data.wechatFans);
				// #endif
				uni.setStorageSync('nickname', res.data.member.nickname);
				uni.setStorageSync('access_token', res.data.access_token);
				uni.setStorageSync('refresh_token', res.data.refresh_token);
				uni.setStorageSync('expiration_time', res.data.expiration_time);
				uni.setStorageSync('member', res.data.member);
			}
		},
		responseSuccessCallBack: (res, catchObj, query) => {
			// console.log('请求触发1', res, catchObj, query);
			if (res && res.code === 403) {
				console.log('请求触发1--403');
				uni.removeStorageSync('access_token');
				fui.toLogin(403)
			}
		}
	}
})

const app = new Vue({
	store,
	...App
})

//v1.3.5起 H5端 你应该去除原有的app.$mount();使用路由自带的渲染方式
// #ifdef H5
RouterMount(app, router, '#app')
// #endif

// #ifndef H5
app.$mount(); //为了兼容小程序及app端必须这样写才有效果
// #endif