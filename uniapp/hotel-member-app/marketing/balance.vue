<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="我的余额" color="#000000" :border="false"  :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="balance">
			<view class="balance_bg">
				<image src="https://oss.ddicms.cn/member/mys/balance_bg.png" mode="" style="width: 100%;height: 100%;"></image>
				<view class="balance_back">
					<view class="fui-flex">
						<view class="balance_back_btn" @click="backBtn">
							<iconfont className="icon-zuojiantou" :size="20" color="#FFFFFF"></iconfont>
						</view>
						<view class="font_size_15 color14">
							我的余额
						</view>
					</view>
				</view>
				<view class="balance_content">
					<view class="fui-flex justify-between align-center">
						<view class="" style="margin-left: 32rpx;">
							<view class="balance_content_txt">
								账户余额
							</view>
							<view class="balance_content_num">
								2,000
							</view>
						</view>
						<view class="balance_content_btn" @click="rechargeBtn">
							<button class="recharge_btn round bg-white color4">充值</button>
						</view>
					</view>
				</view>

			</view>
			<view class="card">
				<view class="bottom-tabs">
					<fui-tabs2 ref="tab" :tabs="tab" color="#999999" sliderBgColor="#00AFC7" selectedColor="#000000"
						:size="28" :sliderWidth="32" :sliderHeight="6" :currentTab='currentTab' @change="tabsChange"
						:bold="true" itemWidth="50%" :unlined="true">
					</fui-tabs2>
				</view>
				<block v-if="currentTab === 0">
					<view class="card_txt fui-flex justify-between" v-for="(item,index) in 3" :key="index">
						<view class="">
							<view class="font_size_14">
								余额充值
							</view>
							<view class="font_size_12 color13 margin-top-xs">
								2023-03-17 14:23
							</view>
						</view>
						<view class="text-bold font_size_18">
							+10
						</view>
					</view>
				</block>
				<block v-if="currentTab === 1">
					<view class="card_txt fui-flex justify-between" v-for="(item,index) in 3" :key="index">
						<view class="">
							<view class="font_size_14">
								酒店预订
							</view>
							<view class="font_size_12 color13 margin-top-xs">
								2023-03-17 14:23
							</view>
						</view>
						<view class="text-bold font_size_18">
							-256
						</view>
					</view>
				</block>
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
						name: '充值明细'
					},
					{
						name: '使用明细'
					}
				],
			};
		},
		onLoad() {},
		onShow() {
			let that = this;
		},
		methods: {
			tabsChange(e) {
				let that = this
				that.currentTab = e.index
			},
			backBtn() {
				let that = this
				that.$Router.back()
			},
			// 充值
			rechargeBtn() {
				let that = this
				that.$Router.push({
					name:'myrecharge'
				})
			}
		}
	};
</script>

<style lang="scss" scoped>
	.container {
		.balance {
			.balance_bg {
				position: relative;
				width: 100%;
				height: 422rpx;

				.balance_back {
					position: absolute;
					top: 80rpx;
					left: 40rpx;

					.balance_back_btn {
						margin-right: 260rpx;
					}
				}

				.balance_content {
					position: absolute;
					top: 50%;
					left: 0;
					width: 100%;
					color: #fff;
					transform: translateY(-50%);

					.balance_content_txt {
						font-size: 20rpx;
					}

					.balance_content_num {
						font-size: 64rpx;
						font-weight: 600;
					}

					.balance_content_btn {
						margin-right: 32rpx;

						.recharge_btn {
							width: 160rpx;
							height: 56rpx;
							font-size: 24rpx;
						}
					}
				}


			}

			.card {
				position: relative;
				margin-top: -115rpx;
				box-shadow: 0rpx 5rpx 20rpx 1rpx rgba(209, 209, 209, 0.3);
				overflow: hidden;
				padding: 0 32rpx;

				.bottom-tabs {
					width: 750rpx;
					transform: translateX(-64rpx);
					display: block !important;
					border-radius: 10rpx;
				}

				.card_txt {
					padding: 22rpx 0;
					border-bottom: 2rpx solid #F5F5F5;
					font-size: 28rpx;

				}
			}

		}
	}

</style>
