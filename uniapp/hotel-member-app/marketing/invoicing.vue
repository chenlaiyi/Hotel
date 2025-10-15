<template>
	<view class="container">
		<!-- 开发票 -->
		<view class="card padding-0 invo-list">
			<view class="fui-flex invo-list_input">
				<view class="fui-col-4 invo-list_text">
					发票抬头
				</view>
				<view class="fui-col-8">
					<input class="uni-input" type="text" placeholder="填写发票抬头" placeholder-class="placeholder" />
				</view>
			</view>
			<view class="fui-flex invo-list_input">
				<view class="fui-col-4 invo-list_text">
					税号
				</view>
				<view class="fui-col-8">
					<input class="uni-input" type="number" placeholder="填写税号" placeholder-class="placeholder" />
				</view>
			</view>
			<view class="fui-flex invo-list_input">
				<view class="fui-col-4 invo-list_text">
					单位地址
				</view>
				<view class="fui-col-8">
					<input class="uni-input" type="text" placeholder="填写单位地址" placeholder-class="placeholder" />
				</view>
			</view>
			<view class="fui-flex invo-list_input">
				<view class="fui-col-4 invo-list_text">
					公司电话
				</view>
				<view class="fui-col-8">
					<input class="uni-input" type="tel" placeholder="填写公司电话" placeholder-class="placeholder" />
				</view>
			</view>
			<view class="fui-flex invo-list_input">
				<view class="fui-col-4 invo-list_text">
					公司开户行
				</view>
				<view class="fui-col-8">
					<input class="uni-input" type="text" placeholder="填写公司开户行" placeholder-class="placeholder" />
				</view>
			</view>
			<view class="fui-flex invo-list_input">
				<view class="fui-col-4 invo-list_text">
					开户行账号
				</view>
				<view class="fui-col-8">
					<input class="uni-input" type="number" placeholder="填写开户行账号" placeholder-class="placeholder" />
				</view>
			</view>
		</view>
		<view class="card padding-0 invo-list">
			<view class="fui-flex invo-list_input">
				<view class="fui-col-4 invo-list_text">
					接收邮箱
				</view>
				<view class="fui-col-8">
					<input class="uni-input" type="email" placeholder="请输入接收邮箱" placeholder-class="placeholder" />
				</view>
			</view>
		</view>
		
		<view class="margin-list">
			<view class="margin-list_label fui-flex justify-center">
				<label class="radio">
					<radio color="#00AFC7" value="r1" style="transform:scale(0.5)" />
				</label>
				<view class="radio_text">
					阅读并同意<text style="color: #00AFC7;">《用户授权协议》</text>及<text style="color: #00AFC7;">《隐私政策》</text>
				</view>
			</view>
		</view>
		
		<view class="margin-btn">
			<button class="search_btn text-white background3 fui-center">确定</button>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {

			}
		},
		methods: {

		}
	}
</script>

<style>
	.container {
		margin: 0;
	}

	.invo-list {
		padding: 0rpx 32rpx;
		margin-bottom: 0;
	}

	.invo-list_input {
		padding: 24rpx 0;
		border-bottom: 2rpx solid #F5F5F5;
	}

	.invo-list_text {
		font-size: 28rpx;
		color: #000;
	}

	.uni-input {
		font-size: 28rpx;
	}

	.placeholder {
		font-size: 28rpx;
		color: #D1D1D1;
		font-weight: 600;
	}
	.margin-btn {
		margin: 0 32rpx;
	}
	
	.margin-list {
		margin:32rpx 0;
	}
	
	.radio_text {
		font-size: 20rpx;
	}

	.search_btn {
		width: 100%;
		height: 72rpx;
		font-size: 28rpx;
		border-radius: 10rpx;
	}
</style>
