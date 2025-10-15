<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="申请退款" color="#000000" :border="false" fixed placeholder :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="card">
			<view class="card_bottom fui-flex justify-between">
				<view class="font_size_14">
					退款原因
				</view>
				<view class="fui-flex font_size_14 color7" @click="open">
					<view class="">
						{{radioName ? radioName : "请选择"}}
					</view>
					<view class="margin-left-sm">
						<iconfont className="icon-youjiantou1" color="#999999" :size="6"></iconfont>
					</view>
				</view>
			</view>
			<view class="card_bottom fui-flex justify-between">
				<view class="font_size_14">
					退款金额
				</view>
				<view class="fui-flex font_size_14 color13">
					<view class="margin-right-xs font_size_16 text-bold color6">
						¥200
					</view>
					<view class="" style="margin-top: 5rpx;">
						<iconfont className="icon-beizhuweitianxie" color="#999999" :size="20"></iconfont>
					</view>
					<view class="">
						修改金额
					</view>
				</view>
			</view>
		</view>
		<view class="card ">
			<view class="card_explain padding-bottom-sm font_size_14">
				退款说明<text class="margin-left-xs color13 font_size_12">（选填）</text>
			</view>
			<view class="card_textarea">
				<textarea value="" placeholder="请输入退款说明" placeholder-class="txt_area" />
			</view>
		</view>
		<view class="card">
			<view class="card_explain padding-bottom-sm font_size_14">
				上传凭证<text class="margin-left-xs color13 font_size_12">（0/3）</text>
			</view>
			<view class="card_upload">
				<iconfont className="icon--camera" :size="40" color="#D1D1D1"></iconfont>
			</view>
		</view>
		<view class="submit" @click="sureBtn">
			<button class="submit-btn font_size_14 color14">提交申请</button>
		</view>
		<!-- 提交申请弹层 -->
		<u-popup :show="refundPopup">
			<view class="popup_bottom">
				<view class="fui-flex justify-end">
					<view class="popup_bottom_title font_size_16 text-bold">
						退款原因
					</view>
					<view class="" @click="close">
						<iconfont className="icon-quxiao" :size="14" color="#999999"></iconfont>
					</view>
				</view>
				<radio-group @change="radioChange" style="margin-top: 32rpx;">
					<label class="uni-list-cell uni-list-cell-pd" v-for="(item, index) in radioList" :key="index">
						<view class="popup_bottom_item">
							<view class="font_size_14">{{item.name}}</view>
							<view>
								<radio :value="JSON.stringify(item)" :checked="index === currentCopy" />
							</view>
						</view>
					</label>
				</radio-group>
				<view class="popup_bottom_cancel">
					<button class="cancel font_size_14 color14" @click="close">确定</button>
				</view>
			</view>
		</u-popup>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				refundPopup: false,
				current: 0,
				currentCopy: 0,
				radioList: [{
						value: '0',
						name: '描述与商品不符',
						checked: 'true',
					},
					{
						value: '1',
						name: '不想住了',
					},
					{
						value: '2',
						name: '填错预订时间',
					},
					{
						value: '3',
						name: '预订了其他房间',
					},
					{
						value: '4',
						name: '其他原因',
					}
				],
				radioTxt: "",
				radioName: "",
				order_id: 0
			};
		},
		created() {
			let that = this
			that.order_id = that.$route.params.order_id
		},
		methods: {
			open() {
				this.refundPopup = true
			},
			close() {
				let that = this
				that.currentCopy = that.current
				if (that.current === 0) {
					that.radioName = that.radioList[0].name
				} else {
					that.radioName = that.radioTxt
				}
				this.refundPopup = false
			},
			radioChange(evt) {
				let that = this
				for (let i = 0; i < that.radioList.length; i++) {
					console.log(that.radioList[i].value, evt.detail.value)
					if (that.radioList[i].value === JSON.parse(evt.detail.value).value) {
						that.current = i;
						that.radioTxt = JSON.parse(evt.detail.value).name
						break;
					}
				}
			},
			sureBtn() {
				let that = this
				const params = {}
				that.orderConfirm(params);
				that.close()
			},
			orderConfirm(params) {
				let that = this
				that.$api.hotelMobileOrderConfirm(that.order_id, 2, params).then(res => {
					console.log('订单操作成功', res);
					uni.showToast({
						title: res.message,
						icon: 'success'
					})
				}).then(err => {
					console.log('订单操作识别', err);
					uni.showToast({
						title: err.message,
						icon: 'error'
					})
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.container {
		padding-top: 16rpx;

		.card {
			margin-top: 0;
			padding: 0 32rpx;
			box-shadow: 0rpx 5rpx 20rpx 1rpx rgba(209, 209, 209, 0.3);

			.card_bottom {
				padding: 30rpx 0;
				border-bottom: 2rpx solid #F5F5F5;
			}

			.card_bottom:last-child {
				border-bottom: none;
			}

			.card_textarea {
				height: 160rpx;
				padding-bottom: 30rpx;

				textarea {
					width: 100%;
					height: 100%;
					background-color: #F9F9F9;
					padding: 24rpx 32rpx;
					box-sizing: border-box;
					border-radius: 10rpx;
					font-size: 28rpx;
				}

				.txt_area {
					color: #D1D1D1;
				}
			}

			.card_explain {
				padding: 30rpx 0;
			}

			.card_upload {
				width: 194rpx;
				height: 194rpx;
				line-height: 194rpx;
				border: 2rpx dotted #D1D1D1;
				border-radius: 10rpx;
				margin-bottom: 32rpx;
				text-align: center;
			}
		}

		.submit {
			margin: 0 32rpx;

			.submit-btn {
				width: 100%;
				height: 88rpx;
				line-height: 88rpx;
				background-color: #00AFC7;
				border-radius: 20rpx;
			}
		}

		.popup_bottom {
			width: 100%;
			padding: 32rpx 32rpx 56rpx;
			width: 686rpx;
			background: #FFFFFF;
			border-radius: 40rpx 40rpx 0 0;

			.popup_bottom_title {
				margin-right: 265rpx;
			}

			.popup_bottom_item {
				height: 100rpx;
				display: flex;
				align-items: center;
				justify-content: space-between;
				border-bottom: 1rpx solid #EEEEEE;
			}

			.popup_bottom_cancel {
				width: 100%;
				padding-top: 43rpx;

				.cancel {
					width: 100%;
					height: 88rpx;
					line-height: 88rpx;
					background-color: #00AFC7;
					border-radius: 20rpx;
				}
			}
		}

		::v-deep .uni-radio-input {
			width: 40rpx;
			height: 40rpx;
		}

		::v-deep .uni-radio-input-checked {
			position: relative;
			background-color: #00AFC7 !important;
			border-color: #00AFC7 !important;
			width: 40rpx;
			height: 40rpx;

			&::before {
				display: none;
			}

			&::after {
				position: absolute;
				top: 50%;
				left: 50%;
				transform: translate(-50%, -50%);
				content: '';
				border-radius: 50%;
				display: inline-block;
				width: 20rpx;
				height: 20rpx;
				background: #fff;
			}
		}

	}
</style>