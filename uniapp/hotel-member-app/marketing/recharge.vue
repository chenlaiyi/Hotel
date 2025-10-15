<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="充值" color="#000000" :border="false"  :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="recharge">
			<view class="font_size_14 text-bold margin-tb-sm">
				选择金额
			</view>
			<view class="recharge_wrap fui-flex">
				<view class="recharge_wrap_border font_size_12 text-bold" v-for="(item,index) in moneyList" :key="index"
					@click="selectBtn(index)">
					<view :class="{'select':item.clicked}">
						￥<text class="font_size_24">{{item.moneyNum}}</text>
						<view class="recharge_abs" v-if="index >=2">
							<view class="" style="font-size: 20rpx; -webkit-transform: scale(0.8);">
								赠￥{{item.giveNum}}
							</view>
						</view>
					</view>
				</view>
				<view class="recharge_wrap_border" @click="customBtn('center')" :class="!defaultVal ? 'font_size_12' : 'font_size_24'" :style="{'font-weight':defaultVal ? 'bold' : 'thin'}">
					{{defaultVal ? '￥'+defaultVal : '自定义金额'}}
				</view>
			</view>
		</view>
		<view class="recharge">
			<view class="font_size_14 text-bold margin-tb-sm">
				选择支付方式
			</view>
			<view class="popup_bottom">
				<radio-group @change="radioChange" style="margin-top: 32rpx;">
					<label class="uni-list-cell uni-list-cell-pd" v-for="(item, index) in radioList" :key="index">
						<view class="popup_bottom_item">
							<view class="fui-flex">
								<view class="">
									<image :src="item.url" mode="" style="width: 40rpx;height: 40rpx;margin-top:10rpx;">
									</image>
								</view>
								<view class="font_size_14 margin-left-xs">{{item.name}}</view>
							</view>
							<view>
								<radio :value="item.value" :checked="index === current" />
							</view>
						</view>
					</label>
				</radio-group>
			</view>

			<view class="">
				<button class="sure_btn font_size_14 color14">确认充值</button>
			</view>
		</view>
		
		<uni-popup ref="popup" type="center">
			<view class="popup_custom">
				<view class="popup_custom_con">
					<view class="popup_custom_title">
						自定义金额
					</view>
					<view class="popup_custom_input">
						<input v-model="customVal" type="text" placeholder="请输入金额">
					</view>
					<view class="popup_custom_btn fui-flex justify-around">
						<view class="" @click="closeLy">
							取消
						</view>
						<view class="line"></view>
						<view class="" @click="sureBtn">
							确定
						</view>
					</view>
				</view>
			</view>
		</uni-popup>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				moneyList: [{
						moneyNum: 50,
						clicked: false

					},
					{
						moneyNum: 100,
						clicked: false
					},
					{
						moneyNum: 300,
						giveNum: 10,
						clicked: false
					},
					{
						moneyNum: 500,
						giveNum: 30,
						clicked: false
					},
					{
						moneyNum: 1000,
						giveNum: 100,
						clicked: false
					}
				],
				current: 0,
				radioList: [{
						value: '1',
						name: '微信支付',
						checked: 'true',
						url: '/static/svgs/wechatpay.svg'
					},
					{
						value: '2',
						name: '网银支付',
						url: '/static/svgs/wangpay.svg'
					},
				],
				customVal:'',
				defaultVal:'',
			};
		},
		methods: {
			selectBtn(index) {
				let that = this
				that.moneyList.forEach((item,i)=>{
					if(i===index) {
						item.clicked = true
					} else {
						item.clicked = false
					}
				})
			},
			radioChange(evt) {
				for (let i = 0; i < this.radioList.length; i++) {
					if (this.radioList[i].value === evt.detail.value) {
						this.current = i;
						break;
					}
				}
			},
			openLy() {
				let that = this
				that.$refs.popup.open()
			},
			closeLy() {
				let that = this
				that.$refs.popup.close()
			},
			customBtn(type) {
				let that = this
				that.type = type
				that.openLy(type)
			},
			// 确认支付
			sureBtn() {
				let that = this
				that.defaultVal = that.customVal
				that.closeLy()
			}
		}
	};
</script>

<style lang="scss" scoped>
	.container {
		.recharge {
			padding: 0 32rpx;

			.recharge_wrap {
				flex-wrap: wrap;

				.recharge_wrap_border {
					position: relative;
					width: 328rpx;
					height: 120rpx;
					line-height: 120rpx;
					text-align: center;
					border: 2rpx solid #EEEEEE;
					border-radius: 20rpx;
					margin-bottom: 32rpx;
					box-sizing: border-box;

					.recharge_abs {
						position: absolute;
						right: 0;
						top: 0;
						height: 32rpx;
						line-height: 32rpx;
						background-color: #00AFC7;
						border-radius: 0 20rpx 0 20rpx;
						color: #ffffff;
					}
				}


				.recharge_wrap_border:nth-child(2n-1) {
					margin-right: 30rpx;
				}

				.select {
					width: 100%;
					height: 100%;
					background-color: #EBF9FB;
					border: 2rpx solid #00AFC7;
					color: #00AFC7;
					box-sizing: border-box;
					border-radius: 20rpx;
				}


			}

			.popup_bottom {
				.popup_bottom_item {
					padding: 32rpx 0;
					display: flex;
					align-items: center;
					justify-content: space-between;
					border-bottom: 1rpx solid #EEEEEE;
				}
			}

			::v-deep .uni-radio-input {
				width: 40rpx;
				height: 40rpx;
			}

			::v-deep .uni-radio-input-checked {
				background-color: #00AFC7 !important;
				border-color: #00AFC7 !important;
			}

			.sure_btn {
				width: 100%;
				height: 88rpx;
				line-height: 88rpx;
				background-color: #00AFC7;
				border-radius: 20rpx;
				margin-top: 48rpx;
			}
		}
		
		.popup_custom {
			width: 750rpx;
			.popup_custom_con {
				background-color: #FFFFFF;
				margin: 0 32rpx;
				box-sizing: border-box;
				border-radius: 20rpx;
				
				.popup_custom_title {
					text-align: center;
					font-size: 32rpx;
					font-weight: bold;
					padding: 32rpx 0;
				}
				
				.popup_custom_input {
					width: 100%;
					padding: 0 96rpx 32rpx;
					box-sizing: border-box;
				}
				
				.popup_custom_btn {
					margin: 0 32rpx;
					padding: 32rpx 0;
					.line {
						width: 2rpx;
						height: 40rpx;
						background-color: #d1d1d1;
					}
				}
			}
		}
	}
</style>
