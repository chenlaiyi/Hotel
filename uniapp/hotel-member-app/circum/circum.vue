<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="周边景点" color="#000000" :border="false" :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="circum_wrap fui-flex">
			<view class="circum_wrap_list" v-for="(item,index) in rimList" :key="index" @click="goCircumDetail">
				<view class="circum_wrap_list_img">
					<image :src="item.thumb" mode=""
						style="width: 100%;height: 100%;border-radius: 10rpx;"></image>
					<view class="circum_wrap_list_txt">
						3.5km
					</view>
				</view>
				<view class="font_size_16 text-bold margin-tb-sm">
					{{item.title}}
				</view>
				<view class="fui-flex font_size_12">
					<view class="color5">
						{{item.comment_num}}分
					</view>
					<view class="circum_wrap_list_line"></view>
					<view class="color13">
						{{item.room_num}}套房源
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				pages: {
					page: 1,
					pageSize: 20
				},
				rim_type: 0,
				rimList:[]
			};
		},
		onLoad() {
			const that = this
			that.getList();
		},
		methods: {
			goCircumDetail() {
				let that = this
				that.$Router.push({
					name: 'circumdetail'
				})
			},
			getList() {
				const that = this
				that.$api.hotelRimList(that.pages.page,that.pages.pageSize,0).then(res => {
					console.log(res)
					that.rimList = res.data
				}).catch(err => {
					that.fui.toast(err.message)
				})
			}
		}
	}
</script>

<style lang="scss">
	.container {
		margin-top: 16rpx;

		.circum_wrap {
			margin: 0 32rpx;
			flex-wrap: wrap;
			justify-content: space-between;

			.circum_wrap_list {
				margin: 0 30rpx 32rpx 0;

				.circum_wrap_list_img {
					position: relative;
					width: 328rpx;
					height: 400rpx;

					.circum_wrap_list_txt {
						position: absolute;
						left: 0;
						bottom: 2rpx;
						padding: 4rpx 8rpx;
						background-color: rgba(0, 0, 0, .8);
						border-radius: 0rpx 10rpx 0rpx 10rpx;
						color: #FFFFFF;
						font-size: 20rpx;
					}
				}

				.circum_wrap_list_line {
					width: 2rpx;
					height: 20rpx;
					background-color: #CCCCCC;
					margin: 0 12rpx;
				}
			}

			.circum_wrap_list:nth-child(2n) {
				margin-right: 0;
			}
		}
	}
</style>