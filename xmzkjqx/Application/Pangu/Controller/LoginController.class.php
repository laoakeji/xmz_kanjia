<?php
namespace Pangu\Controller;
use Think\Controller;

class LoginController extends Controller {

	public function __construct() {
		parent::__construct();
	}
	public function index() {
		$this->display();
	}
	public function do_login() {
		$uname = I("post.uname");
		$password = I("post.password");
		$arr = D("admin")->where("uname='" . $uname . "'")->find();
		if (!$arr) {
			$rd['code'] = 50001; //账户名不存在
			$rd['msg'] = "账户名不存在"; //账户名不存在
		} else {
			if ($arr['password'] == md5($password)) {
				$rd['code'] = 1; //
				$rd['msg'] = "登陆成功"; //
				$_SESSION['admin_id'] = $arr['id'];
			} else {
				$rd['code'] = 50002; //密码错误
				$rd['msg'] = "密码错误"; //账户名不存在
			}
		}
		$this->ajaxReturn($rd);
	}
}