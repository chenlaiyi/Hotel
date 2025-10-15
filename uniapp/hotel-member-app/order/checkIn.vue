<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="选择入住人" color="#000000" :border="false"  fixed placeholder :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="card fui-flex justify-center" @click="addFriends">
			<view class="margin-right-xs">
				<iconfont className="icon-a-1_huaban1-031" color="#00AFC7"></iconfont>
			</view>
			<view class="color4 font_size_14">
				添加入住人
			</view>
		</view>
		<view class="card" style="padding: 0 32rpx;">
			<checkbox-group class="uni-list uni-list-cell-pd" @change="checkboxChange">
				<view class="check_in fui-flex justify-between" v-for="(item,index) in friendsList" :key="item.id">
					<view class="fui-flex">
						<view class="margin-right-xs" style="align-self: flex-start;">
							<label class="uni-list-cell uni-list-cell-pd">
								<view>
									<checkbox :value="item.id+''" :checked="false"></checkbox>
								</view>
							</label>
						</view>
						<view class="">
							<view class="font_size_16 text-bold">
								{{item.realname}}
							</view>
							<view class="padding-tb-xs font_size_14 color2">
								电话号码：{{item.mobile}}
							</view>
							<view class="font_size_14 color2">
								身份证号：{{item.icard_code}}
							</view>
						</view>
					</view>
					<view class="" style="align-self: flex-start;" @click="editFriend(item)">
						<iconfont className="icon-beizhuweitianxie" :size="26" color="#999999"
							style="font-weight: bold;">
						</iconfont>
					</view>
				</view>
			</checkbox-group>
		</view>
		<view class="sure" @click="sureBtn">
			<button class="sure_btn">确定</button>
		</view>

		<!-- 底部弹层 -->
		<view>
			<uni-popup ref="popupDetail" background-color="#fff" type="bottom">
				<view class="popup-content popup-add">
					<view class="popup-pad popup-pad_detail">
						{{popupShowTitle}}
					</view>
					<view class="fui-flex justify-between popup-border">
						<view class="line_item_left fui-col-3">
							住客姓名
						</view>
						<view class="popup-inp fui-col-9">
							<input v-model="form.userName" type="text" placeholder="请输入住客姓名" style="font-size: 28rpx;"
								placeholder-style="font-size:28rpx;color:#D1D1D1;">
						</view>
					</view>
					<view class="fui-flex justify-between popup-border">
						<view class="line_item_left fui-col-3">
							电话号码
						</view>
						<view class="popup-inp fui-col-9">
							<input v-model="form.userMobile" type="text" placeholder="请输入电话号码" style="font-size: 28rpx;"
								placeholder-style="font-size:28rpx;color:#D1D1D1;">
						</view>
					</view>
					<view class="fui-flex justify-between popup-border">
						<view class="line_item_left fui-col-3">
							身份证号
						</view>
						<view class="popup-inp fui-col-9">
							<input type="text" v-model="form.icard_code" placeholder="选填" style="font-size: 28rpx;"
								placeholder-style="font-size:28rpx;color:#D1D1D1;">
						</view>
					</view>
					<view class="fui-flex justify-between" style="padding: 48rpx 0;">
						<button v-if="form.id" class="delect com" @click="deleteBtn">删除</button>
						<button v-if="!form.id" class="cancel com" @click="cancelBtn">取消</button>
						<button class="success com" @click="finishBtn">确定</button>
					</view>
				</view>
			</uni-popup>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				form: {
					userName: '',
					userMobile: '',
					icard_code: ''
				},
				friendsList: [],
				choseArr: [],
				selectArr: [],
			};
		},
		onLoad() {
			let that = this
			that.getFriendsList()
		},
		computed: {
			popupShowTitle() {
				return this.form.id ? '编辑入住人' : '新增入住人'
			},
		},
		methods: {
			// 朋友列表
			getFriendsList() {
				let that = this
				that.$api.hotelMemberMyfriend().then(res => {
					that.friendsList = res.data
				}).catch(res => {
					uni.showToast({
						title: res.message,
						icon: 'none'
					})
				})
			},
			// 添加朋友
			addFriends() {
				let that = this
				that.$set(that.form, 'userName', '')
				that.$set(that.form, 'userMobile', '')
				that.$set(that.form, 'icard_code', '')
				that.$set(that.form, 'id', '')
				that.$refs.popupDetail.open()
			},
			finishBtn() {
				let that = this
				if (that.form.id) {
					that.fui
						.request('/diandi_hotel/member/editfriend', 'POST', {
							
						}, false)
						that.$api.hotelMemberEditfriend().then(res => {
							if (res.code === 200) {
								that.$refs.popupDetail.close()
								that.getFriendsList()
							}
						}).catch(err => {
							uni.showToast({
								title: err.message,
								icon: 'none'
							})
						})
				} else {
					if (that.form.userName === '') {
						return uni.showToast({
							title: '请先填写姓名',
							icon: 'none'
						})
					}
					if (that.form.userMobile === '') {
						return uni.showToast({
							title: '请填写手机号',
							icon: 'none'
						})
					}
					that.fui
						.request('/diandi_hotel/member/addfriend', 'POST', {
							face_img: '',
							realname: that.form.userName,
							mobile: that.form.userMobile,
							icard_code: that.form.icard_code,
							icard_front: '',
							icard_back: ''
						}, false)
						.then(res => {
							if (res.code === 200) {
								that.$refs.popupDetail.close()
								that.getFriendsList()
							}
						}).catch(err => {
							uni.showToast({
								title: err.message,
								icon: 'none'
							})
						})
				}
			},
			// 删除朋友
			deleteBtn() {
				let that = this
				that.fui
					.request('/diandi_hotel/member/delfriend', 'POST', {
						friend_id: that.form.id
					}, false)
					.then(res => {
						if (res.code === 200) {
							that.getFriendsList()
							that.$refs.popupDetail.close()
						}
					}).catch(err => {
						uni.showToast({
							title: err.message,
							icon: 'none'
						})
					})
			},
			// 编辑朋友
			editFriend(item) {
				let that = this
				that.$refs.popupDetail.open()
				that.$set(that.form, 'userName', item.realname)
				that.$set(that.form, 'userMobile', item.mobile)
				that.$set(that.form, 'icard_code', item.icard_code)
				that.$set(that.form, 'id', item.id)
			},
			cancelBtn() {
				let that = this
				that.$refs.popupDetail.close()
			},
			sureBtn() {
				let that = this
				uni.$emit('selectArr', that.selectArr)
				setTimeout(function() {
					that.$Router.back(1)
				}, 300)
			},
			checkboxChange(e) {
				let arr = []
				const val = e.detail.value
				console.log(val)
				this.selectArr = this.friendsList.filter(item => val.includes(item.id + ''))
			},
		}
	}
</script>

<style lang="scss" scoped>
	.container {
		.card {
			box-shadow: 0rpx 5rpx 20rpx 1rpx rgba(209, 209, 209, 0.3);

			.check_in {
				padding: 32rpx 0;
				border-bottom: 2rpx solid #F5F5F5;
			}
		}

		.sure {
			margin: 0 32rpx;

			.sure_btn {
				height: 88rpx;
				line-height: 88rpx;
				background-color: #00AFC7;
				font-size: 28rpx;
				color: #ffffff;
			}
		}

		.popup-content {
			width: 750rpx;
			box-sizing: border-box;
			padding: 32rpx 32rpx 0;
			border-radius: 40rpx 40rpx 0rpx 0rpx;
			background-color: #fff;
			margin-bottom: 120rpx;
		}

		.popup-add {
			margin-bottom: 0;
		}

		.popup-height {
			width: 200px;
		}

		.popup-pad {
			padding-bottom: 48rpx;
			font-size: 32rpx;
			color: #000000;
			font-weight: 600;
		}

		.popup-bot {
			padding-bottom: 24rpx;
			font-size: 28rpx;
			color: #000000;
		}

		.popup-txt {
			font-size: 32rpx;
			font-weight: 600;
		}

		.popup-all {
			font-size: 48rpx;
		}

		.popup-red {
			color: #FF0000;
		}

		.popup-top {
			font-size: 32rpx;
			padding: 31rpx 0 40rpx;
			border-top: 1rpx solid #EEEEEE;
			border-bottom: 1rpx solid #EEEEEE;
		}

		.popup-pad_detail {
			text-align: center;
			font-size: 36rpx;
		}

		.popup-border {
			padding: 24rpx 0;
			border-bottom: 1rpx solid #F5F5F5;
		}

		/* 公共按钮样式 */
		.com {
			width: 320rpx;
			height: 72rpx;
			line-height: 72rpx;
			font-size: 28rpx;
		}

		.delect {
			border: 2rpx solid #FB5B32;
			color: #FB5B32;
		}

		.cancel {
			background-color: #fff;
			color: #000000;
			border: 2rpx solid #EEEEEE;
		}

		.success {
			background-color: #00AFC7;
			color: #fff;
		}
	}


	::v-deep .uni-checkbox-input {
		width: 38rpx;
		height: 38rpx;
		border-radius: 50%;
		border-color: #999999 !important;
	}

	::v-deep .uni-checkbox-input-checked {
		width: 38rpx;
		height: 38rpx;
		border-radius: 50%;
		background-color: #00AFC7 !important;
		border-color: #00AFC7 !important;
	}

	::v-deep .uni-select {
		border: none;
	}

	::v-deep uni-checkbox:not([disabled]) .uni-checkbox-input:hover {
		color: #999999;
	}

	::v-deep .uni-checkbox-input.uni-checkbox-input-checked:before {
		color: #ffffff;
		font-size: 28rpx;
	}
</style>