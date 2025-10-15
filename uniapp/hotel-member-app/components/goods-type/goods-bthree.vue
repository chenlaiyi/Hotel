<template>
	<view class="containers">
		<view class="product-bg">
			<view class="fui-goods-title margin-lr-sm">{{ title }}</view>
			<view class="fui-flex margin-top-sm">
				<view v-for="(item, key) in list" :key="key" class="fui-col-4 fui-center">
					<view class="fui-pro-img" :data-goods_id="item.goods_id" :data-goods_type="item.goods.goods_type" @tap="detail($event)">
						<image class="fui-pro-img" mode="aspectFill" :src="item.goods.thumb"></image>
						<!-- <view class="fui-pro-content">
							<view class="fui-pro-tit">{{ item.goods.goods_name }}</view>
							<view class="fui-pro-price margin-top-xs">￥{{ item.goods.goods_price }}</view>
						</view> -->
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
export default {
	name: 'goods-bthree',
	data() {
		return {};
	},
	props: {
		title: {
			type: String,
			default: ''
		},
		list: {
			type: Array,
			default: function() {
				return [];
			}
		}
	},
	created() {
		let that = this;
		console.log('that.list', that.list);
	},
	mounted() {},
	methods: {
		detail: function(e) {
			let that = this;
			console.log(e);
			let goods_id = e.currentTarget.dataset.goods_id;
			let goods_type = e.currentTarget.dataset.goods_type;
			that.$Router.push({
				name: 'productDetail',
				params: {
					goods_id: goods_id,
					goods_type: goods_type
				}
			});
		}
	}
};
</script>

<style scoped>
.containers {
	margin: 40rpx 0;
}
.product-bg {
	width: 100%;
	height: 100%;
	padding-top: 22rpx;
	background: linear-gradient(180deg, #a6f9e9 0%, #ffffff 100%);
	border-radius: 18rpx;
}
.fui-grid-icon {
	border-radius: 16rpx;
	margin: 0 29rpx;
}
.fui-goods-title {
	color: #fff;
	font-size: 36rpx;
	font-weight: bold;
}
.fui-pro-content {
	margin-top: 32rpx;
}
.fui-pro-tit {
	font-size: 22rpx;
	color: #003333;
	word-break: break-all;
	overflow: hidden;
	text-overflow: ellipsis;
	display: -webkit-box;
	-webkit-box-orient: vertical;
	-webkit-line-clamp: 2;
}
.fui-pro-price {
	color: #ff0000;
	font-size: 22rpx;
}
.product-list {
	width: 656rpx;
	display: flex;
	justify-content: center;
	align-items: center;
	padding: 44rpx 26rpx;
	flex-wrap: wrap;
}
.fui-pro-img {
	width: 186rpx;
	height: 246rpx;
	border-radius: 16rpx;
	padding-bottom: 19rpx;
}
</style>
