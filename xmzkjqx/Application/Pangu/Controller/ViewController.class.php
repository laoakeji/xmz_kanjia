<?php
namespace Pangu\Controller;
use Think\Controller;

class ViewController extends Controller {
	public function __construct() {
		parent::__construct();
		check_admin_login();
	}
	
	/**
	 * 系统信息
	 * @wxy
	 * @DateTime 2016-05-20T16:11:00+0800
	 * @return   [type]                   [description]
	 */
	public function welcome() {
		//p(build_order_no());
		$arr['php_uname1'] = php_uname('s');
		$arr['php_uname2'] = php_uname('r');
		$arr['php_sapi_name'] = php_sapi_name();
		$arr['PHP_VERSION'] = PHP_VERSION;
		$arr['DEFAULT_INCLUDE_PATH'] = DEFAULT_INCLUDE_PATH;
		$arr['host'] = $_SERVER["HTTP_HOST"];
		$arr['ip'] = GetHostByName($_SERVER['SERVER_NAME']);
		$arr['root'] = $_SERVER['SystemRoot'];
		$arr['port'] = $_SERVER['SERVER_PORT'];
		$arr['day'] = date("Y-m-d");

		$this->assign('sys', $arr);
		$this->display();
	}

	public function llist(){
		$sql = "select * from pg_goods";
		$table = M();
		$list = $table->query($sql);
		$this->assign("list",$list);
		$this->display();
	}

	public function kanjia(){
		$sql = "SELECT a.goods_no,a.goods_name,b.* FROM pg_goods a,pg_kanjia b WHERE a.id = b.pid";
		$table = M();
		$list = $table->query($sql);
		$this->assign("list",$list);
		$this->display();
	}

	public function prize(){
		$type=I("get.type");
		$sql = "select a.id,b.uid,a.tel,a.name,b.nickname,a.lid,a.c_time,a.e_time from";
		$data = array();
		
		if($type==1){
			$sql .= " pg_lrecord a,pg_user b where a.uid = b.id";
			$data = D("list")->field("type,name")->select();
		}else{
			$sql .= " pg_trecord a,pg_user b where a.uid = b.id";
			$data = D("tuan")->field("type")->select();
		}

		$table = M();
		$list = $table->query($sql);
		$this->assign("list",$list);
		$this->assign("data",$data);
		$this->assign("type",$type);
		$this->display();
	}

	/**
	 * 通用提交编辑
	 * @wxy
	 * @DateTime 2016-05-20T16:39:51+0800
	 * @return   [type]                   [description]
	 */
	public function do_edit() {
		$id = I("get.id", 0);
		$table = I("get.table", 0);
		$data = I("post.");

		if ($table == "match") {
			$data['c_time'] = strtotime($data['c_time']);
		}

		if ($id == 0) {
			$s = D($table)->add($data);
		} else {
			$data['k_time'] = time();
			$data['status'] = 1;//1表示已经开奖了
			$s = D($table)->where("id=$id")->save($data);
		}
		if ($table == "sys_set") {
			S("sys_set", null);
		}
		echo $s;
	}

	/**
	 * 删除公用方法
	 * @wxy
	 * @DateTime 2016-05-21T09:56:38+0800
	 * @return   [type]                   [description]
	 */
	public function do_del() {
		$id = I("post.id");
		$table = I("post.table");
		if ($table == "wx_user") {
			$s = D($table)->where("uid=$id")->delete();
		} else {
			$s = D($table)->where("id=$id")->delete();
		}
		switch ($table) {
		case 'goods':
			S("goods_" . $id, null);
			S("goods_base_" . $id, null);
			break;

		default:
			# code...
			break;
		}

		echo $s;
	}

	/**
	 * 删除全部
	 * @wxy
	 * @DateTime 2016-05-21T09:59:24+0800
	 * @return   [type]                   [description]
	 */
	public function do_del_all() {
		$ids = I("get.ids");
		$ids = rtrim($ids, ",");
		$table = I("get.table");
		if ($table == "wx_user") {
			$s = D($table)->where("uid in ($ids)")->delete();
		} else {
			$s = D($table)->where("id in ($ids)")->delete();
		}
		$ids = explode(',', $ids);
		switch ($table) {
		case 'goods':
			foreach ($ids as $key => $e) {
				S("goods_" . $e, null);
				S("goods_base_" . $e, null);
			}
			break;
		default:
			# code...
			break;
		}
		echo $s;
	}

	/**
	 * 通用编辑页面
	 * @wxy
	 * @DateTime 2016-05-20T16:39:25+0800
	 * @return   [type]                   [description]
	 */
	public function edit() {
		$id = I("get.id", 0);
		$table = I("get.table", 0);
		$arr = D($table)->where("id=$id")->find();

		$this->assign("list", $arr);
		$this->assign("id", $id);
		$this->display("edit_" . $table);
	}

	public function gettotal(){
		$rand_a = 0.01;
		$rand_b = 68;

		$price = 13990;
		$low_price = 700;

		$data = array();
		$money = 0;
		$getCount = 0;

		for($i=0;$i<10000;$i++){
			$money = 0;
			for($j=0;$j<88;$j++){
				$rand = rand($rand_a*100,$rand_b*100)*0.01;
				$money += $rand;
			}
			if($money>=4200){
				$getCount++;
			}
		}

		echo $getCount/100;exit;


	}

	public function kj_result(){
		$sql = "SELECT a.*,b.nickname FROM pg_kanjia_record a,pg_wx_user b WHERE a.uid = b.uid ";
		$table = M();
		$list = $table->query($sql);
	 	$count = count($list);
		$this->assign("list",$list);
		$this->assign("count",$count);
		$this->display();
	}

	public function dk_result(){
		$sql = "SELECT a.*,b.nickname FROM pg_dk_record a,pg_wx_user b where a.uid = b.uid";
		$table = M();
		$list = $table->query($sql);
	 	$count = count($list);

	 	$sql_all = "SELECT * FROM pg_dk_record  where status=2 and num = 10";
		$list_all = $table->query($sql_all);
	 	$count_all = count($list_all);

		$this->assign("list",$list);
		$this->assign("count",$count);
		$this->assign("count_all",$count_all);
		$this->display();
	}

	public function index() {
		$sql = "SELECT a.id,a.k_price,a.c_time,c.goods_name,b.nickname FROM pg_kanjia_record a,pg_wx_user b,pg_goods c WHERE a.uid = b.uid AND a.kid = c.id ORDER BY a.id";
		$list = M()->query($sql);
		$count1 = count($list);

		$sql = "SELECT a.id,a.k_price,a.c_time,c.goods_name,b.nickname FROM pg_kanjia_help_record a,pg_wx_user b,pg_goods c,pg_kanjia_record d WHERE a.uid = b.uid AND d.kid = c.id AND d.id = a.krid";
		$list = M()->query($sql);
		$count2 = count($list);

		$sql = "SELECT a.id,a.k_price,a.c_time,c.goods_name,b.nickname,c.price FROM pg_kanjia_record a,pg_wx_user b,pg_goods c WHERE a.uid = b.uid AND a.kid = c.id and a.is_prize = 2 ORDER BY a.id";
		$list = M()->query($sql);
		$count3 = count($list);

		/*$count1 = D("kanjia_record")->count();
		$count2 = D("kanjia_help_record")->count();
		$count3 = D("kanjia_record")->where("is_prize = 2")->count();*/

		$this->assign("count1",$count1);
		$this->assign("count2",$count2);
		$this->assign("count3",$count3);
		$this->display();
	}

	public function s1(){
		$sql = "SELECT a.id,a.k_price,a.c_time,c.goods_name,b.nickname FROM pg_kanjia_record a,pg_wx_user b,pg_goods c WHERE a.uid = b.uid AND a.kid = c.id ORDER BY a.id";
		$list = M()->query($sql);
		$count = count($list);
		$this->assign("list",$list);
		$this->assign("num",$count);
		$this->display();
	}

	public function s2(){
		$sid = I("get.sid",0);
		$krid = I("get.id",0);
		if($sid !=0){
			$sql = "SELECT a.id,a.k_price,a.c_time,c.goods_name,b.nickname FROM pg_kanjia_help_record a,pg_wx_user b,pg_goods c,pg_kanjia_record d WHERE a.uid = b.uid AND d.kid = c.id AND d.id = a.krid ";
		}else{
			$sql = "SELECT a.id,a.k_price,a.c_time,c.goods_name,b.nickname FROM pg_kanjia_help_record a,pg_wx_user b,pg_goods c,pg_kanjia_record d WHERE a.uid = b.uid AND d.kid = c.id AND d.id = a.krid AND krid = ".$krid;
		}
		
		$list = M()->query($sql);
		$count = count($list);
		$this->assign("list",$list);
		$this->assign("num",$count);
		$this->display();
	}

	public function s3(){
		$sql = "SELECT a.id,a.k_price,a.c_time,c.goods_name,b.nickname,c.price,a.k_peo FROM pg_kanjia_record a,pg_wx_user b,pg_goods c WHERE a.uid = b.uid AND a.kid = c.id and a.is_prize = 2 ORDER BY a.id";
		$list = M()->query($sql);
		$count = count($list);
		$this->assign("list",$list);
		$this->assign("num",$count);
		$this->display();
	}
}