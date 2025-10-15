<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="我的卡券" color="#000000" :border="false"  :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="bottom-tabs">
			<fui-tabs :tabs="tab" backgroundColor="#ffffff" color="#999999" sliderBgColor="#00AFC7"
				selectedColor="#000000" :size="28" :sliderWidth="32" :sliderHeight="4" :currentTab='currentTab'
				@change="tabsChange" :unlined="true" item-width="33.3333%">
			</fui-tabs>
		</view>
		<!-- 未使用 -->
		<block v-if="couponList.length > 0">
			<view class="assess_cards" v-for="item in couponList" :key="item.id">
				<view class="assess_cards-img fui-flex">
					<view class="assess_cards-img_left">
						<!-- 未使用 -->
						<block v-if="item.status === 0">
							<image src="https://oss.ddicms.cn/member/mys/cards_used.png" mode="" style="width: 100%;height: 100%;">
							</image>
						</block>
						<!-- 已使用 -->
						<block v-if="item.status === 1">
							<image src="https://oss.ddicms.cn/member/mys/cards_expird.png" mode="" style="width: 100%;height: 100%;">
							</image>
						</block>
						<!-- 已失效 -->
						<block v-if="item.status === 2">
							<image src="https://oss.ddicms.cn/member/mys/cards_expird.png" mode="" style="width: 100%;height: 100%;">
						</block>
						</image>
						<view class="card-img_txt flex-direction">
							<view class="card-img_txt_money">
								<text class="text-40">¥</text>{{Math.round(item.coupon.cash)}}
							</view>
							<view class="card-img_txt_used">
								满{{item.coupon.min_order_price}}可用
							</view>
						</view>
					</view>
					<view class="margin-left-lg card-img_full fui-flex-1 fui-flex justify-between">
						<view class="">
							<view class="font_size_16 text-bold">
								{{item.coupon_type_str}}
							</view>
							<view class="font_size_12 color13 margin-top-sm margin-bottom-xs">
								{{item.coupon.enable_store}}
							</view>
							<view class="font_size_12 color13">
								{{(item.end_time).slice(0,10)}}到期
							</view>
						</view>
						<!-- 未使用 -->
						<view v-if="item.status === 0" class="fui-col-1 color4 font_size_12 margin-right-lg">
							立即使用
						</view>
						<!-- 已使用 -->
						<view v-if="item.status === 1" class="color4 font_size_12">
							<image src="https://oss.ddicms.cn/member/mys/icon_used.png" mode=""
								style="width: 144rpx;height: 120rpx;margin: 60rpx 32rpx 0 0;"></image>
						</view>
						<!-- 已失效 -->
						<view v-if="item.status === 2" class="color4 font_size_12">
							<image src="https://oss.ddicms.cn/member/mys/icon_expired.png" mode=""
								style="width: 144rpx;height: 120rpx;margin: 60rpx 32rpx 0 0;"></image>
						</view>
					</view>
				</view>
			</view>
		</block>
		<view class="fui-flex flex-direction" v-else>
			<view class="" style="margin-top: 280rpx;">
				<image src="https://oss.ddicms.cn/member/order/null-data.png" mode="" style="width: 500rpx;height: 280rpx;"></image>
			</view>
			<view class="font_size_15 text-bold margin-tb-sm">
				暂无数据
			</view>
			<view class="font_size_12 color13">
				暂无优惠券数据哦
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				currentTab: 0,
				tab: [{
						name: '未使用',
						status: 0
					},
					{
						name: '已使用',
						status: 1
					},
					{
						name: '已失效',
						status: 2
					},
				],
				couponList: [], //列表
				query: {
					page: 1,
					pageSize: 10
				},
				total: undefined,
			};
		},
		onLoad() {
			let that = this
			that.getCouponList(false)
		},
		methods: {
			tabsChange(e) {
				let that = this
				that.currentTab = e.index;
				that.$set(that.query, 'page', 1)
				that.getCouponList(false)
			},
			// 优惠券列表
			getCouponList(flag) {
				let that = this
					that.$api.hotelCouponMycoupon(that.query.page, that.query.pageSize, that.tab[that.currentTab].status).then(res => {
						if (flag) {
							that.couponList = [...that.couponList, ...res.data.list]
						} else {
							that.couponList = res.data.list
						}
						that.total = res.data.total
					}).catch(err => {
						uni.showToast({
							title: err.message,
							icon: 'none'
						})
					})
			},
		},
		onReachBottom() {
			let that = this
			if ((that.query.page * that.query.pageSize) <= that.total) {
				that.query.page++
				that.getCouponList(true)
			} else {
				uni.showToast({
					title: '加载完毕',
					icon: 'none'
				})
			}
		}
	};
</script>

<style lang="scss" scoped>
	.container {
		padding-bottom: 30rpx;

		.bottom-tabs {
			position: sticky;
			top: 88rpx;
			height: 70rpx;
			z-index: 999;
		}

		.assess_cards {

			.assess_cards-img {
				position: relative;
				width: 100%;
				background: url('https://oss.ddicms.cn/member/mys/cards_bg.png') no-repeat center center;
				background-size: 100%;
				margin-top: 32rpx;

				.card-img_txt {
					position: absolute;
					left: 32rpx;
					top: 23rpx;
					width: 184rpx;
					text-align: center;
					color: #FFFFFF;

					.card-img_txt_money {
						font-size: 72rpx;
					}

					.card-img_txt_used {
						font-size: 24rpx;
					}
				}

				.assess_cards-img_left {
					width: 184rpx;
					height: 180rpx;
					margin-left: 32rpx;
				}
			}
		}
	}
</style>