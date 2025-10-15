<template>
	<view class="mobileHome">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="选择国家" color="#000000" :border="false" fixed placeholder :auto-back="true"></u-navbar>
		<!-- #endif -->
		<area-select
			@cityClick="cityClick"
			:formatName="formatName"
			:activeCity="activeCity"
			:isSearch="true"
			ref="citys"
		></area-select>
	</view>
</template>

<script>
import areaSelect from '@/components/area-select/area-select.vue'
export default {
	data() {
		return {
			//需要构建索引参数的名称（注意：传递的对象里面必须要有这个名称的参数）
			formatName: 'name',
			//当前城市
			activeCity: {
				id: 1,
				title: '南京市'
			}
		}
	},
	components: {
		areaSelect
	},
	onLoad() {
	},
	methods: {
		cityClick(item) {
			console.log(item.dialCode)
			this.$Router.back(1)
			uni.showToast({
				icon: 'none',
				title: 'item: ' + JSON.stringify(item),
				// #ifdef MP-WEIXIN
				duration: 3000,
				// #endif
				mask: true
			})
		}
	}
}
</script>

<style lang="scss" scoped>
	.mobileHome{
		min-height: 100vh;
		// background-color: #fff;
		/deep/ {
			.backimg{
				width: 18rpx;
				height: 30rpx;
			}
			.customright{
				padding-right: 24rpx;
				color: #333;
			}
			.customcontent{
				font-size: 36rpx;
				color: #333;
				font-weight: bold;
			}
		}
	}
</style>
