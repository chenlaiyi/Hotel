<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="编辑心愿单" color="#000000" :border="false" :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="card">
			<input type="text" v-model="editname" placeholder="请输入">
		</view>
		<view class="wish_del" @click="wishDel">
			<view class="fui-flex justify-center wish_del_bg">
				<view class="margin-right-xs">
					<iconfont className="icon-shanchu" :size="16" color="#FB5B32" style="margin-bottom: 5rpx;">
					</iconfont>
				</view>
				<view class="font_size_14 color6">
					删除
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				editname: '',
				cateId: null,
			};
		},
		onLoad(options) {
			let that = this
			that.editname = options.title
			that.cateId = options.cate_id
		},
		methods: {
			wishDel() {
				let that = this
				that.$api.hotelWishDel(that.cateId).then(res => {
					if (res.code === 200) {
						uni.showToast({
							title: '删除成功',
							icon: 'none'
						})
						that.$Router.back(1)
					}
				}).catch(res => {
					uni.showToast({
						title: res.message,
						icon: 'none'
					})
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.container {
		.card {
			margin: 16rpx 32rpx 0;
			box-shadow: 0rpx 5rpx 20rpx 1rpx rgba(209, 209, 209, 0.3);
		}

		.wish_del {
			margin: 48rpx 32rpx 0;

			.wish_del_bg {
				width: 100%;
				height: 88rpx;
				background-color: rgba(251, 91, 50, 0.08);
				border-radius: 20rpx;
			}
		}
	}
</style>