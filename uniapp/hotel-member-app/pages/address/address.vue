<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="地址管理" color="#000000" :border="false" fixed placeholder :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="card" v-for="(item,index) in addressList" :key="index">
			<view class="" style="padding: 32rpx 32rpx 0;">
				<view class="" @click="backAddress(item)">
					<view class="font_size_16 text-bold">
						{{item.name}} <text class="margin-left-sm">{{item.phone}}</text>
					</view>
					<view class="margin-tb-sm font_size_12 color13">
						陕西省西安市雁塔区{{item.detail}}
					</view>
				</view>
				<view class="card_default fui-flex justify-between">
					<view class="fui-flex">
						<view class="">
							<radio-group class="uni-list" @change="radioChange(index)">
								<label class="uni-list-cell uni-list-cell-pd">
									<radio :id="item.name" :value="item.name"
										:checked="item.is_default===1 ? true : false"></radio>
								</label>
							</radio-group>
						</view>
						<view class="font_size_12">
							设为默认
						</view>
					</view>
					<view class="fui-flex">
						<view class="fui-flex" @click="addressEdit(item)">
							<view class="">
								<iconfont className="icon-beizhuweitianxie" :size="25" color="#999999"></iconfont>
							</view>
							<view class="font_size_12">
								编辑
							</view>
						</view>
						<view class="fui-flex margin-left-lg" @click="addressDel(item)">
							<view class="margin-right-xs">
								<iconfont className="icon-shanchu" :size="15" color="#999999"></iconfont>
							</view>
							<view class="font_size_12">
								删除
							</view>
						</view>
					</view>
				</view>
			</view>
			<view class="card_img" v-if="item.is_default === 1">
				<image src="https://oss.ddicms.cn/member/fixed/address.png" mode=""
					style="width: 100%;height: 6rpx;padding: 0;display: block;"></image>
			</view>
		</view>

		<view class="address_add fui-flex justify-center" @click="addressAdd">
			<view class="">
				<iconfont className="icon-a-1_huaban1-031" color="#00AFC7"></iconfont>
			</view>
			<view class="color4 font_size_16 margin-left-xs">
				新增地址
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				addressList: [],
				selectedAddress: {}, //返回带回去的对象
				defaultId: undefined,
			};
		},
		onShow() {
			let that = this
			that.getAddressList()
		},
		methods: {
			// 获取地址列表
			getAddressList() {
				let that = this
				that.$api.integralAddressLists().then(res => {
					that.addressList = res.data
				}).catch(res => {
					uni.showToast({
						title: res.message,
						icon: 'none'
					})
				})
			},
			// 设置地址
			radioChange(index) {
				let that = this
				that.addressList.forEach((item, ins) => {
					if (index !== ins) {
						item.is_default = 0
					} else {
						item.is_default = 1
					}
					if (item.is_default === 1) {
						that.defaultId = item.address_id
					}
				})

				that.$api.integralAddressGetdefault(that.defaultId).then(res => {
					if (res.code === 200) {
						that.getAddressList()
					}
				}).catch(res => {
					uni.showToast({
						title: res.message,
						icon: 'none'
					})
				})
			},
			backAddress(item) {
				let that = this
				let address = {
					address_id: item.address_id,
					username: item.name,
					mobile: item.phone,
					address: (item.province_id + item.city_id + item.region_id + item.detail),
				}
				if (that.$Route.query.isDefault !== 0) {
					// 触发全局事件,向订单页面传递地址信息
					uni.$emit('addressSelected', address)
					setTimeout(() => {
						that.$Router.back(1)
					}, 500)
				}
			},
			// 添加地址
			addressAdd() {
				let that = this
				that.$Router.push({
					name: 'addressAdd'
				})
			},
			// 编辑地址
			addressEdit(item) {
				let that = this
				that.$Router.push({
					name: 'addressAdd',
					params: {
						address_id: item.address_id,
						username: item.name,
						mobile: item.phone,
						location_p: item.province_id,
						location_c: item.city_id,
						location_a: item.region_id,
						addressDetail: item.detail,
						is_default: item.is_default
					}
				})
			},
			// 删除地址
			addressDel(item) {
				let that = this
				that.$api.integralAddressDeletes(item.address_id).then(res => {
					that.getAddressList()
					setTimeout(() => {
						uni.showToast({
							title: '删除成功',
							icon: 'none'
						})
					}, 500)
				}).catch(res => {
					uni.showToast({
						title: res.message,
						icon: 'none'
					})
				})
			},
		}
	}
</script>

<style lang="scss" scoped>
	.container {
		padding-top: 24rpx;

		.card {
			padding: 0;
			box-shadow: 0rpx 5rpx 20rpx 1rpx rgba(209, 209, 209, 0.3);
			margin-top: 0;

			.card_default {
				border-top: 2rpx solid #F5F5F5;
				padding: 24rpx 0;
			}

		}

		.address_add {
			position: fixed;
			bottom: 0;
			left: 0;
			width: 100%;
			height: 100rpx;
			background-color: #EBF9FB;
		}
	}

	::v-deep .uni-radio-input {
		position: relative;
		width: 32rpx;
		height: 32rpx;
		border-radius: 50%;
		background-color: #FFFFFF;
		border-color: #EEEEEE !important;
	}

	::v-deep .uni-radio-input-checked {
		position: relative;
		background-color: #FFFFFF !important;
		border-color: #00AFC7 !important;

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
			background: #00AFC7;
		}
	}
</style>