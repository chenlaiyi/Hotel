/**
 * 商家端接口
 */

import sendHttp from "@/uni_modules/ddiot-ui/js_sdk/http/index.js"

export function hotelCommentList(hotel_id, room_id, page, pageSize) {
	return sendHttp('/diandi_hotel/comment/list', "POST", {
		hotel_id,
		room_id,
		page,
		pageSize
	}, true)
}
export function hotelCouponMycoupon(page, pageSize, status) {
	return sendHttp('/diandi_hotel/coupon/mycoupon', "GET", {
		page,
		pageSize,
		status
	}, true)
}
export function hotelCouponList() {
	return sendHttp('/diandi_hotel/coupon/list', "POST", {}, true)
}
export function hotelCouponCreateorder() {
	return sendHttp('/diandi_hotel/coupon/createorder', "POST", {}, true)
}
export function hotelCouponGetcoupon(coupon_id) {
	return sendHttp('/diandi_hotel/coupon/getcoupon', "POST", {
		coupon_id
	}, true)
}
export function hotelNotifyCallback() {
	return sendHttp('/diandi_hotel/notify/callback', "GET", {}, true)
}
export function hotelApiRpc() {
	return sendHttp('/diandi_hotel/api/rpc', "GET", {}, true)
}
export function hotelApiIndex() {
	return sendHttp('/diandi_hotel/api/index', "GET", {}, true)
}
export function hotelApiPay() {
	return sendHttp('/diandi_hotel/api/pay', "GET", {}, true)
}
export function hotelWoPersonadd() {
	return sendHttp('/diandi_hotel/wo/person-add', "POST", {}, true)
}
export function hotelWoFaceadd() {
	return sendHttp('/diandi_hotel/wo/face-add', "POST", {}, true)
}
export function hotelWoAuthdevice(order_id) {
	return sendHttp('/diandi_hotel/wo/auth-device', "POST", {
		order_id
	}, true)
}
export function hotelWoDeletedevice() {
	return sendHttp('/diandi_hotel/wo/delete-device', "POST", {}, true)
}
export function hotelWoWebhook() {
	return sendHttp('/diandi_hotel/wo/webhook', "GET", {}, true)
}
export function hotelIndexTop() {
	return sendHttp('/diandi_hotel/index/top', "GET", {}, true)
}
export function hotelEnumsIndex() {
	return sendHttp('/diandi_hotel/Enums/index', "GET", {}, true)
}
export function hotelWishList() {
	return sendHttp('/diandi_hotel/wish/list', "GET", {}, true)
}
export function hotelWishAdd(title) {
	return sendHttp('/diandi_hotel/wish/add', "POST", {
		title
	}, true)
}
export function hotelWishMywish(page, pageSize) {
	return sendHttp('/diandi_hotel/wish/mywish', "POST", {
		page,
		pageSize
	}, true)
}
export function hotelWishDel(cate_id) {
	return sendHttp('/diandi_hotel/wish/del', "DELETE", {
		cate_id
	}, true)
}
export function hotelWishAddwish(hotel_id, room_id, cate_id) {
	return sendHttp('/diandi_hotel/wish/addwish', "POST", {
		hotel_id,
		room_id,
		cate_id
	}, true)
}
export function hotelWishDelwish(hotel_id, room_id) {
	return sendHttp('/diandi_hotel/wish/delwish', "POST", {
		hotel_id,
		room_id
	}, true)
}
export function hotelPcIndexLocation() {
	return sendHttp('/diandi_hotel/pc/index/location', "GET", {}, true)
}
export function hotelPcIndexRegion() {
	return sendHttp('/diandi_hotel/pc/index/region', "GET", {}, true)
}
export function hotelGztSaveface(isSelf,order_id,icard_front,icard_back,realname,icard_code,face_img,face_old,mobile) {
	return sendHttp('/diandi_hotel/gzt/saveface', "POST", {isSelf,order_id,icard_front,icard_back,realname,icard_code,face_img,face_old,mobile}, true)
}
export function hotelGztInfo() {
	return sendHttp('/diandi_hotel/gzt/info', "GET", {}, true)
}
export function hotelGztQuerysingle() {
	return sendHttp('/diandi_hotel/gzt/querysingle', "POST", {}, true)
}
export function hotelMobileRoomList() {
	return sendHttp('/diandi_hotel/mobile/room/list', "POST", {}, true)
}
export function hotelMobileRoomDetail() {
	return sendHttp('/diandi_hotel/mobile/room/detail', "POST", {}, true)
}
export function hotelMobileRoomHomestaydetail() {
	return sendHttp('/diandi_hotel/mobile/room/homestaydetail', "GET", {}, true)
}
export function hotelMobileRoomHomestayhzdetail() {
	return sendHttp('/diandi_hotel/mobile/room/homestayhzdetail', "GET", {}, true)
}
export function hotelMobileRoomHoteldetail() {
	return sendHttp('/diandi_hotel/mobile/room/hoteldetail', "GET", {}, true)
}
export function hotelMobileRoomChild() {
	return sendHttp('/diandi_hotel/mobile/room/child', "GET", {}, true)
}

export function hotelMobileRoomThumbs() {
	return sendHttp('/diandi_hotel/mobile/room/thumbs', "GET", {}, true)
}
export function hotelMobileRoomEvaluate() {
	return sendHttp('/diandi_hotel/mobile/room/evaluate', "GET", {}, true)
}
export function hotelMobileRoomLike() {
	return sendHttp('/diandi_hotel/mobile/room/like', "GET", {}, true)
}
export function hotelMobileRoomHomestay() {
	return sendHttp('/diandi_hotel/mobile/room/homestay', "GET", {}, true)
}
export function hotelMobileRoomHotel() {
	return sendHttp('/diandi_hotel/mobile/room/hotel', "GET", {}, true)
}
export function hotelMobileRoomApartment() {
	return sendHttp('/diandi_hotel/mobile/room/apartment', "GET", {}, true)
}
export function hotelMobileRoomCollect() {
	return sendHttp('/diandi_hotel/mobile/room/collect', "POST", {}, true)
}
export function hotelMobileRoomStatusInit(hotel_id, start_time) {
	return sendHttp('/diandi_hotel/mobile/room/statusInit', "POST", {
		hotel_id,
		start_time
	}, true)
}


export function hotelMobileOrderConfirm(order_id, confirmType, params) {
	return sendHttp('/diandi_hotel/mobile/order/confirm', "POST", {
		order_id, confirmType, params
	}, true)
}

export function hotelMobileHotelOrRoom(hotel_id, room_id) {
	return sendHttp('/diandi_hotel/mobile/order/HotelOrRoom', "POST", {
		hotel_id,
		room_id
	}, true)
}

export function hotelMobileHotelHzDetail(hotel_id, room_id) {
	return sendHttp('/diandi_hotel/mobile/hotel/hzdetail', "POST", {
		hotel_id,
		room_id
	}, true)
}

export function hotelMobileHotelDetail(hotel_id, room_id) {
	return sendHttp('/diandi_hotel/mobile/hotel/detail', "POST", {
		hotel_id,
		room_id
	}, true)
}
export function hotelMobileHotelLike() {
	return sendHttp('/diandi_hotel/mobile/hotel/like', "POST", {}, true)
}
export function hotelMobileHotelEvaluate() {
	return sendHttp('/diandi_hotel/mobile/hotel/evaluate', "POST", {}, true)
}
export function hotelMobileHotelRim() {
	return sendHttp('/diandi_hotel/mobile/hotel/rim', "POST", {}, true)
}
export function hotelPcRoomList() {
	return sendHttp('/diandi_hotel/pc/room/list', "POST", {}, true)
}
export function hotelMobileOrderCreate() {
	return sendHttp('/diandi_hotel/mobile/order/create', "POST", {}, true)
}
export function hotelMobileOrderOutroom() {
	return sendHttp('/diandi_hotel/mobile/order/outroom', "POST", {}, true)
}
export function hotelMobileOrderPay(order_id) {
	return sendHttp('/diandi_hotel/mobile/order/pay', "POST", {
		order_id
	}, true)
}
export function hotelMobileOrderDetail(order_id) {
	return sendHttp('/diandi_hotel/mobile/order/detail', "POST", {
		order_id
	}, true)
}
export function hotelMobileOrderList() {
	return sendHttp('/diandi_hotel/mobile/order/list', "POST", {}, true)
}
export function hotelMobileOrderCheckperson() {
	return sendHttp('/diandi_hotel/mobile/order/checkperson', "POST", {}, true)
}
export function hotelMobileOrderInroom() {
	return sendHttp('/diandi_hotel/mobile/order/inroom', "POST", {}, true)
}
export function hotelOrderMycoupon() {
	return sendHttp('/diandi_hotel/order/mycoupon', "POST", {}, true)
}
export function hotelOrderRenewprice() {
	return sendHttp('/diandi_hotel/order/renewprice', "POST", {}, true)
}
export function hotelOrderCreateOrder(order_type, hotel_id, room_id, start_time, end_time, coupon_id, discount,
	amount_payable, real_pay, person_ids, room_num) {
	return sendHttp('/diandi_hotel/order/createOrder', "POST", {
		order_type,
		hotel_id,
		room_id,
		start_time,
		end_time,
		coupon_id,
		discount,
		amount_payable,
		real_pay,
		person_ids,
		room_num
	}, true)
}
export function hotelOrderCancelorder() {
	return sendHttp('/diandi_hotel/order/cancelorder', "POST", {}, true)
}
export function hotelOrderDelorder(order_id) {
	return sendHttp('/diandi_hotel/order/delorder', "POST", {
		order_id
	}, true)
}
export function hotelOrderOrderdetail(order_id) {
	return sendHttp('/diandi_hotel/order/orderdetail', "POST", {
		order_id
	}, true)
}
export function hotelOrderInvoice() {
	return sendHttp('/diandi_hotel/order/invoice', "POST", {}, true)
}
export function hotelOrderContract(status, page, pageSize) {
	return sendHttp('/diandi_hotel/order/contract', "POST", {
		status,
		page,
		pageSize
	}, true)
}
export function hotelOrderUpcontract(landlord_sign_img, id) {
	return sendHttp('/diandi_hotel/order/upcontract', "POST", {
		landlord_sign_img,
		id
	}, true)
}
export function hotelMsgTimemsg() {
	return sendHttp('/diandi_hotel/msg/timemsg', "POST", {}, true)
}
export function hotelMemberInfo() {
	return sendHttp('/diandi_hotel/member/info', "POST", {}, true)
}
export function hotelMemberAddfriend() {
	return sendHttp('/diandi_hotel/member/addfriend', "POST", {}, true)
}
export function hotelMemberEditfriend(face_img, realname, mobile, icard_code, icard_front, icard_back, id) {
	return sendHttp('/diandi_hotel/member/editfriend', "POST", {
		face_img,
		realname,
		mobile,
		icard_code,
		icard_front,
		icard_back,
		id
	}, true)
}
export function hotelMemberMyfriend() {
	return sendHttp('/diandi_hotel/member/myfriend', "GET", {}, true)
}


export function hotelOrderList(page, pageSize, status) {
	return sendHttp('/diandi_hotel/order/list', "GET", {
		page,
		pageSize,
		status
	}, true)
}


export function hotelMemberDelfriend() {
	return sendHttp('/diandi_hotel/member/delfriend', "POST", {}, true)
}
export function hotelMemberAddcon(content) {
	return sendHttp('/diandi_hotel/member/addcon', "POST", {
		content
	}, true)
}
export function hotelMemberGetcon() {
	return sendHttp('/diandi_hotel/member/getcon', "GET", {}, true)
}
export function hotelBlocApartmentListAdd() {
	return sendHttp('/diandi_hotel/bloc/apartment/list/add', "POST", {}, true)
}
export function hotelBlocApartmentListDel() {
	return sendHttp('/diandi_hotel/bloc/apartment/list/del', "POST", {}, true)
}
export function hotelBlocApartmentListList(type_id, lease_type, keywords, rentType) {
	return sendHttp('/diandi_hotel/bloc/apartment/list/list', "GET", {
		type_id,
		lease_type,
		keywords,
		rentType
	}, true)
}
export function hotelBlocApartmentListProomlist(tier_id, hotel_id, type_id) {
	return sendHttp('/diandi_hotel/bloc/apartment/list/p-room-list', "GET", {
		tier_id,
		hotel_id,
		type_id
	}, true)
}
export function hotelBlocHotelListAdd(name, is_show, address_show, type, location_p, location_c, location_a, address) {
	return sendHttp('/diandi_hotel/bloc/hotel/list/add', "POST", {
		name,
		is_show,
		address_show,
		type,
		location_p,
		location_c,
		location_a,
		address
	}, true)
}
export function hotelBlocHotelListEdit(id, name, type, location_p, location_c, location_a, address) {
	return sendHttp('/diandi_hotel/bloc/hotel/list/edit', "POST", {
		id,
		name,
		type,
		location_p,
		location_c,
		location_a,
		address
	}, true)
}
export function hotelBlocHotelListDel(id) {
	return sendHttp('/diandi_hotel/bloc/hotel/list/del', "POST", {
		id
	}, true)
}
export function hotelBlocHotelListIndex(hotel_type, time_type, keywords) {
	return sendHttp('/diandi_hotel/bloc/hotel/list/index', "GET", {
		hotel_type,
		time_type,
		keywords
	}, true)
}
export function hotelBlocHotelListList(page, pageSize, type) {
	return sendHttp('/diandi_hotel/bloc/hotel/list/list', "GET", {
		page,
		pageSize,
		type
	}, false)
}
export function hotelBlocHotelTypeEdit(id, title, template_type, is_default) {
	return sendHttp('/diandi_hotel/bloc/hotel/type/edit', "POST", {
		id,
		title,
		template_type,
		is_default
	}, true)
}
export function hotelBlocHotelTypeAdd(title, template_type) {
	return sendHttp('/diandi_hotel/bloc/hotel/type/add', "POST", {
		title,
		template_type
	}, true)
}
export function hotelBlocHotelTypeDel(id) {
	return sendHttp('/diandi_hotel/bloc/hotel/type/del', "POST", {
		id
	}, true)
}
export function hotelBlocHotelTypeList() {
	return sendHttp('/diandi_hotel/bloc/hotel/type/list', "GET", {}, false)
}
export function hotelBlocConfigBaseSet(delay_time, lead_time, maintain_time) {
	return sendHttp('/diandi_hotel/bloc/config/base/set', "POST", {
		delay_time,
		lead_time,
		maintain_time
	}, true)
}
export function hotelBlocConfigBaseInfo() {
	return sendHttp('/diandi_hotel/bloc/config/base/info', "GET", {}, true)
}
export function hotelBlocConfigBuildingAdd() {
	return sendHttp('/diandi_hotel/bloc/config/building/add', "POST", {}, true)
}
export function hotelBlocConfigBuildingAdds() {
	return sendHttp('/diandi_hotel/bloc/config/building/adds', "POST", {}, true)
}
export function hotelBlocConfigBuildingInfo() {
	return sendHttp('/diandi_hotel/bloc/config/building/info', "GET", {}, true)
}
export function hotelBlocConfigTierAdd(hotel_id, type_id, title) {
	return sendHttp('/diandi_hotel/bloc/config/tier/add', "POST", {
		hotel_id,
		type_id,
		title
	}, true)
}
export function hotelBlocConfigTierEdit() {
	return sendHttp('/diandi_hotel/bloc/config/tier/edit', "POST", {}, true)
}
export function hotelBlocConfigTierAdds(hotel_id, type_id, nums, prefix) {
	return sendHttp('/diandi_hotel/bloc/config/tier/adds', "POST", {
		hotel_id,
		type_id,
		nums,
		prefix
	}, true)
}
export function hotelBlocConfigTierDel(id) {
	return sendHttp('/diandi_hotel/bloc/config/tier/del', "POST", {
		id
	}, true)
}
export function hotelBlocConfigTierList(type_id, hotel_id) {
	return sendHttp('/diandi_hotel/bloc/config/tier/list', "GET", {
		type_id,
		hotel_id
	}, true)
}
export function hotelBlocConfigTierInfo() {
	return sendHttp('/diandi_hotel/bloc/config/tier/info', "GET", {}, true)
}
export function hotelBlocConfigUnitAdd() {
	return sendHttp('/diandi_hotel/bloc/config/unit/add', "POST", {}, true)
}
export function hotelBlocConfigUnitList() {
	return sendHttp('/diandi_hotel/bloc/config/unit/list', "GET", {}, true)
}
export function hotelBlocConfigUnitAdds() {
	return sendHttp('/diandi_hotel/bloc/config/unit/adds', "POST", {}, true)
}
export function hotelBlocConfigUnitInfo() {
	return sendHttp('/diandi_hotel/bloc/config/unit/info', "GET", {}, true)
}
export function hotelBlocRoomTypeAdd(type_id, cate_id, title, checkIn_start, checkIn_end, cancel_start, cancel_end,
	free_cancel, lanuage, breakfast, score, bedadd_show, persons_show, bed_show, smoke_show, floor_show, area_show,
	displayorder, sales, persons, oprice, cprice, mprice, thumb, thumbs, server, remark) {
	return sendHttp('/diandi_hotel/bloc/room/type/add', "POST", {
		type_id,
		cate_id,
		title,
		checkIn_start,
		checkIn_end,
		cancel_start,
		cancel_end,
		free_cancel,
		lanuage,
		breakfast,
		score,
		bedadd_show,
		persons_show,
		bed_show,
		smoke_show,
		floor_show,
		area_show,
		displayorder,
		sales,
		persons,
		oprice,
		cprice,
		mprice,
		thumb,
		thumbs,
		server,
		remark
	}, true)
}
export function hotelBlocRoomTypeList(type_id) {
	return sendHttp('/diandi_hotel/bloc/room/type/list', "GET", {
		type_id
	}, true)
}
export function hotelBlocRoomTypeEdit(id, cate_id, title, checkIn_start, checkIn_end, cancel_start, cancel_end,
	free_cancel, lanuage, breakfast, score, bedadd_show, persons_show, bed_show, smoke_show, floor_show, area_show,
	displayorder, sales, persons, oprice, cprice, mprice, thumb, thumbs, server, remark) {
	return sendHttp('/diandi_hotel/bloc/room/type/edit', "GET", {
		id,
		cate_id,
		title,
		checkIn_start,
		checkIn_end,
		cancel_start,
		cancel_end,
		free_cancel,
		lanuage,
		breakfast,
		score,
		bedadd_show,
		persons_show,
		bed_show,
		smoke_show,
		floor_show,
		area_show,
		displayorder,
		sales,
		persons,
		oprice,
		cprice,
		mprice,
		thumb,
		thumbs,
		server,
		remark
	}, true)
}
export function hotelBlocRoomTypeInfo() {
	return sendHttp('/diandi_hotel/bloc/room/type/info', "GET", {}, true)
}
export function hotelBlocRoomTypeDetail(id) {
	return sendHttp('/diandi_hotel/bloc/room/type/detail', "GET", {
		id
	}, true)
}
export function hotelBlocRoomTypeDel(id) {
	return sendHttp('/diandi_hotel/bloc/room/type/del', "GET", {
		id
	}, true)
}
export function hotelBlocRoomListIndexstatistics() {
	return sendHttp('/diandi_hotel/bloc/room/list/index-statistics', "POST", {}, true)
}
export function hotelBlocRoomListAdd(hotel_id, type_id, title, tier_id, time_length, time_type, room_pid) {
	return sendHttp('/diandi_hotel/bloc/room/list/add', "POST", {
		hotel_id,
		type_id,
		title,
		tier_id,
		time_length,
		time_type,
		room_pid
	}, true)
}
export function hotelBlocRoomListAdds(hotel_id, type_id, nums, tier_id, time_length, time_type, prefix, title,
	lease_type, room_pid) {
	return sendHttp('/diandi_hotel/bloc/room/list/adds', "POST", {
		hotel_id,
		type_id,
		nums,
		tier_id,
		time_length,
		time_type,
		prefix,
		room_pid
	}, true)
}
export function hotelBlocRoomListList(hotel_id, tier_id) {
	return sendHttp('/diandi_hotel/bloc/room/list/list', "GET", {
		hotel_id,
		tier_id
	}, true)
}
export function hotelBlocRoomListDel(id) {
	return sendHttp('/diandi_hotel/bloc/room/list/del', "POST", {
		id
	}, true)
}
export function hotelBlocRoomListSituation(page, pageSize, status, room_type_id) {
	return sendHttp('/diandi_hotel/bloc/room/list/situation', "GET", {
		page,
		pageSize,
		status,
		room_type_id
	}, true)
}
export function hotelBlocRoomListEdit(id, title, status, time_type, time_length, checkIn_start, checkIn_end,
	cancel_start, cancel_end, room_num, free_cancel, lanuage, breakfast, score, bedadd_show, persons_show, bed_show,
	smoke_show, floor_show, area_show, displayorder, sales, persons, oprice, cprice, mprice, thumb, thumbs, server,
	remark, room_type_id, titles, slides) {
	return sendHttp('/diandi_hotel/bloc/room/list/edit', "POST", {
		id,
		title,
		status,
		time_type,
		time_length,
		checkIn_start,
		checkIn_end,
		cancel_start,
		cancel_end,
		room_num,
		free_cancel,
		lanuage,
		breakfast,
		score,
		bedadd_show,
		persons_show,
		bed_show,
		smoke_show,
		floor_show,
		area_show,
		displayorder,
		sales,
		persons,
		oprice,
		cprice,
		mprice,
		thumb,
		thumbs,
		server,
		remark,
		room_type_id,
		titles,
		slides
	}, true)
}
export function hotelBlocRoomListDetail(id) {
	return sendHttp('/diandi_hotel/bloc/room/list/detail', "GET", {
		id
	}, true)
}
export function hotelBlocRoomTempOccupancy(persons, hotel_id, room_id, start_time, end_time) {
	return sendHttp('/diandi_hotel/bloc/room/temp/occupancy', "POST", {
		persons,
		hotel_id,
		room_id,
		start_time,
		end_time
	}, true)
}
export function hotelBlocRoomTempPersonlist(room_id) {
	return sendHttp('/diandi_hotel/bloc/room/temp/person-list', "GET", {
		room_id
	}, true)
}
export function hotelBlocRoomTempFrozen() {
	return sendHttp('/diandi_hotel/bloc/room/temp/frozen', "GET", {}, true)
}
export function hotelBlocRoomTempOutroom(id) {
	return sendHttp('/diandi_hotel/bloc/room/temp/out-room', "GET", {
		id
	}, true)
}
export function hotelBlocRoomTempOutroomall(room_id) {
	return sendHttp('/diandi_hotel/bloc/room/temp/out-room-all', "GET", {
		room_id
	}, true)
}
export function hotelBlocRoomServerAdd() {
	return sendHttp('/diandi_hotel/bloc/room/server/add', "POST", {}, true)
}
export function hotelBlocRoomServerDel() {
	return sendHttp('/diandi_hotel/bloc/room/server/del', "GET", {}, true)
}
export function hotelBlocTenantListAdd() {
	return sendHttp('/diandi_hotel/bloc/tenant/list/add', "POST", {}, true)
}
export function hotelBlocTenantListDetail() {
	return sendHttp('/diandi_hotel/bloc/tenant/list/detail', "POST", {}, true)
}
export function hotelBlocTenantListList() {
	return sendHttp('/diandi_hotel/bloc/tenant/list/list', "GET", {}, true)
}
export function hotelBlocMemberInfoDetail() {
	return sendHttp('/diandi_hotel/bloc/member/info/detail', "GET", {}, true)
}
export function hotelBlocMemberInfoEdit(realname, mobile, idcard, gender, avatar) {
	return sendHttp('/diandi_hotel/bloc/member/info/edit', "POST", {
		realname,
		mobile,
		idcard,
		gender,
		avatar
	}, true)
}
export function hotelBlocMemberInfoPassword() {
	return sendHttp('/diandi_hotel/bloc/member/info/password', "POST", {}, true)
}
export function hotelBlocMemberInfoIdentity(realname, icard_code, icard_front, icard_back) {
	return sendHttp('/diandi_hotel/bloc/member/info/identity', "POST", {
		realname,
		icard_code,
		icard_front,
		icard_back
	}, true)
}
export function hotelBlocMemberInfoMessage() {
	return sendHttp('/diandi_hotel/bloc/member/info/message', "GET", {}, true)
}
export function hotelBlocAgreementList() {
	return sendHttp('/diandi_hotel/bloc/agreement/list', "GET", {}, true)
}
export function hotelBlocAgreementDel() {
	return sendHttp('/diandi_hotel/bloc/agreement/del', "POST", {}, true)
}
export function hotelBlocAgreementAdd() {
	return sendHttp('/diandi_hotel/bloc/agreement/add', "POST", {}, true)
}
export function hotelBlocAgreementProtocolList() {
	return sendHttp('/diandi_hotel/bloc/agreement/protocol/list', "GET", {}, true)
}
export function hotelBlocAgreementProtocolDel() {
	return sendHttp('/diandi_hotel/bloc/agreement/protocol/del', "POST", {}, true)
}
export function hotelBlocAgreementProtocolAdd() {
	return sendHttp('/diandi_hotel/bloc/agreement/protocol/add', "POST", {}, true)
}
export function hotelDeviceDeviceList(device_type, type_id, hotel_id, tier_id, room_id, page, pageSize, title, status,
	keywords) {
	return sendHttp('/diandi_hotel/device/device/list', "GET", {
		device_type,
		type_id,
		hotel_id,
		tier_id,
		room_id,
		page,
		pageSize,
		title,
		status,
		keywords
	}, true)
}
export function hotelDeviceDeviceDevinfo(device_id) {
	return sendHttp('/diandi_hotel/device/device/devinfo', "GET", {
		device_id
	}, true)
}

export function hotelDeviceByorder(order_id) {
	return sendHttp('/diandi_hotel/device/device/order-device', "POST", {
		order_id
	}, true)
}

export function hotelDeviceOrderList(page,pageSize) {
	return sendHttp('/diandi_hotel/device/device/order-list', "POST", {page,pageSize}, true)
}

export function hotelDeviceDeviceDel(id) {
	return sendHttp('/diandi_hotel/device/device/del', "POST", {
		id
	}, true)
}
export function hotelDeviceDeviceAdd() {
	return sendHttp('/diandi_hotel/device/device/add', "POST", {}, true)
}
export function hotelDeviceDeviceEdit() {
	return sendHttp('/diandi_hotel/device/device/edit', "POST", {}, true)
}
export function hotelDeviceDeviceRoomdevice(hotel_type, hotel_id, tier_id, room_id) {
	return sendHttp('/diandi_hotel/device/device/room-device', "GET", {
		hotel_type,
		hotel_id,
		tier_id,
		room_id
	}, true)
}
export function hotelMobileIndexConfig() {
	return sendHttp('/diandi_hotel/mobile/index/config', "GET", {}, true)
}
export function hotelMobileIndexTabs() {
	return sendHttp('/diandi_hotel/mobile/index/tabs', "GET", {}, true)
}
export function hotelMobileIndexAd() {
	return sendHttp('/diandi_hotel/mobile/index/ad', "GET", {}, true)
}

export function hotelMobileHotelList(page, pageSize, lng, lat, keywords, start_time, end_time, sort_type, rim_type,
	rim_id, min_price, max_price, room_type, commentStatus, commentNums, Breakfast, brand, lanuage, HotelDevice) {
	return sendHttp('/diandi_hotel/mobile/hotel/list', "GET", {
		lng,
		lat,
		page,
		pageSize,
		keywords,
		start_time,
		end_time,
		sort_type,
		rim_type,
		rim_id,
		min_price,
		max_price,
		room_type,
		commentStatus,
		commentNums,
		Breakfast,
		brand,
		lanuage,
		HotelDevice
	}, true)
}

export function hotelMobileHotelListinit(latitude,longitude) {
	return sendHttp('/diandi_hotel/mobile/hotel/listinit', "GET", {latitude,longitude}, true)
}
export function hotelMobileHotelTabs() {
	return sendHttp('/diandi_hotel/mobile/hotel/tabs', "GET", {}, true)
}
export function hotelMobileHotelAd() {
	return sendHttp('/diandi_hotel/mobile/hotel/ad', "GET", {}, true)
}
export function hotelRimList(page, pageSize, rim_type) {
	return sendHttp('/diandi_hotel/rim/list', "GET", {
		page,
		pageSize,
		rim_type
	}, true)
}


export function mallCeshiDongjie() {
	return sendHttp('/diandi_mall/ceshi/dongjie', "GET", {}, true)
}
export function mallCeshiSms() {
	return sendHttp('/diandi_mall/ceshi/sms', "GET", {}, true)
}
export function mallIndexSlides() {
	return sendHttp('/diandi_mall/index/slides', "GET", {}, true)
}
export function mallIndexGoodsadv() {
	return sendHttp('/diandi_mall/index/goodsadv', "GET", {}, true)
}
export function mallIndexPageadv() {
	return sendHttp('/diandi_mall/index/pageadv', "GET", {}, true)
}
export function mallIndexMenu() {
	return sendHttp('/diandi_mall/index/menu', "GET", {}, true)
}
export function mallHelpDetail() {
	return sendHttp('/diandi_mall/help/detail', "POST", {}, true)
}
export function mallHelpLists() {
	return sendHttp('/diandi_mall/help/lists', "GET", {}, true)
}
export function mallOrderCreateorder() {
	return sendHttp('/diandi_mall/order/createorder', "POST", {}, true)
}
export function mallOrderList(page, pageSize, order_status) {
	return sendHttp('/diandi_mall/order/list', "POST", {
		page,
		pageSize,
		order_status
	}, true)
}
export function mallOrderDetail(order_id) {
	return sendHttp('/diandi_mall/order/detail', "POST", {
		order_id
	}, true)
}
export function mallOrderConfirm(order_id, ctype) {
	return sendHttp('/diandi_mall/order/confirm', "POST", {
		order_id,
		ctype
	}, true)
}
export function mallOrderCreategoodsorder(goods_id, total_price, express_price, express_type, name, phone, detail,
	goods_num, address_id) {
	return sendHttp('/diandi_mall/order/creategoodsorder', "POST", {
		goods_id,
		total_price,
		express_price,
		express_type,
		name,
		phone,
		detail,
		goods_num,
		address_id
	}, true)
}
export function mallOrderOrderdetail() {
	return sendHttp('/diandi_mall/order/orderdetail', "GET", {}, true)
}
export function mallOrderGetexpress() {
	return sendHttp('/diandi_mall/order/getexpress', "GET", {}, true)
}
export function mallOrderKdinform() {
	return sendHttp('/diandi_mall/order/kdinform', "GET", {}, true)
}
export function mallOrderIntegralpay() {
	return sendHttp('/diandi_mall/order/integralpay', "POST", {}, true)
}
export function mallOrderDeletebytime() {
	return sendHttp('/diandi_mall/order/deletebytime', "POST", {}, true)
}
export function mallGoodsLists(page, pageSize, keywords, room_type_id, price_sort, sale_sort) {
	return sendHttp('/diandi_mall/goods/lists', "GET", {
		page,
		pageSize,
		keywords,
		room_type_id,
		price_sort,
		sale_sort
	}, true)
}
export function mallGoodsDetail(goods_id) {
	return sendHttp('/diandi_mall/goods/detail', "GET", {
		goods_id
	}, true)
}
export function mallGoodsGoodgift() {
	return sendHttp('/diandi_mall/goods/goodgift', "GET", {}, true)
}
export function mallGoodsSearch() {
	return sendHttp('/diandi_mall/goods/search', "GET", {}, true)
}
export function mallGoodsOrderdetail() {
	return sendHttp('/diandi_mall/goods/orderdetail', "GET", {}, true)
}
export function mallGoodsPainter() {
	return sendHttp('/diandi_mall/goods/painter', "POST", {}, true)
}
export function mallGoodsCollect() {
	return sendHttp('/diandi_mall/goods/collect', "POST", {}, true)
}
export function mallCartAdd() {
	return sendHttp('/diandi_mall/cart/add', "POST", {}, true)
}
export function mallCartList() {
	return sendHttp('/diandi_mall/cart/list', "POST", {}, true)
}
export function mallCartClear() {
	return sendHttp('/diandi_mall/cart/clear', "POST", {}, true)
}
export function mallCartDeletecart() {
	return sendHttp('/diandi_mall/cart/deletecart', "POST", {}, true)
}
export function mallCategoryList() {
	return sendHttp('/diandi_mall/category/list', "GET", {}, true)
}
export function mallAddressGetdefault() {
	return sendHttp('/diandi_mall/address/getdefault', "POST", {}, true)
}
export function mallAddressSetdefault(address_id) {
	return sendHttp('/diandi_mall/address/setdefault', "POST", {
		address_id
	}, true)
}
export function mallAddressLists() {
	return sendHttp('/diandi_mall/address/lists', "POST", {}, true)
}
export function mallAddressDeletes(address_id) {
	return sendHttp('/diandi_mall/address/deletes', "POST", {
		address_id
	}, true)
}
export function mallAddressDetail(address_id) {
	return sendHttp('/diandi_mall/address/detail', "POST", {
		address_id
	}, true)
}
export function mallAddressEdit() {
	return sendHttp('/diandi_mall/address/edit', "POST", {}, true)
}
export function mallAddressAdd(name, phone, province_id, city_id, region_id, detail, is_default) {
	return sendHttp('/diandi_mall/address/add', "POST", {
		name,
		phone,
		province_id,
		city_id,
		region_id,
		detail,
		is_default
	}, true)
}
export function mallCommentComment() {
	return sendHttp('/diandi_mall/comment/comment', "POST", {}, true)
}
export function mallCommentList() {
	return sendHttp('/diandi_mall/comment/list', "GET", {}, true)
}
export function mallAreasList() {
	return sendHttp('/diandi_mall/areas/list', "POST", {}, true)
}
export function mallMemberInfo() {
	return sendHttp('/diandi_mall/member/info', "GET", {}, true)
}
export function mallMemberMyagent() {
	return sendHttp('/diandi_mall/member/myagent', "GET", {}, true)
}
export function mallMemberQrcode() {
	return sendHttp('/diandi_mall/member/qrcode', "POST", {}, true)
}
export function mallMemberAddpayset() {
	return sendHttp('/diandi_mall/member/addpayset', "POST", {}, true)
}
export function mallMemberCollect() {
	return sendHttp('/diandi_mall/member/collect', "POST", {}, true)
}
export function mallMemberGetpayset() {
	return sendHttp('/diandi_mall/member/getpayset', "GET", {}, true)
}
export function mallMemberEditbankapply() {
	return sendHttp('/diandi_mall/member/editbankapply', "POST", {}, true)
}
export function mallMemberWithdrawlist() {
	return sendHttp('/diandi_mall/member/withdrawlist', "GET", {}, true)
}
export function mallMemberWechatqrcode() {
	return sendHttp('/diandi_mall/member/wechat-qrcode', "POST", {}, true)
}
export function mallLevelLink() {
	return sendHttp('/diandi_mall/level/link', "POST", {}, true)
}
export function mallAccountLog() {
	return sendHttp('/diandi_mall/account/log', "GET", {}, true)
}
export function mallAccountOrder() {
	return sendHttp('/diandi_mall/account/order', "POST", {}, true)
}
export function mallAccountAddlog() {
	return sendHttp('/diandi_mall/account/addlog', "POST", {}, true)
}
export function mallAccountWithdraw() {
	return sendHttp('/diandi_mall/account/withdraw', "POST", {}, true)
}
export function mallStoreAdd() {
	return sendHttp('/diandi_mall/store/add', "POST", {}, true)
}
export function mallStoreAddpay() {
	return sendHttp('/diandi_mall/store/addpay', "POST", {}, true)
}
export function mallStorePaylist() {
	return sendHttp('/diandi_mall/store/paylist', "GET", {}, true)
}
export function mallStoreMemberpaylist() {
	return sendHttp('/diandi_mall/store/memberpaylist', "POST", {}, true)
}
export function mallStoreExpress() {
	return sendHttp('/diandi_mall/store/express', "GET", {}, true)
}
export function mallStorePaydetail() {
	return sendHttp('/diandi_mall/store/paydetail', "POST", {}, true)
}
export function mallStoreMystore() {
	return sendHttp('/diandi_mall/store/mystore', "GET", {}, true)
}
export function mallStoreConf() {
	return sendHttp('/diandi_mall/store/conf', "GET", {}, true)
}
export function mallStoreList() {
	return sendHttp('/diandi_mall/store/list', "POST", {}, true)
}
export function mallStoreCreditpay() {
	return sendHttp('/diandi_mall/store/creditpay', "POST", {}, true)
}
export function mallStoreConfirmonline() {
	return sendHttp('/diandi_mall/store/confirmonline', "POST", {}, true)
}
export function mallRefundAdd() {
	return sendHttp('/diandi_mall/refund/add', "POST", {}, true)
}
export function mallRefundList() {
	return sendHttp('/diandi_mall/refund/list', "POST", {}, true)
}
export function mallRefundInfo() {
	return sendHttp('/diandi_mall/refund/info', "GET", {}, true)
}
export function mallRefundDetail() {
	return sendHttp('/diandi_mall/refund/detail', "POST", {}, true)
}
export function mallRefundCancel() {
	return sendHttp('/diandi_mall/refund/cancel', "POST", {}, true)
}
export function mallExpressList() {
	return sendHttp('/diandi_mall/express/list', "POST", {}, true)
}
export function integralMemberInfo() {
	return sendHttp('/diandi_integral/member/info', "GET", {}, true)
}
export function integralIndexIndex() {
	return sendHttp('/diandi_integral/index/index', "POST", {}, true)
}
export function integralStoreInfo() {
	return sendHttp('/diandi_integral/store/info', "GET", {}, true)
}
export function integralStoreDistance() {
	return sendHttp('/diandi_integral/store/distance', "GET", {}, true)
}
export function integralHelpDetail() {
	return sendHttp('/diandi_integral/help/detail', "POST", {}, true)
}
export function integralHelpLists() {
	return sendHttp('/diandi_integral/help/lists', "GET", {}, true)
}
export function integralOrderCreateorder() {
	return sendHttp('/diandi_integral/order/createorder', "POST", {}, true)
}
export function integralOrderList(page, pageSize) {
	return sendHttp('/diandi_integral/order/list', "POST", {
		page,
		pageSize
	}, true)
}
export function integralOrderDetail(order_id) {
	return sendHttp('/diandi_integral/order/detail', "POST", {
		order_id
	}, true)
}
export function integralOrderOrderdetail() {
	return sendHttp('/diandi_integral/order/orderdetail', "GET", {}, true)
}
export function integralOrderConfirm() {
	return sendHttp('/diandi_integral/order/confirm', "POST", {}, true)
}
export function integralOrderCreategoodsorder(goods_id, total_price, express_price, express_type, address_id, name,
	phone, detail, goods_number, is_money, remark, delivery_time, spec_id) {
	return sendHttp('/diandi_integral/order/creategoodsorder', "POST", {
		goods_id,
		total_price,
		express_price,
		express_type,
		address_id,
		name,
		phone,
		detail,
		goods_number,
		is_money,
		remark,
		delivery_time,
		spec_id
	}, true)
}
export function integralOrderExchange() {
	return sendHttp('/diandi_integral/order/exchange', "POST", {}, true)
}
export function integralOrderExchangelist() {
	return sendHttp('/diandi_integral/order/exchangelist', "GET", {}, true)
}
export function integralGoodsLists(page, pageSize, category_pid, goods_price, sales_initial, keywords) {
	return sendHttp('/diandi_integral/goods/lists', "GET", {
		page,
		pageSize,
		category_pid,
		goods_price,
		sales_initial,
		keywords
	}, true)
}
export function integralGoodsSearch() {
	return sendHttp('/diandi_integral/goods/search', "GET", {}, true)
}
export function integralGoodsDetail(goods_id) {
	return sendHttp('/diandi_integral/goods/detail', "GET", {
		goods_id
	}, true)
}
export function integralGoodsGetslide() {
	return sendHttp('/diandi_integral/goods/getslide', "GET", {}, true)
}
export function integralCategoryList() {
	return sendHttp('/diandi_integral/category/list', "GET", {}, true)
}
export function integralCommentComment() {
	return sendHttp('/diandi_integral/comment/comment', "POST", {}, true)
}
export function integralCommentList() {
	return sendHttp('/diandi_integral/comment/list', "GET", {}, true)
}
export function integralAreasList() {
	return sendHttp('/diandi_integral/areas/list', "POST", {}, true)
}
export function integralAddressGetdefault(address_id) {
	return sendHttp('/diandi_integral/address/getdefault', "POST", {
		address_id
	}, true)
}
export function integralAddressSetdefault() {
	return sendHttp('/diandi_integral/address/setdefault', "POST", {}, true)
}
export function integralAddressLists() {
	return sendHttp('/diandi_integral/address/lists', "POST", {}, true)
}
export function integralAddressDeletes(address_id) {
	return sendHttp('/diandi_integral/address/deletes', "POST", {
		address_id
	}, true)
}
export function integralAddressDetail() {
	return sendHttp('/diandi_integral/address/detail', "POST", {}, true)
}
export function integralAddressEdit(name, phone, province_id, city_id, region_id, detail, address_id, is_default) {
	return sendHttp('/diandi_integral/address/edit', "POST", {name, phone, province_id, city_id, region_id, detail, address_id, is_default}, true)
}
export function integralAddressAdd() {
	return sendHttp('/diandi_integral/address/add', "POST", {}, true)
}
export function integralCartAdd() {
	return sendHttp('/diandi_integral/cart/add', "POST", {}, true)
}
export function integralCartList() {
	return sendHttp('/diandi_integral/cart/list', "POST", {}, true)
}
export function integralCartClear() {
	return sendHttp('/diandi_integral/cart/clear', "POST", {}, true)
}
export function integralCartDeletecart() {
	return sendHttp('/diandi_integral/cart/deletecart', "POST", {}, true)
}