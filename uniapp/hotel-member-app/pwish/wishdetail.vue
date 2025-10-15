<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="心愿单详情" color="#000000" :border="false"  :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="wish_detail">
			<view class="wish_detail_list" v-for="item in wishList" :key="item.id" @click="goIndex(item)">
				<view class="wish_detail_img">
					<image :src="item.thumb" mode="" style="width: 100%;height: 100%;border-radius: 10rpx;"></image>
					<view class="wish_detail_img_like">
						<iconfont className="icon-full-love" :size="22" color="#FB5B32"></iconfont>
					</view>
				</view>
				<view class="wish_detail_text" style="padding-left: 10rpx;box-sizing: border-box;">
					{{item.name}}
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				headTitle: '',
				query: {
					page: 1,
					pageSize: 10
				},
				wishList: [],
				total: undefined
			};
		},
		onLoad(options) {
			let that = this
			that.headTitle = options.title
			that.wishDetail(false)
		},
		onReachBottom() {
			let that = this
			if ((that.query.page * that.query.pageSize) <= total) {
				that.query.page++
				that.wishList(true)
			} else {
				uni.showToast({
					title: "加载完毕",
					icon: 'none'
				})
			}
		},
		methods: {
			wishDetail(flag) {
				let that = this
					that.$api.hotelWishMywish(that.query.page,that.query.pageSize).then(res => {
						if (flag) {
							that.wishList = [...that.wishList, res.data.list]
						} else {
							that.wishList = res.data.list
						}
						that.total = res.data.count
					}).catch(err => {
						uni.showToast({
							title: err.message,
							icon: 'none'
						})
					})
			},
			goIndex(item) {
				let that = this
				if (item.template_type === 1) {
					that.$Router.push({
						name: 'hotelquery',
					})
				} else if (item.template_type === 2) {
					that.$Router.push({
						name: 'apartment',
					})
				}
			}
		}
	}
</script>

<style lang="scss" scoped>
	.container {
		margin: 16rpx 32rpx 0;

		.wish_detail {
			display: flex;
			flex-wrap: wrap;

			.wish_detail_list {
				.wish_detail_img {
					position: relative;
					width: 336rpx;
					height: 336rpx;
					border-radius: 10rpx;

					.wish_detail_img_like {
						position: absolute;
						right: 12rpx;
						top: 12rpx;
					}
				}

				.wish_detail_text {
					width: 336rpx;
					overflow: hidden;
					white-space: nowrap;
					text-overflow: ellipsis;
					font-size: 28rpx;
					margin: 16rpx 0 32rpx;
				}
			}

			.wish_detail_list:nth-child(2n-1) {
				margin-right: 14rpx;
			}

		}

	}
</style>