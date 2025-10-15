<template>
	<u-popup :show.sync="show" mode="bottom" @click="colse()">
		<view class="t-pop" @tap.stop>
			<view class="pop-main">
				<view class="top">
					<view class="top-l">
						<view @click="changeSwp(1)" class="date-c" :style="{color:sindex==1?'#1E79FF':'#333333'}">
							<text>{{checkyear}}-{{checkmonth>10?checkmonth:'0'+checkmonth}}-{{checkdate>10?checkdate:'0'+checkdate}}</text>
						</view>
					<!-- 	<view @click="changeSwp(2)" class="time-c" :style="{color:sindex==2?'#1E79FF':'#333333'}">
							<text>{{checkhour>10?checkhour:'0'+checkhour}}:{{checkminute>10?checkminute:'0'+checkminute}}</text>
						</view> -->
					</view>
				<!-- 	<view class="top-r" @click="onOK()">
						<text>确定</text>
					</view> -->
				</view>
				<swiper class="swiper" circular :current-item-id="sindex">
					<swiper-item>
						<view class="mid">
							<view class="calendar">
								<view class="ca-top" v-for="item in textList" :key="item">
									{{item}}
								</view>
							</view>
							<scroll-view scroll-y="true" style="height: 460rpx;" @scrolltolower="tolower">
								<!-- <view style="padding-bottom: 150rpx;"> -->
								<view class="calendar" v-for="(item,index) in mList" :key="index">
									<view class="cabg">
										{{item.month}}
									</view>
									<view @click="check(item,i)" class="ca-top"
										:style="{marginLeft:i==0?item.firstDay*14.2+'vw':0}" v-for="i in item.daynums"
										:key="i">
										<view class="cell" style="color:#ccc"
											v-if="nowDate>(i+1)&&nowYear==item.year&&nowMonth==item.month&&!canToday">
											{{i+1}}
										</view>
										<view v-else
											:class="checkyear==item.year&&checkmonth==item.month&&checkdate==(i+1)?'cell cell-active':'cell'">
											{{i+1}}
										</view>

									</view>
								</view>
								<!-- </view> -->
							</scroll-view>
						</view>
					</swiper-item>
				<!-- 	<swiper-item :item-id="2">
						<picker-view :indicator-style="indicatorStyle" :value="value" @change="bindChange"
							class="picker-view">
							<picker-view-column>
								<view class="item" v-for="(item,index) in 23" :key="index">
									{{item+1>10?item+1:'0'+(item+1)}}时
								</view>
							</picker-view-column>
							<picker-view-column>
								<view class="item" v-for="(item,index) in 59" :key="index">
									{{item+1>10?item+1:'0'+(item+1)}}分
								</view>
							</picker-view-column>
						</picker-view>
					</swiper-item> -->
				</swiper>

			</view>
		</view>
	</u-popup>
</template>

<script>
	export default {
		name: "t-datetime",
		props: {
			show: {
				type: Boolean,
				default: false
			},
			delayMin: {
				type: Number,
				default: 0 //默认时间后推0分钟
			},
			canToday: { //是否可选择当天之前的时间
				type: Boolean,
				default: false
			}
		},
		data() {
			return {
				textList: ['日', '一', '二', '三', '四', '五', '六'],
				mList: [],
				checkyear: 0,
				checkmonth: 0,
				checkdate: 0,
				checkhour: 0,
				checkminute: 0,
				indicatorStyle: `height: 50px;`,
				value: [0, 0],
				sindex: 1,
				nowYear: 0,
				nowMonth: 0,
				nowDate: 0
			};
		},
		watch: {
			// 使用watch来响应数据的变化
			show(newVal, oldVal) {
				if (newVal) {
					const dt = Date.now()
					const dtafter = dt + 1000 * 60 * this.delayMin
					this.checkyear = new Date(dtafter).getFullYear()
					this.checkmonth = new Date(dtafter).getMonth() + 1
					this.checkdate = new Date(dtafter).getDate()
					this.checkhour = new Date(dtafter).getHours()
					this.checkminute = new Date(dtafter).getMinutes()
					this.value = [this.checkhour - 1, this.checkminute - 1]
					this.nowYear = new Date(dtafter).getFullYear()
					this.nowMonth = new Date(dtafter).getMonth() + 1
					this.nowDate = new Date(dtafter).getDate()
				}
			}
		},
		created() {
			this.init()
		},
		methods: {
			tolower() {
				this.init()
			},
			init() {
				let startyear = this.mList.length ? this.mList[this.mList.length - 1].year : new Date().getFullYear()
				let startmonth = this.mList.length ? this.mList[this.mList.length - 1].month + 1 : new Date().getMonth() +
					1
				for (let i = 0; i < 10; i++) {
					let year = startmonth + i > 12 ? startyear + 1 : startyear;
					let month = startmonth + i > 12 ? startmonth + i - 12 : startmonth + i;
					let daynums = this.getLastDay(year, month)
					let firstDay = this.getFirstDay(year, month)
					this.mList.push({
						year: year,
						month: month,
						daynums: daynums,
						firstDay: firstDay
					})
				}
			},
			check(item, i) {
				if (this.nowDate > (i + 1) && this.nowYear == item.year && this.nowMonth == item.month && !this.canToday)
					return
				this.checkyear = item.year
				this.checkmonth = item.month
				this.checkdate = i + 1
				this.sindex = 2
			},
			getFirstDay(year, month) {
				const date1 = new Date(month + '/1/' + year)
				return date1.getDay()
			},
			getLastDay(year, month) {
				const date1 = new Date(year, month, 0)
				return date1.getDate()
			},
			colse() {
				// this.$emit('update:show', false)
				this.show = false
			},
			bindChange(e) {
				const val = e.detail.value
				this.value = [val[0], val[1]]
				this.checkhour = val[0] + 1
				this.checkminute = val[1] + 1
				console.log(this.checkhour);
				console.log(this.checkminute);
			},
			changeSwp(i) {
				this.sindex = i
			},
			onOK() {
				let time = this.checkyear + '/' + this.checkmonth + '/' + this.checkdate + ' ' +
					this.checkhour + ':' + this.checkminute;
				let obj = {
					timeStr: time,
					value: Date.parse(time)
				}
				this.$emit('confirm', obj)
			}
		}
	}
</script>

<style lang="scss" scoped>
	.t-pop {
		width: 100%;
		display: flex;
		justify-content: center;
		align-items: center;

		.pop-main {
			display: flex;
			flex-direction: column;
			justify-content: space-between;
			align-items: center;
			background-color: #fff;
			border-radius: 24px;
			height: 600rpx;
			width: 100%;

		}
	}

	.swiper {
		height: 600rpx;
		width: 50vw;
	}

	.top {
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		align-items: center;
		width: 100%;
		margin: 20rpx 0;

		.top-l {
			display: flex;
			flex-direction: row;
			margin-left: 30rpx;
		}

		.top-r {
			margin-right: 30rpx;
			color: #1E79FF;
		}

		.date-c {
			width: 210rpx;
		}

		.time-c {
			// margin-left: 20rpx;
		}

	}

	.calendar {
		display: flex;
		flex-wrap: wrap;
		flex-direction: row;
		align-items: center;
		width: 100vw;
		position: relative;

	}

	.ca-top {
		width:50px;
		display: flex;
		justify-content: center;
		align-items: center;
		height: 66rpx;
		z-index: 10;
	}

	.cell {
		width: 60rpx;
		height: 60rpx;
		display: flex;
		justify-content: center;
		align-items: center;
		align-content: center;
		border-radius: 30rpx;
	}

	.cell-active {
		background-color: #1E79FF;
		color: #fff;
	}

	.cabg {
		display: flex;
		justify-content: center;
		width: 100vw;
		font-size: 180rpx;
		color: beige;
		position: absolute;
		z-index: 9;
	}

	.picker-view {
		width: 750rpx;
		height: 600rpx;
		margin-top: 20rpx;
	}

	.item {
		height: 50px;
		display: flex;
		align-items: center;
		justify-content: center;
		text-align: center;
	}
</style>
