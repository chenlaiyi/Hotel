<template>
	<view class="m-per-house">
		<u-navbar title="景点关联房源" @rightClick="rightClick" :autoBack="true" :placeholder="true"></u-navbar>
		<view class="posk zindex10 bgfff mb16" :style="{'top': navtop}">
			 <u-tabs
				:list="navlist"
				lineWidth="32rpx"
				lineHeight="4rpx"
				lineColor="#00AFC7"
				:activeStyle="{
					color: '#000',
					fontWeight: 'bold',
					fontSize: '28rpx'
				}"
				:inactiveStyle="{
					color: '#999',
					fontSize: '28rpx'
				}"
				itemStyle="height: 74rpx;"
				keyName="name"
				:scrollable="false"
				@click="navclick">
			</u-tabs>
		</view>
		<template v-if="curnav == 1">
			<view class="pt16 pb16 pl32 pr32">
				<view class="h360 pore">
					<view class="h360 bdra10 overhide dsf alc jcc">
						<image class="w100b" src="https://oss.ddicms.cn/member/new/bg3.png" mode="widthFix"></image>
					</view>
					<view class="poab t0 r0 pt30 pr30">
						<view class="dsf">
							<u-icon name="heart" color="#fff" size="22"></u-icon>
						</view>
					</view>
					<view class="poab b0 l0 pb16 pl16">
						<view class="dsf alc pl8 pr12 h44 bdra5 bgfff-op8">
							<u-icon name="star-fill" color="#FF9500" size="16"></u-icon>
							<view class="col000 fwb dsf alb pl4">
								<text class="text-24">5.0</text>
								<text class="text-20">分</text>
							</view>
						</view>
					</view>
					<view class="poab r0 pr32 bf48">
						<view class="w96 h96 bdra50b overhide dsf alc jcc bdfff-w4">
							<image class="w100b h100b" src="https://oss.ddicms.cn/member/new/bg4.png" mode=""></image>
						</view>
					</view>
				</view>
				<view class="pr160 pt24">
					<view class="text-32 col000 fwb onehide lh45">王府井 新中式雅致胡同风格美屋</view>
				</view>
				<view class="dsf alc pt8">
					<view class="text-22 fwb col00AFC7">整租</view>
					<view class="w1 h20 bgd1 ml10 mr10"></view>
					<view class="text-22 col666">9居室</view>
					<view class="w1 h20 bgd1 ml10 mr10"></view>
					<view class="text-22 col666">16床</view>
					<view class="w1 h20 bgd1 ml10 mr10"></view>
					<view class="text-22 col666">30人</view>
				</view>
				<view class="dsf flex-wrap pt8">
					<view class="pl12 pr12 h32 bgFFEEEA text-18 colFB5B32 dsf alc mr10 mb10">超赞房东</view>
					<view class="pl12 pr12 h32 bgf9 text-18 col999 dsf alc mr10 mb10">长租优惠</view>
					<view class="pl12 pr12 h32 bgf9 text-18 col999 dsf alc mr10 mb10">很干净</view>
					<view class="pl12 pr12 h32 bgf9 text-18 col999 dsf alc mr10 mb10">可带宠物</view>
				</view>
				<view class="dsf alb h50">
					<view class="colFB5B32 fwb">
						<text class="text-24">￥</text>
						<text class="text-40">200</text>
					</view>
					<view class="text-24 col000">/晚</view>
				</view>
			</view>
			<view class="pt16 pb16 pl32 pr32">
				<view class="h360 pore">
					<view class="h360 bdra10 overhide dsf alc jcc">
						<image class="w100b" src="https://oss.ddicms.cn/member/new/bg3.png" mode="widthFix"></image>
					</view>
					<view class="poab t0 r0 pt30 pr30">
						<view class="dsf">
							<u-icon name="heart" color="#fff" size="22"></u-icon>
						</view>
					</view>
					<view class="poab b0 l0 pb16 pl16">
						<view class="dsf alc pl8 pr12 h44 bdra5 bgfff-op8">
							<u-icon name="star-fill" color="#FF9500" size="16"></u-icon>
							<view class="col000 fwb dsf alb pl4">
								<text class="text-24">5.0</text>
								<text class="text-20">分</text>
							</view>
						</view>
					</view>
					<view class="poab r0 pr32 bf48">
						<view class="w96 h96 bdra50b overhide dsf alc jcc bdfff-w4">
							<image class="w100b h100b" src="https://oss.ddicms.cn/member/new/bg4.png" mode=""></image>
						</view>
					</view>
				</view>
				<view class="pr160 pt24">
					<view class="text-32 col000 fwb onehide lh45">王府井 新中式雅致胡同风格美屋</view>
				</view>
				<view class="dsf alc pt8">
					<view class="text-22 fwb colFF9500">分租</view>
					<view class="w1 h20 bgd1 ml10 mr10"></view>
					<view class="text-22 col666">9居室</view>
					<view class="w1 h20 bgd1 ml10 mr10"></view>
					<view class="text-22 col666">16床</view>
					<view class="w1 h20 bgd1 ml10 mr10"></view>
					<view class="text-22 col666">30人</view>
				</view>
				<view class="dsf flex-wrap pt8">
					<view class="pl12 pr12 h32 bgFFEEEA text-18 colFB5B32 dsf alc mr10 mb10">超赞房东</view>
					<view class="pl12 pr12 h32 bgf9 text-18 col999 dsf alc mr10 mb10">长租优惠</view>
					<view class="pl12 pr12 h32 bgf9 text-18 col999 dsf alc mr10 mb10">很干净</view>
					<view class="pl12 pr12 h32 bgf9 text-18 col999 dsf alc mr10 mb10">可带宠物</view>
				</view>
				<view class="dsf alb h50">
					<view class="colFB5B32 fwb">
						<text class="text-24">￥</text>
						<text class="text-40">200</text>
					</view>
					<view class="text-24 col000">/晚</view>
				</view>
			</view>
		</template>
		<template v-if="curnav == 0">
			<view v-for="i of 5" class="pt16 pb16 pl32 pr32 dsf">
				<view class="w200 h280 bdra10 overhide dsf alc jcc pore">
					<image class="w100b" src="https://oss.ddicms.cn/member/new/bg2.png" mode="widthFix"></image>
					<view class="poab t18 r16">
						<u-icon name="heart" color="#fff" size="22"></u-icon>
					</view>
				</view>
				<view class="fg1 h280 pt6 pb10 bsbox pl24 u-flex-column jcsb">
					<view>
						<view class="onehide text-32 col000 fwb lh45">轻住·卡尔顿精品酒店(高新国新国</view>
						<view class="dsf alc pt16">
							<view class="bgFF9500 w48 h30 dsf alc jcc bdra5 text-24 colfff">5.0</view>
							<view class="text-20 col999 pl10">123条评论</view>
						</view>
						<view class="dsf alc pt16">
							<view class="dsf alc text-22 col666 pr10">
								<u-icon name="map" color="#000" size="14"></u-icon>
								<view>距您2.3km</view>
							</view>
							<view class="w1 h20 bgd1 pore t1"></view>
							<view class="text-22 col666 pl10">西安高新区高新路8号</view>
						</view>
						<view class="dsf flex-wrap pt16">
							<view class="pl12 pr12 h32 bgf9 text-18 col999 dsf alc mr10 mb10">夜景好看</view>
							<view class="pl12 pr12 h32 bgf9 text-18 col999 dsf alc mr10 mb10">长租优惠</view>
							<view class="pl12 pr12 h32 bgf9 text-18 col999 dsf alc mr10 mb10">很干净</view>
							<view class="pl12 pr12 h32 bgf9 text-18 col999 dsf alc mr10 mb10">可带宠物</view>
						</view>
					</view>
					<view class="dsf jcsb alc">
						<view class="h32 bdra5 pl10 pr10 dsf alc text-20 colFB5B32 bdFB5B32">可用券</view>
						<view class="dsf alb">
							<view class="text-24 col999 text-line-through pr10">¥228</view>
							<view class="colFB5B32 fwb">
								<text class="text-24">￥</text>
								<text class="text-40">200</text>
							</view>
							<view class="text-24 col000">起</view>
						</view>
					</view>
				</view>
			</view>
		</template>
	</view>
</template>

<script>
	export default{
		data(){
			return {
				navtop: 0,
				navlist: [
					{ id: 1, name: '酒店' },
					{ id: 2, name: '公寓' },
					{ id: 3, name: '民宿' },
				],
				curnav: 0,
			}
		},
		onLoad() {
			this.navtop = uni.$u.addUnit(uni.$u.getPx(uni.$u.props.navbar.height) + uni.$u.sys().statusBarHeight, 'px')
		},
		methods:{
			navclick(e){
				this.curnav = e.index
			}
		}
	}
</script>

<style lang="scss" scoped>
	.m-per-house{
		min-height: 100vh;
		background-color: #fff;
	}
</style>