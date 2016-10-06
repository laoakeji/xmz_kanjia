<?php

function p($arr) {
	echo "<pre>";
	print_r($arr);
	echo "<pre>";
}

function chengfa($a, $b) {
	echo sprintf("%.2f", (floatval($a) * floatval($b)));

}
/**
 * 系统设置
 * @wxy
 * @DateTime 2016-05-22T23:06:16+0800
 * @return   [type]                   [description]
 */
function get_sys_set() {
	$arr = S("sys_set");
	if (empty($arr)) {

		//$join = "pg_wx_user on pg_wx_user.uid=pg_user.id";
		$arr = D("sys_set")->where("id=1")->find();

		S("sys_set", $arr);
	}
	return $arr;
}
/**
 * @wxy 检查是否微信授权
 * @DateTime 2016-05-18T13:37:11+0800
 * @return   [type]
 */
function check_wx_login() {
	$uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : 0;

	if ($uid > 0) {

	} else {
		if (IS_GET) {
			wx_login();
			$uid = $_SESSION['uid'];
		} else {
			$rd['code'] = 40000; //登陆时间失效，请重新登录
			$rd['msg'] = '登陆时间失效，请重新登录';
			return $rd;
		}

	}
	return $uid;
}

function uid() {
	return $_SESSION['uid'];
}

function wx_login() {

	require_once THINK_PATH . "Weixin/WxApi.class.php";

	$wx = new WxApi;

	$str = $_SERVER['REQUEST_URI'];
	$int = substr_count($str, 'code=');
	if (empty($_SESSION['uid'])) {

		if ($int == 0) {
			$up_uid = I("get.up_uid", 0);
			$tuan_id = I("get.tuan_id", 0);
			$krid = I("get.krid", 0);
			$rid = I("get.rid", 0);
			if ($krid > 0) {
				$tuan_id = $krid;
			}else if($rid > 0){
				$tuan_id = $rid;
			}
			$_SESSION['up_uid'] = isset($_SESSION['up_uid']) ? $_SESSION['up_uid'] : $up_uid;
			$redirectUrl = get_url(); //重定向网页

			$url = $wx->wxOauthUserinfo($redirectUrl, $tuan_id);
			$wx->wxHeader($url);
			exit;
		}

		//p($str);exit;
		$b = strpos($str, 'code=');
		$c = strpos($str, '&state');
		$code = substr($str, $b + 5, 32);

		$arr = $wx->wxOauthAccessToken($code);

		if ($arr['errcode']==40029) {
			$redirectUrl = get_url(); //重定向网页
			$p1=explode('code=',$redirectUrl);
			$redirectUrl=$p1[0];
			//p($redirectUrl);exit;
			$url = $wx->wxOauthUserinfo($redirectUrl);
			$wx->wxHeader($url);
			exit;
		}

		$openid = $arr['openid'];

		//新增616
		if ($openid == "" || empty($openid)) {
			$up_uid = I("get.up_uid", 0);
			$tuan_id = I("get.tuan_id", 0);
			$krid = I("get.krid", 0);
			$rid = I("get.rid", 0);
			if ($krid > 0) {
				$tuan_id = $krid;
			}else if($rid > 0){
				$tuan_id = $rid;
			}
			//$_SESSION['up_uid'] = isset($_SESSION['up_uid']) ? $_SESSION['up_uid'] : $up_uid;
			$redirectUrl = get_url(); //重定向网页

			$url = $wx->wxOauthUserinfo($redirectUrl, $tuan_id);
			$wx->wxHeader($url);
			exit;
		}

		$access_token = $arr['access_token'];
		$arr2 = $wx->wxOauthUser($access_token, $openid);
		$wx_user = D("wx_user");
		$is_exit = $wx_user->where("openid='" . $openid . "'")->find();
		if ($is_exit) {
			$map = array();
			if ($is_exit['nickname'] != $arr2['nickname']) {
				$map['nickname'] = $arr2['nickname'];
			}
			if ($is_exit['headimgurl'] != $arr2['headimgurl']) {
				$map['headimgurl'] = $arr2['headimgurl'];
			}
			if ($is_exit['sex'] != $arr2['sex']) {
				$map['sex'] = $arr2['sex'];
			}
			if ($is_exit['city'] != $arr2['city']) {
				$map['city'] = $arr2['city'];
			}
			if ($is_exit['province'] != $arr2['province']) {
				$map['province'] = $arr2['province'];
			}
			if ($is_exit['country'] != $arr2['country']) {
				$map['country'] = $arr2['country'];
			}
			if ($map) {
				$map['x_time'] = time();
				$wx_user->where("openid='" . $openid . "'")->save($map);
			}

			$uid = $is_exit['uid'];
			$id = D("user")->where("uid=$uid")->getField("id");
			$id = $uid;
		} else {
			$data['a_id'] = $up_uid = isset($_SESSION['up_uid']) ? $_SESSION['up_uid'] : 0;
			//三级新增
			if ($up_uid > 0) {
				$arr_66 = $wx_user->where("uid=$up_uid")->field("a_id,b_id,c_id")->find();
				$data['b_id'] = $arr_66['a_id'];
				$data['c_id'] = $arr_66['b_id'];
			}
			//p($data);exit;
			//三级新增
			$data['a_time'] = time();
			$data['openid'] = $openid;
			$data['nickname'] = $arr2['nickname'];
			$data['headimgurl'] = $arr2['headimgurl'];
			$data['sex'] = $arr2['sex'];
			$data['city'] = $arr2['city'];
			$data['province'] = $arr2['province'];
			$data['country'] = $arr2['country'];
			$data3['x_time'] = $data2['c_time'] = $data['c_time'] = $data['x_time'] = time();


			$wx_user->startTrans(); //事务开始
			$s2 = $wx_user->add($data);

			$datas['name'] = $arr2['nickname'];
			$datas['uid'] = $s2;
			$datas['c_time'] = time();
			$datas['headimgurl'] = $arr2['headimgurl'];

			$id = D("user")->add($datas);
			$id = $s2;


			
			/*if ($s2 > 0 && $s3 > 0 && $s4 > 0) {
				if ($up_uid > 0) {
					send_xj_msg($up_uid, $arr2['nickname']);
					// if ($data['b_id']>0) {
					// 	send_xj_msg($data['b_id'], $arr2['nickname']);
					// }
					// if ($data['c_id']>0) {
					// 	send_xj_msg($data['c_id'], $arr2['nickname']);
					// }
				}*/
			$wx_user->commit(); //事务提交
			/*} else {
				$wx_user->rollback(); //事务回滚
				echo "<h1>注册失败，请联系管理员微信号：wxy511433</h1>";exit;
			}*/

		}

		$_SESSION['uid'] = $id;

	}

	return;
}

/**
 * 获取当前页面完整URL地址
 */
function get_url() {
	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	$php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
	$path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
	$relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : $path_info);
	return $sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url;
}

function echo_url() {
	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	$php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
	$path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
	$relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : $path_info);
	echo urlencode($sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url);
}
/**
 * @wxy	获取用户信息
 * @DateTime 2016-05-18T20:11:15+0800
 * @param    integer
 * @return   [type]
 */
function get_user_info($uid = 0) {
	if ($uid == 0) {
		$uid = uid();
	}
	$arr = S("user_" . $uid);
	$arr = null;
	if (empty($arr)) {

		//$join = "pg_wx_user on pg_wx_user.uid=pg_user.id";
		$arr = D("wx_user")->where("uid=$uid and status=1")->find();

		S("user_" . $uid, $arr);
	}
	return $arr;
}
/**
 * @wxy	获取用户数据
 * @DateTime 2016-05-18T20:16:37+0800
 * @param    integer
 * @return   [type]
 */
function get_user_data($uid = 0) {
	if ($uid == 0) {
		$uid = uid();
	}
	$arr = D("user_data")->where("uid=$uid")->find();
	return $arr;
}
/**
 * @wxy 获取当前用户信息
 * @DateTime 2016-05-18T16:12:12+0800
 * @param    [type]
 * @return   [type]
 */
function get_user_field($field) {
	$uid = $_SESSION['uid'];
	$arr = D("user")->where("id=$uid and status=1")->field($field)->find();
	return $arr;
}
/**
 * @wxy  获取图片
 * @DateTime 2016-05-18T14:36:48+0800
 * @param    integer
 * @return   [type]
 */
function get_img($type = 1) {
	$arr = S("img_" . $type);

	if (empty($arr)) {
		$arr = D("img")->where("type=$type and status=1")->order("sort asc")->select();
		S("img_" . $type, $arr);
	}
	return $arr;
}
function get_index_brand() {
	$arr = S("index_brand");
	//$arr = null;
	if (empty($arr)) {
		$arr = D("goods_brand")->where("status=1")->order("id asc")->limit(3)->select();
		S("index_brand", $arr);
	}
	return $arr;
}
/**
 * @wxy  获取商品列表
 * @DateTime 2016-05-18T15:23:08+0800
 * @param    品牌ID
 * @return   [type]
 */
function get_goods_list($brand_id) {
	$arr = S("goods_list_" . $brand_id);
	//$arr = null;
	if (empty($arr)) {
		$arr = D("goods")->where("brand_id=$brand_id and status=1 and is_gift=0")->order("sort desc")->select();
		S("goods_list_" . $brand_id, $arr);
	}
	return $arr;
}
/**
 * @wxy 获取商品信息
 * @DateTime 2016-05-18T15:39:11+0800
 * @param    商品id
 * @return   [type]
 */
function get_goods_info($goods_id) {
	$arr = S("goods_" . $goods_id);
	//$arr = null;
	if (empty($arr)) {
		$arr = D("goods")->where("id=$goods_id and status=1")->find();
		$arr['goods_imgs_wap'] = explode(',', ltrim($arr['goods_imgs_wap'], ','));
		$arr['goods_imgs_pc'] = explode(',', ltrim($arr['goods_imgs_pc'], ','));
		S("goods_" . $goods_id, $arr);
	}
	return $arr;
}
/**
 * 获取商品动态数据
 * @wxy
 * @DateTime 2016-05-22T20:48:30+0800
 * @param    [type]                   $goods_id [description]
 * @return   [type]                             [description]
 */
function get_goods_data($goods_id) {
	$arr = D("goods_data")->where("goods_id=$goods_id")->find();
	return $arr;
}
/**
 * 获取商品折扣信息
 * @wxy
 * @DateTime 2016-05-22T21:01:09+0800
 * @param    [type]                   $goods_id [description]
 * @return   [type]                             [description]
 */
function get_goods_discount($goods_id) {
	$time = time();

	$arr = D("goods_discount")->where("goods_id=$goods_id and s_time>=$time and e_time<$time and status=1")->find();
	if ($arr && $arr['num'] > 0) {
		$res['is_discount'] = 1;
		$res['data'] = $arr;
	} else {
		$res['is_discount'] = 0;
	}
	return $res;
}

/**
 * @wxy	获取商品字段
 * @DateTime 2016-05-18T16:19:35+0800
 * @param    [type]
 * @param    [type]
 * @return   [type]
 */
function get_goods_base_info($goods_id) {
	$arr = S("goods_base_" . $goods_id);
	$arr = null;
	if (empty($arr)) {
		$arr = D("goods")->where("id=$goods_id and status=1")->field("price,cy_price,goods_name,specification,goods_thumb_wap,goods_thumb_pc,gift_type,t_peo_price,t_peo_num,t_peo_max_num")->find();
		S("goods_base_" . $goods_id, $arr);
	}

	return $arr;
}
/**
 * @wxy	 获取购物车商品列表
 * @DateTime 2016-05-18T17:15:07+0800
 * @return   [type]
 */
function get_my_car_list() {
	$uid = $_SESSION['uid'];
	$arr = S("my_car_list_" . $uid);
	$arr = null;
	if (empty($arr)) {
		$join = "pg_goods_data on pg_goods_data.goods_id=pg_goods_car.goods_id";
		$join2 = "pg_goods on pg_goods.id=pg_goods_car.goods_id";
		$field = "pg_goods_data.sku_num,pg_goods.status as g_status,pg_goods_car.*,pg_goods.goods_thumb_wap,pg_goods.price,pg_goods.gift_type";
		$arr = D("goods_car")->join($join)->join($join2)->where("pg_goods_car.uid=$uid and pg_goods_car.status=1")->field($field)->select();
		S("my_car_list_" . $uid, $arr);
	}
	return $arr;
}

/**
 * 删除购物车
 * @wxy
 * @DateTime 2016-05-23T21:49:32+0800
 * @param    [type]                   $id [description]
 * @return   [type]                       [description]
 */
function delete_car($id) {
	$map['status'] = -1;
	$s = D("goods_car")->where("id=$id")->save($map);
	$rd['code'] = 1;
	$rd['msg'] = $s;

	return $rd;
}
/**
 * 购物车商品数量
 * @wxy
 * @DateTime 2016-05-22T23:32:22+0800
 * @return   [type]                   [description]
 */
function car_num() {
	$uid = uid();
	$num = D("goods_car")->where("uid=$uid and status=1")->distinct(true)->field('goods_id')->count();
	return $num;
}
/**
 * @wxy	 获取商品信息
 * @DateTime 2016-05-18T18:50:17+0800
 * @return   [type]
 */
function get_car_list($car_ids) {
	$uid = $_SESSION['uid'];
	$car_ids = rtrim($car_ids, ',');
	$arr = S("car_list_" . $car_ids);
	if (empty($arr)) {
		$join = "pg_goods_data on pg_goods_data.goods_id=pg_goods_car.goods_id";
		$join2 = "pg_goods on pg_goods.id=pg_goods_car.goods_id";
		$field = "pg_goods_data.sku_num,pg_goods.status as g_status,pg_goods_car.*,pg_goods.goods_thumb_wap,pg_goods.gift_type";
		$arr = D("goods_car")->join($join)->join($join2)->where("uid=$uid and pg_goods_car.id in ($car_ids) and pg_goods_car.status=1")->field($field)->select();
		S("car_list_" . $uid, $arr);
	}
	return $arr;
}
/**
 * @wxy	获取购物车商品状态
 * @DateTime 2016-05-18T17:29:55+0800
 * @param    [type]
 * @return   [type]
 */
function get_goods_car_status($car_id) {
	$status = D("goods_car")->where("id=$car_id")->getField("status");
	return $status;
}

/**
 * 生成订单号
 */

function order_no() {
	return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
}
/**
 * @wxy	获取下单信息
 * @DateTime 2016-05-18T18:39:50+0800
 * @param    [type]
 * @return   [type]
 */
// function get_order_info($order_id) {

// 	$arr = D("goods_order")->where("id=$order_id")->find();
// 	$goods = D("goods");
// 	$car_ids = $arr['car_ids'];
// 	$join = "pg_goods_data on pg_goods_data.goods_id=pg_goods_car.goods_id";
// 	$join2 = "pg_goods on pg_goods.id=pg_goods_car.goods_id";
// 	$field = "pg_goods_car.*,pg_goods_data.sku_num,pg_goods.status as g_status";
// 	$arr['goods_car'] = D("goods_car")->where("id in ($car_ids)")->join($join)->field($field)->select();
// 	return $arr;

// }
/**
 * @wxy	 我的收货地址列表
 * @DateTime 2016-05-18T21:00:58+0800
 * @return   [type]
 */
function get_my_address_list() {
	$uid = $_SESSION['uid'];
	$arr = S("address_list_" . $uid);
	if (empty($arr)) {
		$arr = D("user_address")->where("uid=$uid")->select();
		S("address_list_" . $uid, $arr);
	}
	return $arr;
}
/**
 * @wxy	获取收货地址详情
 * @DateTime 2016-05-18T21:02:42+0800
 * @param    [type]
 * @return   [type]
 */
function get_address_detail($id) {
	$arr = S("address_" . $id);
	if (empty($arr)) {
		$arr = D("user_address")->where("id=$id")->find();
		S("address_" . $id, $arr);
	}
	return $arr;
}

/**
 * 获取商品费用
 * @wxy
 * @DateTime 2016-05-23T09:52:37+0800
 * @param    [type]                   $money [description]
 * @return   [type]                          [description]
 */
function get_fee($money, $gift_type = 0) {

	$uid = uid();
	$user_info = get_user_info($uid);
	$sys_set = get_sys_set();
	$fee = $sys_set['postage'];
	if ($gift_type > 0) {
		$sys_set['a_discount'] = 1;
		$sys_set['b_discount'] = 1;
	}
	$money2 = $money;
	switch ($user_info['rank']) {
	case '1': //普通会员
		$money = $money;
		if ($money >= $sys_set['post_free_price']) {
			$fee = 0;
		}
		break;
	case '2': //A型
		$money = $money * $sys_set['b_discount'];
		if ($money >= $sys_set['post_free_price']) {
			$fee = 0;
		}

		break;
	case '3': //B型
		$money = $money * $sys_set['a_discount'];
		if ($money >= $sys_set['post_free_price']) {
			$fee = 0;
		}

		break;
	default:
		$money = $money;
		break;
	}
	if ($gift_type == 3) {
		$fee = 0;
		$money = $money2;
	}
	$res['money'] = $money;
	$res['fee'] = $fee;
	return $res;
}
/**
 * 获取订单信息
 * @wxy
 * @DateTime 2016-05-23T10:12:15+0800
 * @param    [type]                   $order_id [description]
 * @return   [type]                             [description]
 */
function get_order_info($order_id) {
	$res = S("order_" . $order_id);
	if (empty($res)) {
		$arr = D("goods_order")->where("id=$order_id")->find();
		$car = explode(',', $arr['car_ids']);
		foreach ($car as $key => &$e) {
			$e['car_detail'] = D("goods_car")->where("id='" . $e . "'")->find();
		}
		unset($e);
		$res['data'] = $arr;
		$res['car'] = $car;
		S("order_" . $order_id, $res);
	}
	return $res;
}
/**
 * 获取订单金额
 * @wxy
 * @DateTime 2016-05-23T10:38:41+0800
 * @param    [type]                   $order_id [description]
 * @return   [type]                             [description]
 */
function get_order_money($order_id) {

	$arr = D("goods_order")->where("id=$order_id")->field("money,carriage")->find();
	$money = $arr['money'] + $arr['carriage'];
	return $money;
}

function add_goods_pay_record($order_id) {
	$order_info = get_order_product($order_id);
	$pay_record = D("pay_record");
	$arr_5 = $pay_record->where("pid=$order_id and type=2")->find();
	if ($arr_5) {
		return $arr_5;
	}
	$uid = $order_info['data']['uid'];
	$money = $order_info['data']['money'] + $order_info['data']['carriage'];
	$map2['uid'] = $uid;
	$map2['money'] = $money;
	$map2['type'] = 2;
	//$map2['pay_type'] = 2;
	$map2['status'] = 1;
	$map2['c_time'] = time();
	$map2['pid'] = $order_id;
	$map2['order_no'] = $order_info['data']['order_no'];

	//$pay_record->startTrans();

	$id = $pay_record->add($map2);
	// $data['status'] = 2;
	// $s2 = D("goods_order")->where("id=$order_id and status=1")->save($data);
	// if ($id > 0 && $s2 > 0) {
	// 	$pay_record->commit();
	// } else {
	// 	$pay_record->rollback();
	// 	exit("出错，请联系管理员");
	// }
	$map2['id'] = $id;
	return $map2;
}

function change_rank($uid, $rank) {
	$map33['rank'] = $rank;
	$map33['rank_time'] = time();
	$arr = D("user_data")->where("uid=$uid")->field("whd_money,fx_money,money")->find();
	if ($arr['whd_money'] > 0) {
		$map['whd_money'] = 0;
		$money = $arr['whd_money'];
		$map['money'] = $arr['money'] + $money;
		$map['fx_money'] = $arr['fx_money'] + $money;
		D("user_data")->where("uid=$uid")->save($map);
		$data['uid'] = $uid;
		$data['money'] = $money;
		$data['status'] = 1; //冻结中
		$data['c_time'] = time();
		$data['type'] = 6;
		D("pay_record")->add($data);

	}
	$s7 = D("wx_user")->where("uid=$uid")->save($map33);
	return $s7;
}
/**
 * 余额支付
 * @wxy
 * @DateTime 2016-05-23T10:39:17+0800
 * @param    [type]                   $order_id [description]
 * @return   [type]                             [description]
 */
function yue_pay($pay_id) {
	$pay_record = D("pay_record");
	$pay_record->startTrans();
	$arr_5 = D("pay_record")->where("id=$pay_id and type=2")->find();
	$order_id = $arr_5['pid'];
	$s99 = 0;
	if ($arr_5['status'] == 2) {
		$rd['code'] = 40076; //已支付
		$rd['msg'] = '订单已支付';
		return $rd;
	}
	// $money=get_order_money($order_id);
	$order_info = get_order_product($order_id);

	if ($order_info['data']['status'] != 1) {
		$rd['code'] = 40073; //余额不足
		$rd['msg'] = '订单已支付或已失效';
		return $rd;
	}
	$uid = $order_info['data']['uid'];
	$money = $order_info['data']['money'] + $order_info['data']['carriage'];
	$user_data = get_user_data();
	if ($user_data['money'] < $money) {
		$rd['code'] = 40071; //余额不足
		$rd['msg'] = '余额不足';
		return $rd;
	}
	$user_info = get_user_info($uid);
	$a_id = $user_info['a_id'];
	$b_id = $user_info['b_id'];
	$c_id = $user_info['c_id'];
	//$up_user = D("wx_user")->where("uid=$up_uid")->field("rank,user_type")->find();
	$sys_set = get_sys_set();
	$fx_money = 0;
	$t_fx_money = 0;

	$my_rank = D("wx_user")->where("uid=$uid")->getField("rank");
	$s7 = 1;
	$dis_a = 1;
	// if ($my_rank == 1) {
	// 	$dis_a = 1;
	// }
	// if ($my_rank == 2) {
	// 	$dis_a = $sys_set['b_discount'];
	// }
	// if ($my_rank == 3) {
	// 	$dis_a = $sys_set['a_discount'];
	// }
	$t_peo_num = 0;
	$goods_order = D("goods_order");
	foreach ($order_info['car'] as $key => $e) {

		switch ($e['gift_type']) {
		case 1: //礼包

			if ($my_rank != 2) {

				$s7 = change_rank($uid, 2);

			}
			$amoney = $e['goods_num'] * $sys_set['ascale'] * $e['goods_price'];
			$bmoney = $e['goods_num'] * $sys_set['bscale'] * $e['goods_price'];
			$cmoney = $e['goods_num'] * $sys_set['cscale'] * $e['goods_price'];

			break;
		case 2: //团购

			break;
		case 3: //团购

			$tuan_id = $order_info['data']['tuan_id'];
			$t_peo_num = $e['t_peo_num'];
			$t_peo_max_num = $e['t_peo_max_num'];
			$num = $goods_order->where("tuan_id=$tuan_id and status=2")->count();
			$m_num = $goods_order->where("tuan_id=$tuan_id and status=2 and uid=$uid")->count();
			$time = time();
			$t1 = $time - 24 * 60 * 60;
			$arr_232 = $goods_order->where("tuan_id=$tuan_id and status=2 and is_tuan=1 and pay_time<=$t1")->find(); //
			if ($arr_232) {
				$rd['code'] = 40305; //拼团时间到
				$rd['msg'] = "拼团时间到"; //团购人数已满
				return $rd;exit;
			}
			if ($num < $t_peo_num) {
				$amoney = 0;
				if ($m_num < $t_peo_max_num) {

				} else {
					$rd['code'] = 40301; //团购人数已满
					$rd['msg'] = "每人限购" . $t_peo_max_num . "件"; //团购人数已满
					return $rd;exit;
				}
			} else {
				$rd['code'] = 40302; //团购人数已满
				$rd['msg'] = "团购人数已满"; //团购人数已满
				return $rd;exit;
			}

			break;
		default:

			break;
		}

		if ($amoney > 0) {
			$time = time();
			if ($a_id > 0) {
				$rank = get_rank($a_id);
				if ($rank > 1) {
					$map[0]['uid'] = $a_id;
					$map[0]['money'] = $amoney;
					$map[0]['status'] = 1; //冻结中
					$map[0]['c_time'] = $time;
					$map[0]['type'] = 3;
					$map[0]['pid'] = $order_id;
					$map[0]['order_no'] = "FX" . order_no();

					D("user_data")->where("uid=$a_id")->setInc("fxz_money", $amoney);
					//D("user_data")->where("uid=$a_id")->setInc("money", $amoney);
				}
			}
			if ($b_id > 0) {
				$rank = get_rank($b_id);
				if ($rank > 1) {
					$map[1]['uid'] = $b_id;
					$map[1]['money'] = $bmoney;
					$map[1]['status'] = 1; //冻结中
					$map[1]['c_time'] = $time;
					$map[1]['type'] = 3;
					$map[1]['pid'] = $order_id;
					$map[1]['order_no'] = "FX" . order_no();

					D("user_data")->where("uid=$b_id")->setInc("fxz_money", $bmoney);
					//D("user_data")->where("uid=$b_id")->setInc("money", $bmoney);
				}
			}
			if ($c_id > 0) {
				$rank = get_rank($c_id);
				if ($rank > 1) {
					$map[2]['uid'] = $c_id;
					$map[2]['money'] = $cmoney;
					$map[2]['status'] = 1; //冻结中
					$map[2]['c_time'] = $time;
					$map[2]['type'] = 3;
					$map[2]['pid'] = $order_id;
					$map[2]['order_no'] = "FX" . order_no();

					D("user_data")->where("uid=$c_id")->setInc("fxz_money", $cmoney);
					//D("user_data")->where("uid=$c_id")->setInc("money", $cmoney);
				}
			}
			$s = $pay_record->addAll($map);
			$s99 = 1;
			//$t_fx_money += $fx_money;

		}
		D("goods_data")->where("goods_id='" . $e['goods_id'] . "'")->setInc("sale_num", $e['goods_num']);
		D("goods_data")->where("goods_id='" . $e['goods_id'] . "'")->setInc("sku_num", -$e['goods_num']);
	}

	$s2 = D("user_data")->where("uid=$uid")->setInc("money", -$money);

	$map2['pay_type'] = 1;
	$map2['status'] = 2;
	$map2['p_time'] = $data['pay_time'] = time();
	$s4 = $pay_record->where("id=$pay_id and status=1")->save($map2);
	$data['status'] = 2;
	$s3 = $goods_order->where("id=$order_id and status=1")->save($data);
	if ($t_peo_num > 0) {
		check_taun($tuan_id, $t_peo_num);
	}
	if ($s2 > 0 && $s3 > 0 && $s4 > 0 && $s7 > 0) {
		$rd['code'] = 1; //支付失败
		$rd['msg'] = "支付成功"; //支付失败
		$pay_record->commit();
		send_templet_msg($user_info['openid'], 3, "/Home/wap/order_list", "创业天使", $order_info['data']['order_no'], ($order_info['data']['money'] + $order_info['data']['carriage']));

		if ($s99 > 0) {
			# code...
			send_fx_msg($user_info, $order_info['data']['order_no'], $order_info['data']['money']);
		}
		// if ($t_fx_money > 0) {
		// 	send_up_order_msg($up_uid, $order_id, $money, $t_fx_money);
		// }

	} else {
		$pay_record->rollback();

		$rd['code'] = 40072; //支付失败
		$rd['msg'] = "支付失败"; //支付失败

	}
	return $rd;

}
/**
 * 检查是否团购成功
 * @wxy
 * @DateTime 2016-06-04T14:38:47+0800
 * @return   [type]                   [description]
 */
function check_taun($tuan_id = 0, $t_peo_num) {

	$n = D("goods_order")->where("tuan_id=$tuan_id and status=2")->count();
	// p($n);
	// p($t_peo_num);exit;
	if ($n >= $t_peo_num) {
		$arr = D("goods_order")->where("tuan_id=$tuan_id and pg_goods_order.status=2")->join("pg_wx_user on pg_wx_user.uid=pg_goods_order.uid")->field("order_no,money,openid")->select();
		$data['tuan_status'] = 2; //拼团成功
		D("goods_order")->where("tuan_id=$tuan_id and pg_goods_order.status=2")->save($data);
		foreach ($arr as $key => $e) {
			send_templet_msg($e['openid'], 6, "/Home/wap/my_tuan", $e['order_no'], $e['money']);
		}
	}
	return $n;
}

function update_pay_recored($out_trade_no) {
	$pay_record = D("pay_record");
	$pay_record->startTrans(); //事务开始
	$arr99 = $pay_record->where("out_trade_no='" . $out_trade_no . "'")->find();
	//p($arr99);
	if ($arr99['status'] != 1) {
		return false;
	}

	if ($arr99['type'] == 4) {

		$map['status'] = 2;
		$id = $arr99['id'];
		$s1 = $pay_record->where("id=$id")->save($map);
		$uid = $arr99['uid'];
		$s2 = D("user_data")->where("uid=$uid")->setInc("money", $arr99['money']);
		if ($s1 > 0 && $s2 > 0) {
			$pay_record->commit();
			$rd['code'] = 1;
			$rd['msg'] = "充值成功";
		} else {
			$pay_record->rollback();
		}

		return true;
	}
	$s99 = 0;
	if ($arr99['type'] == 2) {
		$order_id = $arr99['pid'];
		// $money=get_order_money($order_id);
		$order_info = get_order_product($order_id);

		if ($order_info['data']['status'] != 1) {
			$rd['code'] = 40073; //余额不足
			$rd['msg'] = '订单已支付或已失效';
			return $rd;
		}
		$uid = $order_info['data']['uid'];
		$money = $order_info['data']['money'] + $order_info['data']['carriage'];

		$user_info = get_user_info($uid);
		$a_id = $user_info['a_id'];
		$b_id = $user_info['b_id'];
		$c_id = $user_info['c_id'];
		//$up_user = D("wx_user")->where("uid=$up_uid")->field("rank,user_type")->find();
		$sys_set = get_sys_set();
		$fx_money = 0;
		$t_fx_money = 0;

		$my_rank = D("wx_user")->where("uid=$uid")->getField("rank");
		$s7 = 1;
		$dis_a = 1;
		$t_peo_num = 0;
		$goods_order = D("goods_order");
		foreach ($order_info['car'] as $key => $e) {

			switch ($e['gift_type']) {
			case 1: //礼包

				if ($my_rank != 2) {
					$s7 = change_rank($uid, 2);
				}
				$amoney = $e['goods_num'] * $sys_set['ascale'] * $e['goods_price'];
				$bmoney = $e['goods_num'] * $sys_set['bscale'] * $e['goods_price'];
				$cmoney = $e['goods_num'] * $sys_set['cscale'] * $e['goods_price'];

				break;
			case 2: //团购

				break;
			case 3: //团购

				$tuan_id = $order_info['data']['tuan_id'];
				$t_peo_num = $e['t_peo_num'];
				$t_peo_max_num = $e['t_peo_max_num'];
				$num = $goods_order->where("tuan_id=$tuan_id and status=2")->count();
				$m_num = $goods_order->where("tuan_id=$tuan_id and status=2 and uid=$uid")->count();
				$time = time();
				$t1 = $time - 24 * 60 * 60;
				$arr_232 = $goods_order->where("tuan_id=$tuan_id and status=2 and is_tuan=1 and pay_time<=$t1")->find(); //
				if ($arr_232) {
					$rd['code'] = 40305; //拼团时间到
					$rd['msg'] = "拼团时间到"; //团购人数已满
					return $rd;exit;
				}
				if ($num < $t_peo_num) {
					$amoney = 0;
					if ($m_num < $t_peo_max_num) {

					} else {
						$rd['code'] = 40301; //团购人数已满
						$rd['msg'] = "每人限购" . $t_peo_max_num . "件"; //团购人数已满
						return $rd;exit;
					}
				} else {
					$rd['code'] = 40302; //团购人数已满
					$rd['msg'] = "团购人数已满"; //团购人数已满
					return $rd;exit;
				}

				break;
			default:

				break;
			}

			if ($amoney > 0) {
				$time = time();
				if ($a_id > 0) {
					$rank = get_rank($a_id);
					if ($rank > 1) {
						$map[0]['uid'] = $a_id;
						$map[0]['money'] = $amoney;
						$map[0]['status'] = 1; //冻结中
						$map[0]['c_time'] = $time;
						$map[0]['type'] = 3;
						$map[0]['pid'] = $order_id;
						$map[0]['order_no'] = "FX" . order_no();

						D("user_data")->where("uid=$a_id")->setInc("fxz_money", $amoney);
						//D("user_data")->where("uid=$a_id")->setInc("money", $amoney);
					}

				}
				if ($b_id > 0) {
					$rank = get_rank($b_id);
					if ($rank > 1) {
						$map[1]['uid'] = $b_id;
						$map[1]['money'] = $bmoney;
						$map[1]['status'] = 1; //冻结中
						$map[1]['c_time'] = $time;
						$map[1]['type'] = 3;
						$map[1]['pid'] = $order_id;
						$map[1]['order_no'] = "FX" . order_no();

						D("user_data")->where("uid=$b_id")->setInc("fxz_money", $bmoney);
						//D("user_data")->where("uid=$b_id")->setInc("money", $bmoney);
					}
				}
				if ($c_id > 0) {
					$rank = get_rank($c_id);
					if ($rank > 1) {

						$map[2]['uid'] = $c_id;
						$map[2]['money'] = $cmoney;
						$map[2]['status'] = 1; //冻结中
						$map[2]['c_time'] = $time;
						$map[2]['type'] = 3;
						$map[2]['pid'] = $order_id;
						$map[2]['order_no'] = "FX" . order_no();

						D("user_data")->where("uid=$c_id")->setInc("fxz_money", $cmoney);
						//D("user_data")->where("uid=$c_id")->setInc("money", $cmoney);
					}
				}
				$s = $pay_record->addAll($map);
				$s99 = 1;
				//$t_fx_money += $fx_money;

			}
			D("goods_data")->where("goods_id='" . $e['goods_id'] . "'")->setInc("sale_num", $e['goods_num']);
			D("goods_data")->where("goods_id='" . $e['goods_id'] . "'")->setInc("sku_num", -$e['goods_num']);
		}

		$s2 = 1;

		$map2['status'] = 2;
		$map2['pay_type'] = 2;
		$map2['p_time'] = $data['pay_time'] = time();
		$s4 = $pay_record->where("id='" . $arr99['id'] . "'")->save($map2);
		//
		$data['status'] = 2;
		$s3 = $goods_order->where("id=$order_id and status=1")->save($data);

		//$s3 = 1;
		if ($t_peo_num > 0) {
			check_taun($tuan_id, $t_peo_num);

		}
		if ($s2 > 0 && $s3 > 0 && $s4 > 0 && $s7 > 0) {
			$rd['code'] = 1; //支付失败
			$rd['msg'] = "支付成功"; //支付失败
			$pay_record->commit();
			send_templet_msg($user_info['openid'], 3, "/Home/wap/order_list", "创业天使", $order_info['data']['order_no'], ($order_info['data']['money'] + $order_info['data']['carriage']));
			if ($s99 > 0) {
				# code...
				send_fx_msg($user_info, $order_info['data']['order_no'], $order_info['data']['money']);
			}
			// send_admin_msg($user_info['uid'], ($order_info['data']['money'] + $order_info['data']['carriage']), $order_info['data']['order_no']);
			// if ($t_fx_money > 0) {
			// 	send_up_order_msg($up_uid, $order_id, $money, $t_fx_money);
			// }

		} else {
			$pay_record->rollback();

			$rd['code'] = 40072; //支付失败
			$rd['msg'] = "支付失败"; //支付失败

		}
		return $rd;

	}

}
function get_rank($uid = 0) {
	if ($uid > 0) {
	} else {
		return 0;
	}

	return D("wx_user")->where("uid=$uid")->getField("rank");
}

function get_order_product($order_id) {
	$arr = D("goods_order")->where("id=$order_id")->field("car_ids,money,carriage,order_no,uid,status,tuan_id")->find();
	$car = explode(',', $arr['car_ids']);
	$join = "pg_goods on pg_goods.id=pg_goods_car.goods_id";
	$field = "pg_goods.gift_type,pg_goods_car.goods_num,pg_goods_car.goods_price,pg_goods_car.goods_id,pg_goods.t_peo_num,pg_goods.t_peo_max_num";
	$arr2 = array();
	foreach ($car as $key => &$m) {

		$arr2[] = D("goods_car")->where("pg_goods_car.id='" . $m . "'")->join($join)->field($field)->find();

	}
	unset($m);

	$res['data'] = $arr;
	$res['car'] = $arr2;
	return $res;
}

function get_up_user() {
	$uid = uid();
	$a_id = D("wx_user")->where("uid=$uid")->getField("a_id");
	$arr = D("wx_user")->where("uid=$a_id")->find();
	if (!$arr) {
		$arr['nickname'] = "暂无上级";
		$arr['headimgurl'] = __ROOT__ . "/Public/Home/images/index_01.png";
	}
	return $arr;
}

// function get_my_team($rank) {
// 	$uid = uid();
// 	if ($rank == 0) {
// 		$where = "a_id=$uid";
// 	} else {
// 		$where = "a_id=$uid and rank=$rank";
// 	}
// 	$arr = D("wx_user")->where($where)->field("nickname,headimgurl,rank")->select();
// 	return $arr;
// }
function get_my_team($rank, $uid) {
	//$uid = uid();

	if ($rank == 1) {
		$where = "a_id=$uid";
	} elseif ($rank == 2) {
		$where = "b_id=$uid";
	} elseif ($rank == 3) {
		$where = "c_id=$uid";
	} else {
		$where = "a_id=$uid";
	}
	$arr = D("wx_user")->where($where)->field("nickname,headimgurl,rank,uid")->select();
	return $arr;
}

function get_rank_list() {
	$join = "pg_wx_user on pg_wx_user.uid=pg_user_data.uid";
	$arr = D("user_data")->where("pg_user_data.uid not in (151,148,51) and fx_money>0")->join($join)->field("(fx_money+fxz_money) as tm,headimgurl,nickname,fx_money,fxz_money,pg_user_data.uid")->order("(fx_money+fxz_money) desc")->limit('100')->select();
	return $arr;
}

function get_order_list($type) {
	$uid = uid();
	if ($type == 0) {
		$where = "status>0 and uid=$uid";
	} elseif ($type == -2) {
		$where = "status<-1 and uid=$uid";
	} else {
		$where = "status=$type and uid=$uid";
	}

	$arr = D("goods_order")->where($where)->order("id desc")->select();
	$car = D("goods_car");
	foreach ($arr as $key => &$e) {
		$car_ids = $e['car_ids'];
		$arr2 = $car->where("id in ($car_ids) and status=2")->select();
		$e['car'] = $arr2;
	}
	unset($e);
	return $arr;
}

function cancel_order($id) {
	$uid = uid();
	$data['status'] = -1;
	$s = D("goods_order")->where("status=1 and id=$id and uid=$uid")->save($data);
	$rd['code'] = 1;
	$rd['msg'] = "已取消";
	return $rd;
}

function confirm_order($id) {
	$uid = uid();
	$data['status'] = 4;
	$s = D("goods_order")->where("status=2 and id=$id and uid=$uid")->save($data);
	$rd['code'] = 1;
	$rd['msg'] = "已确认";
	return $rd;
}

function get_pay_record($type) {
	$uid = uid();
	$where = "uid=$uid";
	switch ($type) {
	case 1: //未获得
		$where .= " and type=6 and status=1";
		break;
	case 2: //冻结中
		$where .= " and type=3 and status=1";
		break;
	default:

		break;
	}

	$arr = D("pay_record")->where($where)->order("id desc")->select();
	foreach ($arr as $key => &$e) {
		if ($e['type'] == 3) {
			$arr2 = D("goods_order")->where("id='" . $e['pid'] . "'")->join("pg_wx_user on pg_wx_user.uid=pg_goods_order.uid")->field("pg_wx_user.nickname")->find();
			$e['xj_name'] = $arr2['nickname'];
		}
	}
	unset($e);
	return $arr;
}

function send_order_msg() {
	send_templet_msg($user_info['openid'], 3, "/Home/wap/order_list", "创业天使", $order_info['data']['order_no'], $order_info['data']['money']);
}

function send_fx_msg($user_info, $order_no, $money) {
	$a_id = $user_info['a_id'];
	$b_id = $user_info['b_id'];
	$c_id = $user_info['c_id'];
	$sys_set = get_sys_set();
	$money = sprintf("%.2f", substr(sprintf("%.3f", $money), 0, -2));
	if ($a_id > 0) {
		$user = D("wx_user")->where("uid=$a_id")->field("openid,rank")->find();
		if ($user['rank'] > 0) {
			$money2 = $sys_set['ascale'] * $money;
			$money2 = sprintf("%.2f", substr(sprintf("%.3f", $money2), 0, -2));
			send_templet_msg($user['openid'], 2, "/Home/wap/my_account", $order_no, $money, $money2, "一级分销" . $user_info['nickname']);
		}
	}
	if ($b_id > 0) {
		$user = D("wx_user")->where("uid=$b_id")->field("openid,rank")->find();
		if ($user['rank'] > 0) {
			$money2 = $sys_set['bscale'] * $money;
			$money2 = sprintf("%.2f", substr(sprintf("%.3f", $money2), 0, -2));
			send_templet_msg($user['openid'], 2, "/Home/wap/my_account", $order_no, $money, $money2, "二级分销" . $user_info['nickname']);
		}
	}
	if ($c_id > 0) {
		$user = D("wx_user")->where("uid=$c_id")->field("openid,rank")->find();
		if ($user['rank'] > 0) {
			$money2 = $sys_set['cscale'] * $money;
			$money2 = sprintf("%.2f", substr(sprintf("%.3f", $money2), 0, -2));
			send_templet_msg($user['openid'], 2, "/Home/wap/my_account", $order_no, $money, $money2, "三级分销" . $user_info['nickname']);
		}
	}
}
function send_up_order_msg($uid = 0, $order_id, $buy_money = 0, $fx_money = 0) {
	$openid = D("wx_user")->where("uid=$uid")->getField("openid");
	$fx_money = sprintf("%.2f", substr(sprintf("%.3f", $fx_money), 0, -2));
	$buy_money = sprintf("%.2f", substr(sprintf("%.3f", $buy_money), 0, -2));
	send_templet_msg($openid, 2, "/Home/wap/pay_record", $order_id, $buy_money, $fx_money);
}

function send_xj_msg($uid = 0, $nickname) {
	$user_info = get_user_info($uid);
	$user_data = get_user_data($uid);
	send_templet_msg($user_info['openid'], 1, "/Home/wap/my_team", $nickname, ($user_data['xj_num']));
	if ($user_info['a_id'] > 0) {
		$b1 = get_user_info($user_info['a_id']);
		$b2 = get_user_info($user_info['a_id']);
		send_templet_msg($b1['openid'], 11, "/Home/wap/my_team", $nickname, ($b2['xj_num']), $user_info['nickname']);
	}
	if ($user_info['b_id'] > 0) {
		$c1 = get_user_info($user_info['b_id']);
		$c2 = get_user_info($user_info['b_id']);
		send_templet_msg($c1['openid'], 12, "/Home/wap/my_team", $nickname, ($c2['xj_num']), $user_info['nickname']);
	}
}

function send_admin_msg($uid = 0, $money = 0, $order_no = 0) {
	$openid = "oMnnYwb5SOOTxfdDV8KurKPZ6-v0";
	send_templet_msg($openid, 5, "/Home/wap/my_team", $uid, $money, $order_no, date("Y-m-d H:i:s"));
}
function do_sh($id = 0) {
	$goods_order = D("goods_order");
	$goods_order->startTrans(); //事务开始
	$order_info = $goods_order->where("id=$id")->find();
	if ($order_info['status'] == 4) {
		$rd['code'] = 40081; //已确认
		$rd['msg'] = "已确认收货";
		return $rd;
	}
	$map['status'] = 4;
	$s1 = $goods_order->where("id=$id and status=3")->save($map);
	$map2['status'] = 2;
	$pay_record = D("pay_record");

	$s2 = $pay_record->where("pid=$id and type=3 and status=1")->save($map2);
	$arr22 = $pay_record->where("pid=$id and type=3 and status=2")->select();
	$s3 = 1;
	$s2 = 1;
	if (!$arr22) {
		$s2 = 1;
	} else {
		// $Model = new \Think\Model();
		foreach ($arr22 as $key => $e) {
			$money = floatval($e['money']);
			$uid = $e['uid'];
			$arr3 = D("user_data")->where("uid=$uid")->field("money,fx_money,fxz_money,uid")->find();
			$rank = D("wx_user")->where("uid=$uid")->getField("rank");
			if ($rank > 1) {
				$map3['money'] = $arr3['money'] + $money;
				$map3['fx_money'] = $arr3['fx_money'] + $money;
				//$map3['whd_money'] = $arr3['whd_money'];
			}

			$map3['fxz_money'] = $arr3['fxz_money'] - $money;
			$s3 = D("user_data")->where("uid=$uid")->save($map3);
			unset($map3);
			//$s3 = $Model->query("UPDATE pg_user_data set money=money+$money,fx_money=fx_money+$money,fxz_money=fxz_money-$money where uid=$uid");
		}
	}

	if ($s3 > 0 && $s2 > 0 && $s1 > 0) {
		$goods_order->commit();
		$rd['code'] = 1;
		$rd['msg'] = "收货成功";
	} else {
		$goods_order->rollback();
		$rd['code'] = 40082;
		$rd['msg'] = "收货失败";
	}
	return $rd;

}

function do_th($id = 0) {
	$map['status'] = -2;
	$s = D("goods_order")->where("id=$id and status=2")->save($map);
	if ($s > 0) {
		$rd['code'] = 1;
		$rd['msg'] = "申请退货成功";
	} else {
		$rd['code'] = 40090; //申请退货失败
		$rd['msg'] = "申请退货失败";
	}
	return $rd;
}
function do_qxth($id = 0) {
	$map['status'] = 2;
	$s = D("goods_order")->where("id=$id and status=-2")->save($map);
	if ($s > 0) {
		$rd['code'] = 1;
		$rd['msg'] = "取消退货成功";
	} else {
		$rd['code'] = 40090; //申请退货失败
		$rd['msg'] = "取消退货失败";
	}
	return $rd;
}
/**
 * 发送模板消息
 * @wxy
 * @DateTime 2016-05-20T10:05:45+0800
 * @return   [type]                   [description]
 */
function send_templet_msg($openid = "", $type, $url = "", $key1, $key2, $key3, $key4) {
	require_once THINK_PATH . "Weixin/WxApi.class.php";
	$wx = new WxApi;
	//$openid = "oyjjwwxHXRJ5ohEDzruTXGPywgxA";
	//$openid = "oyjjww5L6L2ANd-E39Wj6pnhSEfk";
	$url = "http://" . $_SERVER['SERVER_NAME'] . $url;
	switch ($type) {
	case '1':
		$template_id = "FzR4bJ1GJXpd3pMwlzhvIuI8LM1i-DJrvmDhZWsqfZU"; //新增一个下级会员
		$data['first']['value'] = "恭喜您成功推荐了一个一级分销";
		$data['first']['color'] = "#173177";
		$data['keyword1']['value'] = $key1; //下级名称
		$data['keyword1']['color'] = "#173177";
		$data['keyword2']['value'] = date("Y-m-d H:i:s");
		$data['keyword2']['color'] = "#173177";
		$data['remark']['value'] = "赶快推荐更多好友吧";
		//$data['remark']['value'] = "您当前有" . $key2 . "个下级";
		$data['remark']['color'] = "#55452";

		break;
	case '11':
		$template_id = "FzR4bJ1GJXpd3pMwlzhvIuI8LM1i-DJrvmDhZWsqfZU"; //新增一个下级会员
		$data['first']['value'] = "恭喜您的一级分销" . $key3 . "为您成功推荐了一个二级分销";
		$data['first']['color'] = "#173177";
		$data['keyword1']['value'] = $key1; //下级名称
		$data['keyword1']['color'] = "#173177";
		$data['keyword2']['value'] = date("Y-m-d H:i:s");
		$data['keyword2']['color'] = "#173177";
		$data['remark']['value'] = "赶快推荐更多好友吧";
		//$data['remark']['value'] = "您当前有" . $key2 . "个下级";
		$data['remark']['color'] = "#55452";

		break;
	case '12':
		$template_id = "FzR4bJ1GJXpd3pMwlzhvIuI8LM1i-DJrvmDhZWsqfZU"; //新增一个下级会员
		$data['first']['value'] = "恭喜您的二级分销" . $key3 . "为您成功推荐了一个三级分销";
		$data['first']['color'] = "#173177";
		$data['keyword1']['value'] = $key1; //下级名称
		$data['keyword1']['color'] = "#173177";
		$data['keyword2']['value'] = date("Y-m-d H:i:s");
		$data['keyword2']['color'] = "#173177";
		$data['remark']['value'] = "赶快推荐更多好友吧";
		//$data['remark']['value'] = "您当前有" . $key2 . "个下级";
		$data['remark']['color'] = "#55452";

		break;
	case '2':
		$template_id = "MtNg35W-12VCOoyfcIcN1mNth7RlTqtkCi_bsYjo0Po"; //下级消费返现
		$data['first']['value'] = "亲，您的" . $key4 . "成功支付了一笔订单，继续加油哦";
		$data['first']['color'] = "#173177";
		$data['keyword1']['value'] = $key1;
		$data['keyword1']['color'] = "#173177";
		$data['keyword2']['value'] = $key2;
		$data['keyword2']['color'] = "#173177";
		$data['keyword3']['value'] = $key3;
		$data['keyword3']['color'] = "#173177";
		$data['keyword4']['value'] = date("Y-m-d H:i:s");
		$data['keyword4']['color'] = "#173177";
		$data['remark']['value'] = "备注:只有购买过99元创业礼包,才可获得该分成金额。";
		$data['remark']['color'] = "#55452";
		break;
	case '3':
		$template_id = "F8VSpjL9ygVqwODNkYbJuy3CTjYIR1Dwf6OAuJRPp8Q"; //订单支付成功
		$data['first']['value'] = "您好，您的订单已支付成功！";
		$data['first']['color'] = "#173177";
		$data['keyword1']['value'] = $key1; //商品名称
		$data['keyword1']['color'] = "#173177";
		$data['keyword2']['value'] = $key2; //订单编号
		$data['keyword2']['color'] = "#173177";
		$data['keyword3']['value'] = $key3; //支付金额
		$data['keyword3']['color'] = "#173177";
		$data['remark']['value'] = "赶快推荐更多好友来购买吧";
		$data['remark']['color'] = "#55452";
		break;
	case '4':
		$template_id = "m0gdSfH2hXpJOUMI2unLT4GozV1zzERD4EOyBcsoCIQ"; //订单支付成功
		$data['first']['value'] = "您好，您的订单已发货";
		$data['first']['color'] = "#173177";
		$data['keyword1']['value'] = "YN88888"; //YN88888
		$data['keyword1']['color'] = "#173177";
		$data['keyword2']['value'] = "顺丰快递"; //快递公司
		$data['keyword2']['color'] = "#173177";
		$data['keyword3']['value'] = "10000004567"; //快递单号
		$data['keyword3']['color'] = "#173177";
		$data['remark']['value'] = "点击查看物流信息。";
		$data['remark']['color'] = "#55452";
		break;

	case '5':
		$template_id = "-aTczNIrtQwjqTaZYIn2g_poT1P7TCi7RPXL11rEVUk"; //订单支付成功
		$data['first']['value'] = "您好，您网站上有一笔新订单";
		$data['first']['color'] = "#173177";
		$data['keyword1']['value'] = $key1; //YN88888
		$data['keyword1']['color'] = "#173177";
		$data['keyword2']['value'] = $key2; //快递公司
		$data['keyword2']['color'] = "#173177";
		$data['keyword3']['value'] = $key3; //快递单号
		$data['keyword3']['color'] = "#173177";
		$data['keyword4']['value'] = $key4; //快递单号
		$data['keyword4']['color'] = "#173177";
		$data['remark']['value'] = "请您及时发货";
		$data['remark']['color'] = "#55452";
		break;
	case '6':
		$template_id = "QgA-6uQD7aa_xwYihI5MAMF66rCRGNibC1O5VyO0COQ"; //拼团成功
		$data['first']['value'] = "恭喜您，拼团成功！我们会尽快为您发货";
		$data['first']['color'] = "#173177";
		$data['keyword1']['value'] = $key1; //YN88888
		$data['keyword1']['color'] = "#173177";
		$data['keyword2']['value'] = $key2; //快递公司
		$data['keyword2']['color'] = "#173177";
		$data['remark']['value'] = "点击查看订单详情";
		$data['remark']['color'] = "#55452";
		break;

	case '7':
		$template_id = "AVRlBryPn_kpq_BwIZKA4APhrFX3y7PAqkcWDIG1CGs"; //拼团失败
		$data['first']['value'] = "对不起，你的拼团失败了，我们会尽快返还你的订单金额。";
		$data['first']['color'] = "#173177";
		$data['keyword1']['value'] = $key1; //YN88888
		$data['keyword1']['color'] = "#173177";
		$data['keyword2']['value'] = $key2; //快递公司
		$data['keyword2']['color'] = "#173177";

		$data['remark']['value'] = "如有疑问，请拨打我们的服务电话：18660102962";
		$data['remark']['color'] = "#55452";
		break;

	default:

		break;
	}

	//$data = json_encode($data);
	//p($data);exit;
	$res = $wx->wxSetSend($openid, $template_id, $url, $data);

}

function get_kd_info($ems_no = "") {
	$typeCom = "申通快递"; //快递公司
	$typeNu = $ems_no; //快递单号

	//echo $typeCom.'<br/>' ;
	//	echo $typeNu;exit;

	$AppKey = 'fdd08da919d1338b'; //请将XXXXXX替换成您在http://kuaidi100.com/app/reg.html申请到的KEY
	$url = 'http://api.kuaidi100.com/api?id=' . $AppKey . '&com=' . $typeCom . '&nu=' . $typeNu . '&show=2&muti=1&order=asc';
	p($url);
	//$url="http://api.kuaidi100.com/api?id=[]&com=[]&nu=[]&valicode=[]&show=[0|1|2|3]&muti=[0|1]&order=[desc|asc]"
	//请勿删除变量$powered 的信息，否者本站将不再为你提供快递接口服务。
	$powered = '查询数据由：<a href="http://kuaidi100.com" target="_blank">KuaiDi100.Com （快递100）</a> 网站提供 ';

	//优先使用curl模式发送数据
	if (function_exists('curl_init') == 1) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($curl, CURLOPT_TIMEOUT, 5);
		$get_content = curl_exec($curl);
		curl_close($curl);
	} else {
		var_dump(121);exit;
		include "snoopy.php";
		$snoopy = new snoopy();
		$snoopy->referer = 'http://www.google.com/'; //伪装来源
		$snoopy->fetch($url);
		$get_content = $snoopy->results;
	}
	print_r($get_content . '<br/>' . $powered);
	exit();
}

function isMobile() {
	// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
	if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
		return true;
	}
	// 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
	if (isset($_SERVER['HTTP_VIA'])) {
		// 找不到为flase,否则为true
		return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
	}
	// 脑残法，判断手机发送的客户端标志,兼容性有待提高
	if (isset($_SERVER['HTTP_USER_AGENT'])) {
		$clientkeywords = array('nokia',
			'sony',
			'ericsson',
			'mot',
			'samsung',
			'htc',
			'sgh',
			'lg',
			'sharp',
			'sie-',
			'philips',
			'panasonic',
			'alcatel',
			'lenovo',
			'iphone',
			'ipod',
			'blackberry',
			'meizu',
			'android',
			'netfront',
			'symbian',
			'ucweb',
			'windowsce',
			'palm',
			'operamini',
			'operamobi',
			'openwave',
			'nexusone',
			'cldc',
			'midp',
			'wap',
			'mobile',
		);
		// 从HTTP_USER_AGENT中查找手机浏览器的关键字
		if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
			return true;
		}
	}
	// 协议法，因为有可能不准确，放到最后判断
	if (isset($_SERVER['HTTP_ACCEPT'])) {
		// 如果只支持wml并且不支持html那一定是移动设备
		// 如果支持wml和html但是wml在html之前则是移动设备
		if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
			return true;
		}
	}
	return false;
}

function get_is_guanzhu() {
	require_once THINK_PATH . "Weixin/WxApi.class.php";

	$wx = new WxApi;
	$arr = get_user_info();
	$openid = $arr['openid'];
	$res = $wx->wxGetUser($openid);
	if ($res['subscribe'] == $arr['subscribe']) {

	} else {
		$map['subscribe'] = $res['subscribe'];
		D("wx_user")->where("openid='" . $openid . "'")->save($map);
	}
	//p($res['subscribe']);exit;
	$rd['code'] = 1;
	$rd['msg'] = "成功";
	$rd['subscribe'] = $res['subscribe'];
	return $rd;
}

// function do_recharge($id) {
// 	$pay_record = D("pay_record");
// 	$pay_record->startTrans(); //事务开始
// 	$map['status'] = 2;
// 	$arr = $pay_record->where("id=$id")->find();
// 	$s1 = $pay_record->where("id=$id")->save($map);
// 	$s2 = D("user_data")->where("uid=$uid")->setInc("money", $arr['money']);
// 	if ($s1 > 0 && $s2 > 0) {
// 		$pay_record->commit();
// 		$rd['code'] = 1;
// 		$rd['msg'] = "充值成功";
// 	} else {
// 		$pay_record->rollback();
// 	}
// }

//打印输出数组信息
function printf_info($data) {
	foreach ($data as $key => $value) {
		echo "<font color='#00ff55;'>$key</font> : $value <br/>";
	}
}

function get_openid() {
	$uid = uid();
	$arr = S("openid_" . $uid);
	if (empty($arr)) {
		$arr = D("wx_user")->where("uid=$uid")->getField("openid");
		S("openid_", $uid);
	}
	return $arr;
}

function add_out_trade_no($pay_id, $out_trade_no) {
	$pay_record = D("pay_record");
	$data['out_trade_no'] = $out_trade_no;
	$arr = $pay_record->where("id=$pay_id")->find();
	if ($arr['status'] != 1) {
		echo "<script>alert('订单已支付');location.href='/index.php/Home/Wap/user_center'</script>";return false;exit;
	}
	if ($arr['out_trade_no'] == "") {

		$pay_record->where("id=$pay_id")->save($data);
		$res['out_trade_no'] = $out_trade_no;
	} else {
		$res['out_trade_no'] = $arr['out_trade_no'];
	}
	$res['money'] = $arr['money'];

	//return $arr['money'];
	return $res;
}

function get_order_num($uid = 0) {
	$arr['n1'] = D("goods_order")->where("uid=$uid and status=1")->count();
	$arr['n2'] = D("goods_order")->where("uid=$uid and status=2")->count();
	$arr['n3'] = D("goods_order")->where("uid=$uid and status=3")->count();
	$arr['n4'] = D("goods_order")->where("uid=$uid and status=4")->count();
	//p($arr);
	return $arr;
}

function change_headimgurl($url = "") {
	//$url="http://wx.qlogo.cn/mmopen/3tm40b3DvD8uj31bmDJRJYJlwG63nSflTyNFJS4tiagicRiaKJ1zibXcMeWkwtnggkOjtNAhOtmg8seVr2Dg2PZcvy4UFGKiatpz2/64";
	$url = rtrim($url, "0");
	$url = $url . "64";
	echo $url;
}

function build_order_no() {
	return date('YmdHis') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
}
