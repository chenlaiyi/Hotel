<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="心愿单" color="#000000" :border="false" :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="card fui-flex" v-for="item in wishList" :key="item.id">
			<view class="fui-flex">
				<view class="font_size_16">
					{{item.title}}
				</view>
				<view class="" @click="editBtn(item)" style="z-index: 99;">
					<iconfont className="icon-beizhuweitianxie" :size="24" style="margin-top: 6rpx;" color="#999999">
					</iconfont>
				</view>
			</view>
			<view class="fui-flex" @click="wishdetailBtn(item)" style="flex: 1;justify-content: end;">
				<view class="font_size_16 color13">
					{{item.total}}
				</view>
				<view class="">
					<iconfont className="icon-youjiantou1" :size="14" style="margin: 5rpx 0 0 10rpx;" color="#999999">
					</iconfont>
				</view>
			</view>
		</view>
		<view class="wish_btn">
			<view class="fui-flex justify-center wish_btn_bg" @click="foundBtn">
				<view class="margin-right-xs">
					<iconfont className="icon-a-1_huaban1-031" color="#00AFC7"></iconfont>
				</view>
				<view class="font_size_16 color4">
					创建心愿单
				</view>
			</view>
		</view>

		<!-- 创建心愿单 -->
		<fui-modal :show="showModal" :custom='true' @cancel='cancelModal'>
			<view class="modalTitle">
				创建心愿单
			</view>
			<view class="modallMsg">
				请输入心愿单名称最多10个字符
			</view>
			<view class="modalInput">
				<input type="text" v-model="foundWishTxt" placeholder="请输入" style="width: 90%;">
				<view class="modalInput_clear" @click="foundWishTxt=''">
					×
				</view>
			</view>
			<view class="modalBtn fui-flex justify-between align-center">
				<view class="modalBtn_cancel" @click='cancelModal'>
					取消
				</view>
				<view class="line">
					|
				</view>
				<view class="modalBtn_submit" style="color: #00AFC7;" @click="submitModal">
					确定
				</view>
			</view>
		</fui-modal>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				showModal: false,
				foundWishTxt: '',
				wishList: []
			};
		},
		onShow() {
			let that = this
			that.getWishList()
		},
		methods: {
			// 心愿单列表
			getWishList() {
				let that = this
				that.$api.hotelWishList().then(res => {
					that.wishList = res.data
				}).catch(res => {
					uni.showToast({
						title: res.message,
						icon: 'none'
					})
				})
			},
			foundBtn() {
				let that = this
				that.showModal = true
			},
			cancelModal() {
				this.showModal = false
			},
			// 添加心愿单
			submitModal() {
				let that = this
				if (that.foundWishTxt !== '') {
					that.$api.hotelWishAdd(that.foundWishTxt).then(res => {
						uni.showToast({
							title: "心愿单创建成功",
							icon: 'none'
						})
						that.getWishList()
						this.showModal = false
					}).catch(res => {
						uni.showToast({
							title: res.message,
							icon: 'none'
						})
					})
				} else {
					uni.showToast({
						title: '心愿单名称不能为空',
						icon: 'none'
					})
				}
			},
			// 编辑心愿单
			editBtn(item) {
				let that = this
				that.$Router.push({
					name: 'wishedit',
					params: {
						cate_id: item.id,
						title: item.title
					}
				})
			},
			// 心愿单详情
			wishdetailBtn(item) {
				let that = this
				that.$Router.push({
					name: 'wishdetail',
					params: {
						title: item.title
					}
				})
			}

		}

	};
</script>

<style lang="scss" scoped>
	.container {
		.card {
			box-shadow: 0rpx 5rpx 20rpx 1rpx rgba(209, 209, 209, 0.3);
			margin-bottom: 0;
		}

		.wish_btn {
			margin: 32rpx 32rpx 0;

			.wish_btn_bg {
				width: 100%;
				height: 108rpx;
				background-color: #EBF9FB;
				border-radius: 20rpx;
			}
		}

		.modalTitle {
			font-size: 36rpx;
			font-weight: 600;
			color: #000000;
			text-align: center;
		}

		.modallMsg {
			font-size: 28rpx;
			font-weight: 300;
			color: #000000;
			text-align: center;
			margin-top: 16rpx;
		}

		.modalInput {
			position: relative;
			margin-top: 40rpx;
			padding-bottom: 23rpx;
			border-bottom: 1rpx solid #999999;

			.modalInput_clear {
				position: absolute;
				right: 0;
				bottom: 27rpx;
				width: 36rpx;
				height: 36rpx;
				line-height: 30rpx;
				text-align: center;
				border-radius: 50%;
				color: #999999;
				background: #EEEEEE;
			}
		}

		.modalBtn {
			margin-top: 52rpx;

			.line {
				height: 42rpx;
				line-height: 36rpx;
				color: #CBCBCB;
			}

			.modalBtn_cancel,
			.modalBtn_submit {
				width: 278rpx;
				font-size: 30rpx;
				font-weight: 400;
				color: #000000;
				text-align: center;
			}
		}
	}

	::v-deep .fui-modal-mask {
		z-index: 996;
	}

	::v-deep .fui-modal-box {
		z-index: 998;
	}
</style>