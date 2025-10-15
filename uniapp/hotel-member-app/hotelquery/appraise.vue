<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="评价" fixed placeholder color="#000000" :border="false"  fixed placeholder :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="appraise_top fui-flex">
			<view class="margin-right-xl">
				<view class="font_size_14 text-bold">
					<text style="font-size: 120rpx;">{{(star).toFixed(1)}}</text>很赞
				</view>
				<view class="font_size_12 color13">
					共{{appraiseList.length}}条用户评价
				</view>
			</view>
			<view class="margin-left-sm" style="flex: 1;">
				<view class="fui-flex" v-for="(item,index) in progressList" :key="index">
					<view class="font_size_10 margin-right-xs fui-col-4">{{item.labels}}</view>
					<view class="appraise_top_progress">
						<ai-progress :percentage="item.num" :strokeWidth="5" bgColor="#FF9500" :noData="true"
							inBgColor="#EEEEEE"></ai-progress>
					</view>
					<view class="font_size_10">
						{{item.num}}
					</view>
				</view>
			</view>
		</view>
		<view class="appraise_content">
			<view class="fui-flex justify-between margin-bottom-sm">
				<view class="font_size_14 text-bold">
					{{appraiseList.length}}条评价
				</view>
				<view class="fui-flex">
					<view class="font_size_12 color4">
						综合排序
					</view>
					<view class="line"></view>
					<view class="font_size_12">
						最新评价
					</view>
				</view>
			</view>
			<view class="" v-for="(item,index) in appraiseList" :key="index">
				<view class="fui-flex justify-between margin-tb-sm">
					<view class="fui-flex">
						<view class="margin-right-sm">
							<image :src="item.member.avatar" mode=""
								style="width: 80rpx;height: 80rpx;border-radius: 50%;"></image>
						</view>
						<view class="">
							<view class="font_size_14 text-bold">
								{{item.member.realname}}
							</view>
							<view class="font_size_10 color13 margin-top-xs">
								{{item.create_time}}
							</view>
						</view>
					</view>
					<view class="">
						<fui-rate :quantity="5" :current="item.star_num" normal="#D1D1D1" active="#FF9500"
							:size="16"></fui-rate>
					</view>
				</view>
				<view class="appraise_content_txt font_size_12">
					{{item.comment}}
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				progressList: [],
				query: {
					page: 1,
					pageSize: 10
				},
				appraiseList: [],
				star: '', //星级
			};
		},
		onLoad() {
			let that = this
			that.getAppraiseList(that.$Route.query.hotel_id, that.$Route.query.room_id)
		},
		methods: {
			getAppraiseList(hotel_id, room_id) {
				let that = this
				that.$api.hotelCommentList(hotel_id, room_id, that.query.page, that.query.pageSize).then(res => {
					that.progressList = res.data.labels
					that.appraiseList = res.data.list
					that.star = res.data.star_num
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
		.appraise_top {
			margin: 0 32rpx;
			padding-bottom: 32rpx;

			.appraise_top_progress {
				width: 280rpx;
			}
		}

		.appraise_content {
			margin: 0 32rpx;
			padding-top: 32rpx;
			border-top: 2rpx solid #F5F5F5;

			.line {
				width: 1px;
				height: 20rpx;
				background-color: #D1D1D1;
				margin: 0 12rpx;
			}

			.appraise_content_txt {
				padding-bottom: 32rpx;
				border-bottom: 2rpx solid #F5F5F5;
				line-height: 36rpx;
			}
		}
	}
</style>