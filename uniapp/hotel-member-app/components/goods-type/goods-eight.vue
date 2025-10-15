<template>
	<view class="containers">
		<scroll-view scroll-x class="nav" scroll-with-animation scroll-left="0">
			<view
				v-for="(item, index) in list"
				:key="index"
				class="product-list"
				hover-class="hover"
				:data-goods_id="item.goods_id"
				:data-goods_type="item.goods.goods_type"
				@tap="detail($event)"
			>
				<view class="goods-itm">
					<image class="fui-pro-item" :src="item.goods.thumb" mode="aspectFill"></image>
					<view class="margin-xs">
						<view class="fui-pro-tit margin-tb-xs">{{ item.goods.goods_name }}</view>
						<view class="fui-flex">
							<view class="fui-pro-price">￥{{ item.goods.goods_price }}</view>
							<view class="fui-pro-pay margin-xs">￥{{ item.goods.line_price }}</view>
						</view>
					</view>
				</view>
			</view>
		</scroll-view>
	</view>
</template>

<script>
export default {
	name: 'goods-eight',
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
.product-list {
	height: 206rpx;
	display: inline-block;
	width: 25%;
}
.goods-itm{
	width: 180rpx;
}
.fui-pro-item {
	width: 204rpx;
	height: 204rpx;
	border-radius: 9rpx;
}
.fui-pro-tit {
	width: 180rpx;
	font-size: 20rpx;
	word-break: break-all;
	overflow: hidden;
	text-overflow: ellipsis;
	display: -webkit-box;
	-webkit-box-orient: vertical;
	-webkit-line-clamp: 1;
}
.fui-pro-price {
	color: #ff0000;
	font-size: 24rpx;
}
.fui-pro-pay {
	color: #999999;
	font-size: 18rpx;
	text-decoration: line-through;
}
</style>
