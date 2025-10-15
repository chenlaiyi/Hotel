<template>
	<view class="containers">
		<view class="fui-grid-icon">
			<view class="fui-goods-title margin-left-xs fui-color-white">{{ title }}</view>
			<view class="goods-item bg-white">
				<view v-for="(items, index) in list" :key="index" class="fui-flex flex-wrap">
					<view v-for="(item, key) in items" :key="key" class="fui-col-4 goods-type">
						<view class="fui-pro-img" :data-goods_id="item.goods_id" :data-goods_type="item.goods.goods_type" @tap="detail($event)">
							<image class="fui-pro-img" mode="aspectFill" :src="item.goods.thumb"></image>
							<view class="fui-pro-content">
								<view class="fui-pro-tit">{{ item.goods.goods_name }}</view>
								<view class="fui-pro-price margin-top-sm">￥{{ item.goods.goods_price }}</view>
							</view>
						</view>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
import easyLoadimage from '@/components/easy-loadimage/easy-loadimage.vue';
export default {
	components: {
		easyLoadimage
	},
	name: 'goods-three',
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
	margin: 22rpx 0;
	height: 100%;
}
.fui-grid-icon {
	padding: 24rpx 0;
	border-radius: 20rpx;
	background: linear-gradient(227deg, #00e4b8 0%, #00c093 100%);
	width: 100%;
	height: 100%;
}
.goods-item{
	border-radius: 22rpx;
	margin: 18rpx 29rpx 0;
	padding: 22rpx 0;
}
.fui-goods-title {
	font-size: 36rpx;
	font-weight: bold;
}
.goods-type {
	border-radius: 16rpx;
	background-color: #F5F5F5;
	height:283rpx;
	width: 190rpx;
	margin: auto;
}
.fui-pro-content {
	margin-top: 12rpx;
}
.fui-pro-tit {
	font-size: 22rpx;
	word-break: break-all;
	overflow: hidden;
	text-overflow: ellipsis;
	display: -webkit-box;
	-webkit-box-orient: vertical;
	-webkit-line-clamp: 2;
}
.fui-pro-price {
	color: #ff0000;
	font-size: 24rpx;
}
.fui-pro-img {
	width: 141rpx;
	height: 141rpx;
	margin: auto;
}
</style>
