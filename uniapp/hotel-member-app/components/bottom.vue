<template>
	<view class="bottom_tab">
		<view class="bottom_itm fui-flex">
			<view class="itm_list">
				<view class="text-bold text-black">LOGO</view>
				<view>工作机会</view>
				<view>爱彼迎新闻</view>
				<view>政策</view>
				<view>无障碍设施</view>
			</view>
			<view class="itm_list">
				<view class="text-bold text-black">发现</view>
				<view>信任与安全</view>
				<view>邀请好友</view>
				<view>商务差旅</view>
				<view>logo杂志</view>
				<view>logo.org</view>
			</view>
			<view class="itm_list">
				<view class="text-bold text-black">出租</view>
				<view>为什么要出租?</view>
				<view>房东义务</view>
				<view>开展体验</view>
				<view>资源中心</view>
			</view>
			<view class="itm_list">
				<view class="text-bold text-black">客服支持</view>
				<view>帮助?</view>
				<view>邻里支持</view>
			</view>
		</view>
		<view class="text-center padding-tb-sm">
			<view class="">京ICP备16017121号 京ICP证 160773号 中国公安局标志 京公网安备 11010502032345号 安彼迎网络（北京）有限公司 中国电子营业执照标志 营业执照</view>
			<view class="">© 2022 Airbnb, Inc. All rights reserved.</view>
			<view class="">条款 · 隐私政策 · 网站地图 · 全国旅游投诉渠道 12301</view>
		</view>
	</view>
</template>

<script>
export default {
	name: 'top',
	data() {
		return {};
	}
};
</script>

<style>
.bottom_tab {
	width: 100%;
	height: 244px;
	background: #f6f6f6;
	color: #9C9C9C;
	font-size: 10px;
}
.bottom_itm {
	padding: 33px 110px;
	width: 100%;
	border-bottom: 1px solid #b4b4b4;
}
.itm_list {
	width: 20%;
	margin: 0 auto;
	color: #707070;
	line-height: 20px;
}
</style>
