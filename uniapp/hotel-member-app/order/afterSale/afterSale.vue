<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="我的售后" color="#000000" :border="false"  fixed placeholder :auto-back="true"></u-navbar>
		<!-- #endif -->
		<!-- tabs切换 -->
		<view class="">
			<fui-tabs2 ref="tab" :tabs="tab" backgroundColor="#ffffff" color="#999999" sliderBgColor="#00AFC7"
				selectedColor="#000000" :size="28" :sliderWidth="32" :sliderHeight="4" :currentTab='currentTab'
				@change="tabsChange" :unlined="true" itemWidth="50%">
			</fui-tabs2>
		</view>
		<!-- 订单 -->
		<view class="" v-if="currentTab === 0">
			<view class="card" v-for="(item,index) in 3" :key="index">
				<view class="card_msg fui-flex justify-between font_size_12">
					<view class="color13">
						订单号：12634551
					</view>
					<view class="color4">
						退款中
					</view>
				</view>
				<view class="card_txt">
					<view class="padding-tb-sm font_size_14 text-bold">
						维也纳酒店（高新路店）
					</view>
					<view class="card_txt_every fui-flex font_size_12 color13">
						<view class="fui-col-3">
							入住日期：
						</view>
						<view class="fui-col-12">
							2023-03-12至2023-03-13 1晚/1间
						</view>
					</view>
					<view class="card_txt_every fui-flex font_size_12 color13">
						<view class="fui-col-3">
							入住人：
						</view>
						<view class="fui-col-12">
							李思思
						</view>
					</view>
					<view class="card_txt_every fui-flex font_size_12 color13">
						<view class="fui-col-3">
							房间类型：
						</view>
						<view class="fui-col-12">
							独立房间·1室1卫1床
						</view>
					</view>
					<view class="card_txt_every fui-flex font_size_12 color13">
						<view class="fui-col-3">
							创建日期：
						</view>
						<view class="fui-col-12">
							2023-03-12
						</view>
					</view>
				</view>
				<view class="fui-flex justify-between padding-tb-sm">
					<view class="font_size_12 color13">
						退款金额：<text class="font_size_16 color6 text-bold">¥200</text>
					</view>
					<view class="">
						<button class="serve-btn font_size_12 color13">联系店家</button>
					</view>
				</view>
			</view>
		</view>
		<view class="" v-if="currentTab === 1">
			<view class="card" v-for="(item,index) in 3" :key="index">
				<view class="card_msg fui-flex justify-between font_size_12">
					<view class="color13">
						订单号：12634551
					</view>
					<view class="color13">
						已退款
					</view>
				</view>
				<view class="card_txt">
					<view class="padding-tb-sm font_size_14 text-bold">
						维也纳酒店（高新路店）
					</view>
					<view class="card_txt_every fui-flex font_size_12 color13">
						<view class="fui-col-3">
							入住日期：
						</view>
						<view class="fui-col-12">
							2023-03-12至2023-03-13 1晚/1间
						</view>
					</view>
					<view class="card_txt_every fui-flex font_size_12 color13">
						<view class="fui-col-3">
							入住人：
						</view>
						<view class="fui-col-12">
							李思思
						</view>
					</view>
					<view class="card_txt_every fui-flex font_size_12 color13">
						<view class="fui-col-3">
							房间类型：
						</view>
						<view class="fui-col-12">
							独立房间·1室1卫1床
						</view>
					</view>
					<view class="card_txt_every fui-flex font_size_12 color13">
						<view class="fui-col-3">
							创建日期：
						</view>
						<view class="fui-col-12">
							2023-03-12
						</view>
					</view>
				</view>
				<view class="fui-flex justify-between padding-tb-sm">
					<view class="font_size_12 color13">
						退款金额：<text class="font_size_16 color6 text-bold">¥200</text>
					</view>
					<view class="">
						<button class="serve-btn font_size_12 color13">联系店家</button>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				currentTab:0,
				tab:[
					{
						name:'待处理'
					},
					{
						name:'已退款'
					}
				]
			}
		},
		methods:{
			tabsChange(e) {
				let that = this
				that.currentTab = e.index
			}
		}
	}
</script>

<style lang="scss" scoped>
	.container {
		.card {
			padding: 0 32rpx;
			box-shadow: 0rpx 5rpx 20rpx 1rpx rgba(204,204,204,0.3);
			margin-bottom: 32rpx;
			.card_msg {
				padding: 24rpx 0;
				border-bottom: 2rpx solid #F5F5F5;
			}
			
			.card_txt {
				padding-bottom: 12rpx;
				border-bottom: 2rpx solid #F5F5F5;
				.card_txt_every {
					padding-bottom: 12rpx;
				}
			}
			
			.serve-btn {
				width: 158rpx;
				height: 56rpx;
				line-height: 50rpx;
				background-color: transparent;
				border: 2rpx solid #EEEEEE;
			}
		}
	}
</style>
