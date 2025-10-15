<template>
	<view class="containers">
		<view class="fui-flex flex-wrap">
			<fui-grid v-for="(items, index) in list" :key="index" class="fui-col-6">
				<view class="fui-grid-icon">
					<view class="fui-goods-title">{{ title }}</view>
					<view v-for="(item, key) in items" :key="key">
						<fui-grid-item :cell="2">
							<view class=" fui-center">
								<view>
									<image
										:data-goods_id="item.goods.goods_id"
										:data-goods_type="item.goods.goods_type"
										:src="item.goods.thumb"
										@tap="detail($event)"
										class="fui-pro-img  goods-img"
										mode=" aspectFit"
									></image>
								</view>
							</view>
						</fui-grid-item>
					</view>
				</view>
			</fui-grid>
		</view>
	</view>
</template>

<script>
export default {
	name: 'goods-four',
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
		console.log('thattt', that.list);
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
.fui-pro-img {
	width: 122rpx;
	height: 120rpx;
}
.fui-grid-icon {
	width: 346rpx;
	height: 220rpx;
	border-radius: 16rpx;
	background-color: #fff;
	margin: 20rpx 0;
}
.fui-goods-title {
	color: #003333;
	font-size: 36rpx;
	padding: 16rpx 34rpx;
	background: linear-gradient(180deg, #e1e1f3 0%, #ffffff 100%);
	font-weight: bold;
}
.top-adver{
	height: 172rpx;
	border-radius: 16rpx;
	margin-top:24rpx;
	width: 100%;
}
</style>
