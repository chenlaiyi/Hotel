<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="兑换记录" color="#000000" :border="false"  :auto-back="true"></u-navbar>
		<!-- #endif -->
		<block v-if="exchangeReList.length > 0">
			<view class="card fui-flex" v-for="(item,index) in exchangeReList" :key="index">
				<view class="margin-right-sm" v-for="(item1,ins) in item.goods" :key="ins">
					<image :src="item1.thumb" mode="" style="width: 160rpx;height: 160rpx;border-radius: 10rpx;">
					</image>
				</view>
				<view class="record">
					<!-- 标题 -->
					<view class="fui-flex justify-between record-title">
						<view class="record-title_con">{{item.order_body}}</view>
						<view class="record-title_status">{{item.status_label}}</view>
					</view>
					<!-- 兑换时间 -->
					<view class="record-time">
						兑换时间：<text>{{item.create_time}}</text>
					</view>
					<!-- 送达时间 -->
					<view class="record-time">
						送达时间：<text>预计{{item.delivery_time}}送达</text>
					</view>
				</view>
			</view>
		</block>
		<view class="fui-flex flex-direction" v-else>
			<view class="" style="margin-top: 280rpx;">
				<image src="https://oss.ddicms.cn/member/order/null-data.png" mode="" style="width: 500rpx;height: 280rpx;">
				</image>
			</view>
			<view class="font_size_15 text-bold margin-tb-sm">
				暂无数据
			</view>
			<view class="font_size_12 color13">
				暂无订单数据，快去逛逛吧
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				query: {
					page: 1,
					pageSize: 10
				},
				exchangeReList: [],
			};
		},
		onLoad() {
			let that = this
			that.recordList()
		},
		methods: {
			recordList() {
				let that = this
					that.$api.integralOrderList(that.query.page,that.query.pageSize) .then(res => {
						that.exchangeReList = res.data.list
					}).catch(res => {
						uni.showToast({
							title: res.message,
							icon: 'none'
						})
					})
			}
		}
	};
</script>

<style>
	.container {
		margin: 0 32rpx 0;
	}

	.card {
		margin: 0 0 20rpx;
		box-shadow: 0rpx 3rpx 20rpx 1rpx rgba(209, 209, 209, 0.3);
	}

	.record {
		flex: 1;
	}

	.record-title {
		margin-bottom: 34rpx;
	}

	.record-title_con {
		font-size: 28rpx;
		color: #000;
	}

	.record-title_status {
		font-size: 24rpx;
		color: #00AFC7;
	}

	.record-time {
		color: #999;
		font-size: 20rpx;
		margin-bottom: 5rpx;
	}
</style>