<template>
	<view class="containers">
		<view v-for="(items, index) in list" :key="index" class="product-detail-list">
			<view v-for="(item, key) in items" :key="key" class="fui-product-container fui-col-6 margin-tb-xs">
				<view class="fui-pro-item" hover-class="hover" :data-goods_id="item.goods_id" :data-goods_type="item.goods.goods_type" @tap="detail($event)">
					<image class="fui-pro-item" :src="item.goods.thumb" mode="aspectFill"></image>
					<view class="margin-xs">
						<view class="fui-pro-tit margin-tb-xs">{{ item.goods.goods_name }}</view>
						<view>
							<view class="fui-pro-pay margin-tb-xs">{{ item.goods.sales_initial }}人付款</view>
							<view class="fui-pro-price">￥{{ item.goods.goods_price }}</view>
						</view>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
export default {
	name: 'goods-two',
	data() {
		return {
			skeletonShow: true
		};
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
.product-detail-list {
	display: flex;
	flex-wrap: wrap;
	width: 740rpx;
	margin: 20rpx auto;
}
.fui-product-container {
	background: #fff !important;
	border-radius: 30rpx;
	width: 347rpx;
	height: 436rpx;
	margin-right: 20rpx;
}
.fui-pro-item {
	width: 347rpx;
	height: 286rpx;
	border-top-left-radius: 30rpx;
	border-top-right-radius: 30rpx;
}
.fui-pro-tit {
	font-size: 24rpx;
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
	font-weight: bold;
}
.fui-pro-pay {
	color: #999999;
	font-size: 14rpx;
}
.top-adver {
	height: 172rpx;
	border-radius: 16rpx;
	margin-top: 24rpx;
	width: 100%;
}
</style>
