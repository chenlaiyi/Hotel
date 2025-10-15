<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="地址编辑" color="#000000" :border="false" fixed placeholder :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="card">
			<view class="buildingInfo_item fui-flex justify-between">
				<view class="font_size_14 color2">
					联系人
				</view>
				<view class="fui-flex">
					<input class="input_con" type="text" placeholder="请输入联系人" v-model="form.username">
				</view>
			</view>
			<view class="buildingInfo_item fui-flex justify-between">
				<view class="font_size_14 color2">
					联系电话
				</view>
				<view class="fui-flex">
					<input class="input_con" type="text" placeholder="请输入联系电话" v-model="form.mobile">
				</view>
			</view>
			<view class="buildingInfo_item fui-flex justify-between" @click="showAdressPicker = true">
				<view class="font_size_14 color2">
					所在地区
				</view>
				<view class="fui-flex">
					<text
						style="font-size: 28rpx;margin-right: 10rpx;color: #999999;">{{form.area ? form.area : '省市区县、乡镇等'}}</text>
					<iconfont className="icon-youjiantou1" size="12" color="#999999"></iconfont>
				</view>
			</view>
			<view class="buildingInfo_item fui-flex justify-between">
				<view class="font_size_14 color2">
					详细地址
				</view>
				<view class="fui-flex">
					<input class="input_con" type="text" placeholder="请输入详细地址" v-model="form.addressDetail">
				</view>
			</view>
			<view class="buildingInfo_item fui-flex justify-between">
				<view class="font_size_14 color2">
					设为默认
				</view>
				<view class="fui-flex">
					<switch color="#00AFC7" style="transform:scale(0.8,0.7);" :checked="form.is_default"
						@change="changeDefault" />
				</view>
			</view>
		</view>
		<view class="serve">
			<button class="serve-btn" @click="saveBtn">保存</button>
		</view>

		<address-picker :show='showAdressPicker' @cancel='closeAdressPickerFn' @confirm='confirmAdressPickerFn'
			:closeOnClickOverlay='true' @close='closeAdressPickerFn'></address-picker>

	</view>
</template>
<script>
	export default {
		data() {
			return {
				showAdressPicker: false, //地区
				form: {
					username: '',
					mobile: '',
					area: '',
					is_default: false, //是否默认
					location_p: '', //  省
					location_c: '', //  市
					location_a: '', //  区/县
					addressDetail: '', // 详情地址
				},
				address_id: undefined,
				// is_default:undefined,
			}
		},
		onLoad(options) {
			let that = this
			that.address_id = options.address_id
			that.form = {
				username: options.username,
				mobile: options.mobile,
				area: options.location_p + options.location_c + options.location_a,
				// location_p: options.location_p, //  省
				// location_c: options.location_c, //  市
				// location_a: options.location_a, //  区/县
				addressDetail: options.addressDetail, // 详情地址
			}
			if (+options.is_default === 1) {
				that.form.is_default = true
			} else if (+options.is_default === 0) {
				that.form.is_default = false
			}
		},
		computed: {
			navShowTitle() {
				return this.address_id ? "编辑地址" : "新增地址"
			}
		},
		methods: {
			changeDefault(e) {
				let that = this
				if (e.detail.value === true) {
					that.form.is_default = 1
				} else {
					that.form.is_default = 0
				}
			},
			saveBtn() {
				let that = this
				if (that.address_id) {
					// 编辑
					that.$api.integralAddressEdit(that.form.username, that.form.mobile, that.form.location_p, that.form
						.location_c, that.form.location_a, that.form.addressDetail, that.address_id, that.form
						.is_default).then(res => {
						this.$Router.back(1)
						setTimeout(() => {
							uni.showToast({
								icon: "none",
								title: '修改成功'
							})
						}, 200)
					}).catch(res => {
						uni.showToast({
							title: res.message,
							icon: 'none'
						})
					})

				} else {
					// 添加
					if (that.form.username === '') {
						return uni.showToast({
							title: '请输入联系人',
							icon: 'none'
						})
					}
					if (that.form.mobile === '') {
						return uni.showToast({
							title: '请输入手机号',
							icon: 'none'
						})
					}
					if (that.form.location_a === '' && that.form.location_c === '' && that.form.location_p === '') {
						return uni.showToast({
							title: '请输入省市、区县乡镇等',
							icon: 'none'
						})
					}
					if (that.form.addressDetail === '') {
						return uni.showToast({
							title: '请输入详细地址',
							icon: 'none'
						})
					}
					that.$api.integralAddressAdd(that.form.username, that.form.mobile, that.form.location_p, that.form
						.location_c, that.form.location_a, that.form.addressDetail, that.address_id, that.form
						.is_default).then(
						res => {
							this.$Router.back(1)
							setTimeout(() => {
								uni.showToast({
									icon: "none",
									title: '添加成功'
								})
							}, 200)
						}).catch(res => {
						uni.showToast({
							title: res.message,
							icon: 'none'
						})
					})
				}
			},
			confirmAdressPickerFn(e) {
				let that = this
				that.$set(that.form, 'location_p', e.areaId[0])
				that.$set(that.form, 'location_c', e.areaId[1])
				that.$set(that.form, 'location_a', e.areaId[2])
				if (Array.from(new Set(e.value)).length === 2) {
					that.$set(that.form, 'area', Array.from(new Set(e.value))[0] + '/' + Array.from(new Set(e
						.value))[1])
				} else if (Array.from(new Set(e.value)).length === 3) {
					that.$set(that.form, 'area', Array.from(new Set(e.value))[0] + '/' + Array.from(new Set(e
						.value))[1] + '/' + Array.from(new Set(e.value))[2])
				}
				that.showAdressPicker = false
			},
			closeAdressPickerFn() {
				let that = this
				that.showAdressPicker = false
			},
		}
	}
</script>
<style lang="scss">
	.container {
		.card {
			padding: 0 32rpx;
			border-radius: 20rpx;
			box-shadow: 0rpx 5rpx 20rpx 1rpx rgba(209, 209, 209, 0.3);

			.buildingInfo_item {
				padding: 30rpx 0;
				height: 100rpx;
				box-sizing: border-box;
				border-bottom: 1rpx solid #F5F5F5;

				.input_con {
					text-align: end;
					font-size: 28rpx;
					color: #999999;
				}

				.uni-input-placeholder {
					font-size: 28rpx;
					color: #999999;
				}
			}
		}

		.serve {
			margin: 16rpx 32rpx 0;

			.serve-btn {
				width: 100%;
				height: 88rpx;
				line-height: 88rpx;
				border-radius: 20rpx;
				background-color: #00AFC7;
				font-size: 28rpx;
				color: #FFFFFF;
				box-sizing: border-box;
			}
		}

	}

	::v-deep .uni-switch-wrapper .uni-switch-input {
		&::after {
			width: 12rpx;
			height: 12rpx;
			top: 22rpx;
			left: 15rpx;
			background-color: #999999;
		}
	}

	::v-deep .uni-switch-wrapper .uni-switch-input-checked {
		&::after {
			left: 6rpx;
			top: 5rpx;
			width: 48rpx;
			height: 48rpx;
			background-color: #fff;
		}
	}
</style>