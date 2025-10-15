<template>
	<view>
		<fui-tabbar :tabBar="tabBar" :selectedColor="selectedColor" :hump="hump" :current="current"
			@click="tabbarSwitch" color="#CCCCCC">
		</fui-tabbar>
	</view>
</template>
<script>
	export default {
		name: "tabBar",
		data() {
			return {
				hump: true,
				current: 0,
				selectedColor: "#00AFC7",
				tabBar: [{
						"pagePath": "index",
						"text": "首页",
						"iconPath": "/static/svgs/tab-05.svg",
						"selectedIconPath": "/static/svgs/tab-01.svg",
					},
					{
						"pagePath": "myshop",
						"text": "积分商城",
						"iconPath": "/static/images/tabbar/tab_shop_de@2x.png",
						"selectedIconPath": "/static/images/tabbar/tab_shop_se@2x.png",
						"verify": true
					},
					{
						"pagePath": "privileges",
						"iconPath": "/static/images/tabbar/privileges3.png",
						"selectedIconPath": "/static/images/tabbar/privileges_active3.png",
						"verify": true,
                        'hump':true
					},
					{
						"pagePath": "myorder",
						"text": "订单",
						"iconPath": "/static/svgs/tab-07.svg",
						"selectedIconPath": "/static/svgs/tab-03.svg",
						"verify": true
					},
					{
						"pagePath": "mys",
						"text": "我的",
						"iconPath": "/static/svgs/tab-08.svg",
						"selectedIconPath": "/static/svgs/tab-04.svg",
						"verify": true
					},
				],
			}
		},
		props: {
			currentindex: {
				type: Number,
				default: 0
			}
		},
		created() {
			let that = this
			this.current = that.$store.state.currentindex
			// console.log('切换页面', this.current)
		},
		methods: {
			tabbarSwitch(e) {
				let that = this
				//获取登录状态，此处默认未登录
				let isLogin = false
				// console.log('切换页面',e.pagePath,e,e.index)
				that.$store.state.currentindex = e.index
				that.$forceUpdate()
				// console.log('当前第几个', e.index, e, e.name)
				that.$Router.replaceAll({name:e.pagePath})
			},
		}
	}
</script>

<style >

</style>
